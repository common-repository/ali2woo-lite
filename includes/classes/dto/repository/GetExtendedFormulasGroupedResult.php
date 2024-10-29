<?php

/**
 * Description of GetExtendedFormulasGroupedResult
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class GetExtendedFormulasGroupedResult
{
    public function __construct(
        private array $globalRules = [],
        private array $categoryRules = []
    ) {}

    /**
     * @return array|PriceFormula[]
     */
    public function getGlobalRules(): array
    {
        return $this->globalRules;
    }

    /**
     * @return array|PriceFormula[]
     */
    public function getCategoryRules(): array
    {
        return $this->categoryRules;
    }
}
