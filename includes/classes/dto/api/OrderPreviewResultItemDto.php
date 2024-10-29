<?php

/**
 * Description of OrderPreviewResultItemDto
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class OrderPreviewResultItemDto
{
    public function __construct(
        private float $price,
        private string $externalProductId,
        private string $externalSkuId,
        private int $quantity,
    ) {

    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getExternalProductId(): string
    {
        return $this->externalProductId;
    }

    public function getExternalSkuId(): string
    {
        return $this->externalSkuId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
