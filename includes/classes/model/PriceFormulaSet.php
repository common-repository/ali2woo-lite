<?php

/**
 * Description of PriceFormulaSet
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class PriceFormulaSet
{
    public const NAME_FIELD = 'name';
    public const DEFAULT_FORMULA_FIELD = 'default_formula';
    public const FORMULA_LIST_FIELD = 'formula_list';
    public const PRICE_CENTS_FIELD = 'price_cents';
    public const PRICE_COMPARED_CENTS_FIELD = 'price_compared_cents';
    public const USE_EXTENDED_PRICE_MARKUP_FIELD = 'use_extended_price_markup';
    public const USE_COMPARED_PRICE_MARKUP_FIELD = 'use_compared_price_markup';
    public const ADD_SHIPPING_TO_PRICE_FIELD = 'add_shipping_to_price';
    public const APPLY_PRICE_RULES_AFTER_SHIPPING_COST_FIELD = 'apply_price_rules_after_shipping_cost';
    public const PRICING_RULES_TYPE_FIELD = 'pricing_rules_type';

    private string $name;
    
    private PriceFormula $PriceFormulaDefault;

    /**
     * @var array|PriceFormula[]
     */
    private array $PriceFormulasExtended = [];

    private int $cents = -1;
    private int $comparedCents = -1;

    private bool $useExtendedPriceMarkup = false;

    private bool $useComparedPriceMarkup = false;

    private bool $addShippingToPrice = false;

    private bool $applyPriceRulesAfterShippingCost = false;

    public string $pricingRulesType;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDefaultFormula(): PriceFormula
    {
        return $this->PriceFormulaDefault;
    }

    public function setDefaultFormula(PriceFormula $PriceFormula): self
    {
        $this->PriceFormulaDefault = $PriceFormula;

        return $this;
    }

    /**
     * @return PriceFormula[]|array
     */
    public function getExtendedFormulas(): array
    {
        return $this->PriceFormulasExtended;
    }

    /**
     * @param PriceFormula[]|array $PriceFormulas
     * @return $this
     */
    public function setExtendedFormulas(array $PriceFormulas): self
    {
        $this->PriceFormulasExtended = $PriceFormulas;

        return $this;
    }

    public function getCents(): int
    {
        return $this->cents;
    }

    public function setCents(int $cents): self
    {
        $this->cents = $cents;

        return $this;
    }

    public function getComparedCents(): int
    {
        return $this->comparedCents;
    }

    public function setComparedCents(int $comparedCents): self
    {
        $this->comparedCents = $comparedCents;

        return $this;
    }

    public function getUseExtendedPriceMarkup(): bool
    {
        return $this->useExtendedPriceMarkup;
    }

    public function setUseExtendedPriceMarkup(bool $useExtendedPriceMarkup): self
    {
        $this->useExtendedPriceMarkup = $useExtendedPriceMarkup;

        return $this;
    }

    public function getUseComparedPriceMarkup(): bool
    {
        return $this->useComparedPriceMarkup;
    }

    public function setUseComparedPriceMarkup(bool $useComparedPriceMarkup): self
    {
        $this->useComparedPriceMarkup = $useComparedPriceMarkup;

        return $this;
    }

    public function getAddShippingToPrice(): bool
    {
        return $this->addShippingToPrice;
    }

    public function setAddShippingToPrice(bool $addShippingToPrice): self
    {
        $this->addShippingToPrice = $addShippingToPrice;

        return $this;
    }

    public function getApplyPriceRulesAfterShippingCost(): bool
    {
        return $this->applyPriceRulesAfterShippingCost;
    }

    public function setApplyPriceRulesAfterShippingCost(bool $applyPriceRulesAfterShippingCost): self
    {
        $this->applyPriceRulesAfterShippingCost = $applyPriceRulesAfterShippingCost;

        return $this;
    }

    public function getPricingRulesType(): string
    {
        return $this->pricingRulesType;
    }

    public function setPricingRulesType(string $pricingRulesType): self
    {
        $this->pricingRulesType = $pricingRulesType;

        return $this;
    }

    public function toArray(): array
    {
        $extendedFormulas = [];

        foreach ($this->getExtendedFormulas() as $PriceFormula) {
            $extendedFormulas[] = $PriceFormula->toArray();
        }

        return [
            self::NAME_FIELD => $this->getName(),
            self::DEFAULT_FORMULA_FIELD => $this->getDefaultFormula()->toArray(),
            self::FORMULA_LIST_FIELD => $extendedFormulas,
            self::PRICE_CENTS_FIELD => $this->getCents(),
            self::PRICE_COMPARED_CENTS_FIELD => $this->getComparedCents(),
            self::USE_EXTENDED_PRICE_MARKUP_FIELD => $this->getUseExtendedPriceMarkup(),
            self::USE_COMPARED_PRICE_MARKUP_FIELD => $this->getUseComparedPriceMarkup(),
            self::ADD_SHIPPING_TO_PRICE_FIELD => $this->getAddShippingToPrice(),
            self::APPLY_PRICE_RULES_AFTER_SHIPPING_COST_FIELD => $this->getApplyPriceRulesAfterShippingCost(),
            self::PRICING_RULES_TYPE_FIELD => $this->getPricingRulesType(),
        ];
    }

}
