<?php

/**
 * Description of PriceFormulaSetService
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

use Exception;

class PriceFormulaSetService
{
    private PriceFormulaRepository $PriceFormulaRepository;
    private PriceFormulaSettingsRepository $PriceFormulaSettingsRepository;
    private PriceFormulaSetFactory $PriceFormulaSetFactory;
    private BackgroundProcessFactory $BackgroundProcessFactory;

    public function __construct(
        PriceFormulaRepository $PriceFormulaRepository,
        PriceFormulaSettingsRepository $PriceFormulaSettingsRepository,
        PriceFormulaSetFactory $PriceFormulaSetFactory,
        BackgroundProcessFactory $BackgroundProcessFactory
    ) {
        $this->PriceFormulaRepository = $PriceFormulaRepository;
        $this->PriceFormulaSettingsRepository = $PriceFormulaSettingsRepository;
        $this->PriceFormulaSetFactory = $PriceFormulaSetFactory;
        $this->BackgroundProcessFactory = $BackgroundProcessFactory;
    }

    public function buildFromSettings(string $setName): PriceFormulaSet
    {
        $PriceFormulaDefault = $this->PriceFormulaRepository->getDefaultFormula();
        $ExtendedFormulas = $this->PriceFormulaRepository->getExtendedFormulas();

        return $this->PriceFormulaSetFactory->createFormulaSet(
            $setName,
            $PriceFormulaDefault,
            $ExtendedFormulas,
            $this->PriceFormulaSettingsRepository->getPriceCents(),
            $this->PriceFormulaSettingsRepository->getPriceComparedCents(),
            $this->PriceFormulaSettingsRepository->getUseExtendedPriceMarkup(),
            $this->PriceFormulaSettingsRepository->getUseComparedPriceMarkup(),
            $this->PriceFormulaSettingsRepository->getAddShippingToPrice(),
            $this->PriceFormulaSettingsRepository->getApplyPriceRulesAfterShippingCost(),
            $this->PriceFormulaSettingsRepository->getPricingRulesType()
        );
    }

    /**
     * @throws ServiceException
     * @throws Exception
     */
    public function choose(PriceFormulaSet $PriceFormulaSet): void
    {
        $ApplyPricingRulesProcess = $this->BackgroundProcessFactory
            ->createProcessByCode(ApplyPricingRulesProcess::ACTION_CODE);

        if ($ApplyPricingRulesProcess->is_queued()) {
            $message =  _x("Please wait until the current 'Apply Pricing Rules' process is complete.",
                'error text', 'ali2woo');
            throw new ServiceException($message);
        }

        $this->PriceFormulaRepository->setDefaultFormula(
            $PriceFormulaSet->getDefaultFormula()
        );

        foreach ($PriceFormulaSet->getExtendedFormulas() as $PriceFormula) {
            $this->PriceFormulaRepository->saveExtendedFormula($PriceFormula);
        }

        $this->PriceFormulaSettingsRepository
            ->setPriceCents($PriceFormulaSet->getCents())
            ->setPriceComparedCents($PriceFormulaSet->getComparedCents())
            ->setUseExtendedPriceMarkup($PriceFormulaSet->getUseExtendedPriceMarkup())
            ->setUseComparedPriceMarkup($PriceFormulaSet->getUseComparedPriceMarkup())
            ->setAddShippingToPrice($PriceFormulaSet->getAddShippingToPrice())
            ->setApplyPriceRulesAfterShippingCost($PriceFormulaSet->getApplyPriceRulesAfterShippingCost())
            ->setPricingRulesType($PriceFormulaSet->getPricingRulesType());
    }
}
