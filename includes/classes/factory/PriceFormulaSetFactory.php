<?php
/**
 * Description of PriceFormulaSetFactory
 *
 * @author Ali2Woo Team
 *
 */

namespace AliNext_Lite;;

class PriceFormulaSetFactory
{
    private PriceFormulaFactory $PriceFormulaFactory;

    public function __construct(PriceFormulaFactory $PriceFormulaFactory)
    {
        $this->PriceFormulaFactory = $PriceFormulaFactory;
    }

    /**
     * @param array $data
     * @return PriceFormulaSet
     */
    public function createFormulaSetFromData(array $data): PriceFormulaSet
    {
        $setName = $data[PriceFormulaSet::NAME_FIELD];
        $PriceFormulaDefault = $this->PriceFormulaFactory->createFormulaFromData(
            $data[PriceFormulaSet::DEFAULT_FORMULA_FIELD]
        );
        $ExtendedFormulas = [];
        foreach ($data[PriceFormulaSet::FORMULA_LIST_FIELD] as $priceFormulaDate) {
            $ExtendedFormulas[] = $this->PriceFormulaFactory->createFormulaFromData(
                $priceFormulaDate
            );
        }

        return (new PriceFormulaSet())
            ->setName($setName)
            ->setDefaultFormula($PriceFormulaDefault)
            ->setExtendedFormulas($ExtendedFormulas)
            ->setCents($data[PriceFormulaSet::PRICE_CENTS_FIELD])
            ->setComparedCents($data[PriceFormulaSet::PRICE_COMPARED_CENTS_FIELD])
            ->setUseExtendedPriceMarkup($data[PriceFormulaSet::USE_EXTENDED_PRICE_MARKUP_FIELD])
            ->setUseComparedPriceMarkup($data[PriceFormulaSet::USE_COMPARED_PRICE_MARKUP_FIELD])
            ->setAddShippingToPrice($data[PriceFormulaSet::ADD_SHIPPING_TO_PRICE_FIELD])
            ->setApplyPriceRulesAfterShippingCost($data[PriceFormulaSet::APPLY_PRICE_RULES_AFTER_SHIPPING_COST_FIELD])
            ->setPricingRulesType($data[PriceFormulaSet::PRICING_RULES_TYPE_FIELD]);
    }

    public function createFormulaSet(
        string $setName, PriceFormula $PriceFormulaDefault, array $ExtendedFormulas, int $cents,
        int $comparedCents, bool $useExtendedPriceMarkup, bool $useComparedPriceMarkup, bool $addShippingToPrice,
        bool $applyPriceRulesAfterShippingCost, string $pricingRulesType
    ): PriceFormulaSet {

        return (new PriceFormulaSet())
            ->setName($setName)
            ->setDefaultFormula($PriceFormulaDefault)
            ->setExtendedFormulas($ExtendedFormulas)
            ->setCents($cents)
            ->setComparedCents($comparedCents)
            ->setUseExtendedPriceMarkup($useExtendedPriceMarkup)
            ->setUseComparedPriceMarkup($useComparedPriceMarkup)
            ->setAddShippingToPrice($addShippingToPrice)
            ->setApplyPriceRulesAfterShippingCost($applyPriceRulesAfterShippingCost)
            ->setPricingRulesType($pricingRulesType);
    }
}
