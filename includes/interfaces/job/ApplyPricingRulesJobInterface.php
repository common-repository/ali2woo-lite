<?php

/**
 * Description of ApplyPricingRulesJobInterface
 *
 * @author Ali2Woo Team
 *
 * @position: 2
 */

namespace AliNext_Lite;;

interface ApplyPricingRulesJobInterface extends BaseJobInterface
{
    public function pushToQueue(array $productIds, string $scope, string $type): self;
}
