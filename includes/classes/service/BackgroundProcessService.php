<?php

/**
 * Description of BackgroundProcessService
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;
class BackgroundProcessService
{
    /**
     * @var BaseJobInterface[]|array $BackgroundProcesses
     */
    protected array $BackgroundProcesses;
    public function __construct(
        ApplyPricingRulesProcess $ApplyPricingRulesProcess,
        ImportProcess $importProcess
    ) {
        $this->BackgroundProcesses = [
            $ApplyPricingRulesProcess,
            $importProcess
        ];
    }

    /**
     * @return array|BaseJobInterface[]
     */
    public function getAll(): array
    {
        return $this->BackgroundProcesses;
    }

}
