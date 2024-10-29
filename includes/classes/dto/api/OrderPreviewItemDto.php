<?php

/**
 * Description of OrderPreviewItemDto
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class OrderPreviewItemDto
{
    public function __construct(
        private string $productId, private string $skuId,
        private string $imageUrl, private int $quantity = 1,
        private array $attributes = []
    ) {}

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getSkuId(): string
    {
        return $this->skuId;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

}
