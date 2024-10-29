<?php

/**
 * Description of UpdateFulfillmentShippingResult
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class UpdateFulfillmentShippingResult
{
    public function __construct(
        private float $totalOrderPrice, private array $resultItems
    ) {}

    public function getTotalOrderPrice(): float
    {
        return $this->totalOrderPrice;
    }

    public function getResultItems():array
    {
        return $this->resultItems;
    }
}
