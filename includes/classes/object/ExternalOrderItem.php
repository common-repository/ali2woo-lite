<?php

/**
 * Description of ExternalOrderItem
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class ExternalOrderItem
{

    /**
     * @param string|null $externalProductId
     * @param int $productCount
     * @param string|null $shippingService
     * @param string|null $externalProductSku
     * @param string|null $comment
     * @param string|null $imageUrl
     * @param string|null $externalSkuId
     * @param int|null $orderItemId
     * @param array|ExternalOrderItemAttribute[] $attributes
     */
    function __construct(
        private ?string $externalProductId = null,
        private int $productCount = 1,
        private ?string $shippingService = null,
        private ?string $externalProductSku= null,
        private ?string $comment = null,
        private ?string $imageUrl = null,
        private ?string $externalSkuId = null,
        private ?int $orderItemId = null,
        private array $attributes = []
    ) {

    }

    public function getExternalProductId(): ?string
    {
        return $this->externalProductId;
    }

    public function setExternalProductId(?string $externalProductId): self
    {
        $this->externalProductId = $externalProductId;

        return $this;
    }

    public function getProductCount(): int
    {
        return $this->productCount;
    }

    public function setProductCount(int $productCount): self
    {
        $this->productCount = $productCount;

        return $this;
    }

    public function getExternalProductSku(): ?string
    {
        return $this->externalProductSku;
    }

    public function setExternalProductSku(?string $externalProductSku): self
    {
        $this->externalProductSku = $externalProductSku;

        return $this;
    }

    public function getShippingService(): ?string
    {
        return $this->shippingService;
    }

    public function setShippingService(?string $shippingService): self
    {
        $this->shippingService = $shippingService;

        return $this;
    }

    public  function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getExternalSkuId(): ?string
    {
        return $this->externalSkuId;
    }

    public function setExternalSkuId(?string $externalSkuId): self
    {
        $this->externalSkuId = $externalSkuId;

        return $this;
    }

    public function getOrderItemId(): ?int
    {
        return $this->orderItemId;
    }

    public function setOrderItemId(?int $orderItemId): self
    {
        $this->orderItemId = $orderItemId;

        return $this;
    }

    /**
     * @return array|ExternalOrderItemAttribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array|ExternalOrderItemAttribute $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

}
