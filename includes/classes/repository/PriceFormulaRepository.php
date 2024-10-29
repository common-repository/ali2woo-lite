<?php

/**
 * Description of PriceFormulaRepository
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class PriceFormulaRepository
{

    private PriceFormulaFactory $PriceFormulaFactory;

    public function __construct(PriceFormulaFactory $PriceFormulaFactory)
    {
        $this->PriceFormulaFactory = $PriceFormulaFactory;
    }

    /**
     * @return array|PriceFormula[]
     */
    public function getExtendedFormulas(): array
    {
        $PriceFormulas = [];
        $formulas = $this->getAllFormulasAsArray();

        foreach ($formulas as $formula) {
            $PriceFormulas[] = $this->PriceFormulaFactory->createFormulaFromData($formula);
        }

        return $PriceFormulas;
    }

    public function getExtendedFormulasGrouped(): GetExtendedFormulasGroupedResult
    {
        $PriceFormulas = [
            'any' => [],
            'category' => [],
        ];

        $formulas = $this->getAllFormulasAsArray();

        foreach ($formulas as $formula) {
            $PriceFormula = $this->PriceFormulaFactory->createFormulaFromData($formula);

            if (!$PriceFormula->getCategoryIds()) {
                $PriceFormulas['any'][] = $PriceFormula;
            } else {
                $PriceFormulas['category'][] = $PriceFormula;
            }
        }

        return new GetExtendedFormulasGroupedResult(
            $PriceFormulas['any'],
            $PriceFormulas['category']
        );
    }

    public function deleteAll(): void
    {
        del_setting('formula_list');
    }

    public function deleteById(int $formulaId): void
    {
        $formulas = $this->getAllFormulasAsArray();
        foreach ($formulas as $key => $formula) {
            if (intval($formula['id']) === $formulaId) {
                unset($formulas[$key]);
                set_setting('formula_list', array_values($formulas));
            }
        }
    }

    public function saveExtendedFormula(PriceFormula $PriceFormula): PriceFormula
    {
        $formulas = $this->getAllFormulasAsArray();

        if (!$PriceFormula->id) {
            $PriceFormula->id = 1;
            foreach ($formulas as $key => $formula) {
                if (intval($formula['id']) >= $PriceFormula->id) {
                    $PriceFormula->id = intval($formula['id']) + 1;
                }
            }
            $formulas[] = $PriceFormula->toArray();
        } else {
            $boolean = false;
            foreach ($formulas as $key => $formula) {
                if (intval($formula['id']) === intval($PriceFormula->id)) {
                    $formulas[$key] = $PriceFormula->toArray();
                    $boolean = true;
                }
            }
            if (!$boolean) {
                $formulas[] = $PriceFormula->toArray();
            }
        }

        set_setting('formula_list', array_values($formulas));

        return $PriceFormula;
    }

    public function getDefaultFormulas(): array
    {
        $PriceFormula1 = $this->PriceFormulaFactory->createFormula()
            ->setId(1)
            ->setMinPrice(0)
            ->setMaxPrice(10)
            ->setSign("*")
            ->setValue(1);

        $PriceFormula2 = $this->PriceFormulaFactory->createFormula()
            ->setId(1)
            ->setMinPrice(10.01)
            ->setMaxPrice(null)
            ->setSign("*")
            ->setValue(null);

        return [
            $PriceFormula1,
            $PriceFormula2,
        ];
    }

    public function getDefaultFormula(): PriceFormula
    {
        $data = $this->getDefaultFormulaAsArray();

        return $this->PriceFormulaFactory->createFormulaFromData($data);
    }

    public function setDefaultFormula(PriceFormula $PriceFormula): void
    {
        set_setting('default_formula', $PriceFormula->toArray());
    }

    public function getDefaultFormulaAsArray(): array
    {
        $formula = get_setting('default_formula');

        return $formula && is_array($formula) ? $formula :
            ['value' => 1, 'sign' => '*', 'compared_value' => 1, 'compared_sign' => '*'];
    }

    private function getAllFormulasAsArray(): array
    {
       $formulas = get_setting('formula_list');

       return $formulas && is_array($formulas) ? $formulas : [];
    }

}
