<?php

/**
 * Description of OrderPreviewResultDto
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class OrderPreviewResultDto
{

    /**
     * @param int $externalOrderId
     * @param int $status
     * @param float $subtotalPrice
     * @param float $totalPrice
     * @param float $totalShippingPrice
     * @param string $shippingName
     * @param string $shippingTime
     * @param array|OrderPreviewResultItemDto[] $items
     */
    public function __construct(
        private int $externalOrderId,
        private int $status,
        private float $subtotalPrice,
        private float $totalPrice,
        private float $totalShippingPrice,
        private string $shippingName,
        private string $shippingTime,
        private array $items,
    ) {}

    public function getExternalOrderId(): int
    {
        return $this->externalOrderId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getSubtotalPrice(): float
    {
        return $this->subtotalPrice;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getTotalShippingPrice(): float
    {
        return $this->totalShippingPrice;
    }

    public function getShippingName(): string
    {
        return $this->shippingName;
    }

    public function getShippingTime(): string
    {
        return $this->shippingTime;
    }

    /**
     * @return array|OrderPreviewResultItemDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
