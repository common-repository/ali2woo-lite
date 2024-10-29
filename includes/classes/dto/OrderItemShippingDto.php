<?php

/**
 * Description of OrderItemShippingDto
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class OrderItemShippingDto
{
    public function __construct(
        private int $orderItemID, private string $shippingCode
    ) {}

    public function getOrderItemID(): int
    {
        return $this->orderItemID;
    }

    public function getShippingCode(): string
    {
        return $this->shippingCode;
    }
}
