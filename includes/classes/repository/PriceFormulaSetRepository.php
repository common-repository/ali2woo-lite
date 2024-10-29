<?php

/**
 * Description of PriceFormulaSetRepository
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class PriceFormulaSetRepository
{
    public const FIELD_PRICE_FORMULA_SETS = 'price_formula_sets';


    private PriceFormulaSetFactory $PriceFormulaSetFactory;

    public function __construct(
        PriceFormulaSetFactory $PriceFormulaSetFactory
    ) {
        $this->PriceFormulaSetFactory = $PriceFormulaSetFactory;
    }

    /**
     * @return array|PriceFormulaSet[]
     */
    public function getAll(): array
    {
        $result = [];
        $data = $this->getAllAsArray();

        foreach ($data as $itemData) {
            $result[] = $this->PriceFormulaSetFactory->createFormulaSetFromData($itemData);
        }

        return $result;
    }

    /**
     * @throws RepositoryException
     */
    public function getOneByName(string $setName): PriceFormulaSet
    {
        $data = $this->getAllAsArray();

        foreach ($data as $itemData) {
            if ($itemData[PriceFormulaSet::NAME_FIELD] === $setName) {
                return $this->PriceFormulaSetFactory->createFormulaSetFromData($itemData);
            }
        }

        throw new RepositoryException(
            _x( "A price formula set with that name does`t exists. Please choose a different name.",
                'error text', 'ali2woo')
        );
    }

    public function checkExists(string $setName): bool
    {
        $data = $this->getAllAsArray();

        foreach ($data as $itemData) {
            if ($itemData[PriceFormulaSet::NAME_FIELD] === $setName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws RepositoryException
     */
    public function add(PriceFormulaSet $PriceFormulaSet): void
    {
        $checkExists = $this->checkExists($PriceFormulaSet->getName());

        if ($checkExists) {
            throw new RepositoryException(
                _x( "A price formula set with that name already exists. Please choose a different name.", 'error text', 'ali2woo')
            );
        }

        $data = $this->getAllAsArray();
        $data[] = $PriceFormulaSet->toArray();
        set_setting(PriceFormulaSetRepository::FIELD_PRICE_FORMULA_SETS, $data);
    }

    public function removeOneByName(string $setName): void
    {
        $data = $this->getAllAsArray();

        $newData = [];

        foreach ($data as $itemData) {
            if ($itemData[PriceFormulaSet::NAME_FIELD] === $setName) {
               continue;
            }
            $newData[] = $itemData;
        }
        set_setting(PriceFormulaSetRepository::FIELD_PRICE_FORMULA_SETS, $newData);
    }

    private function getAllAsArray(): array
    {
        $data = get_setting(PriceFormulaSetRepository::FIELD_PRICE_FORMULA_SETS);

        return $data && is_array($data) ? $data : [];
    }
}
