<?php

/**
 * Description of PriceFormulaSettingsRepository
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class PriceFormulaSettingsRepository
{
    public function getPricingRulesType(): string
    {
        return get_setting('pricing_rules_type', PriceFormulaService::SALE_PRICE_AS_BASE);
    }

    public function setPricingRulesType(string $pricingRulesType): self
    {
        set_setting('pricing_rules_type', $pricingRulesType);

        return $this;
    }

    public function getApplyPriceRulesAfterShippingCost(): bool
    {
        return get_setting('apply_price_rules_after_shipping_cost');
    }

    public function setApplyPriceRulesAfterShippingCost(bool $applyPriceRulesAfterShippingCost): self
    {
        set_setting('apply_price_rules_after_shipping_cost', $applyPriceRulesAfterShippingCost);

        return $this;
    }

    public function getAddShippingToPrice(): bool
    {
        return  get_setting('add_shipping_to_price');
    }

    public function setAddShippingToPrice(bool $addShippingToPrice): self
    {
        set_setting('add_shipping_to_price', $addShippingToPrice);

        return $this;
    }

    public function getUseExtendedPriceMarkup(): bool
    {
        return get_setting('use_extended_price_markup');
    }

    public function setUseExtendedPriceMarkup(bool $useExtendedPriceMarkup): self
    {
        set_setting('use_extended_price_markup', $useExtendedPriceMarkup);

        return $this;
    }

    public function getUseComparedPriceMarkup(): bool
    {
        return get_setting('use_compared_price_markup');
    }

    public function setUseComparedPriceMarkup(bool $useComparedPriceMarkup): self
    {
        set_setting('use_compared_price_markup', $useComparedPriceMarkup);

        return $this;
    }

    public function getPriceCents(): float|int
    {
        return get_setting('price_cents');
    }

    public function setPriceCents(float|int $cents): self
    {
        set_setting('price_cents', $cents);

        return $this;
    }

    public function getPriceComparedCents(): float|int
    {
        return get_setting('price_compared_cents');
    }

    public function setPriceComparedCents(float|int $comparedCents): self
    {
        set_setting('price_compared_cents', $comparedCents);

        return $this;
    }

    public function getUseSeparateFormula(): bool
    {
        return a2wl_check_defined('A2WL_USE_SEPARATE_FORMULA');
    }
}
