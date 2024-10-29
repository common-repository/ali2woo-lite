<?php

/**
 * Description of ExternalOrder
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class ExternalOrder
{
    /**
     * @param int $orderId
     * @param ExternalOrderShippingAddress $ExternalOrderShippingAddress
     * @param array|ExternalOrderItem[] $ExternalOrderItems
     */
    function __construct(
        private int $orderId,
        private ExternalOrderShippingAddress $ExternalOrderShippingAddress,
        private array $ExternalOrderItems,
        private string $buyerName
    ) {

    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getShippingAddress(): ExternalOrderShippingAddress
    {
        return $this->ExternalOrderShippingAddress;
    }

    public function setShippingAddress(ExternalOrderShippingAddress $ExternalOrderShippingAddress): self
    {
        $this->ExternalOrderShippingAddress = $ExternalOrderShippingAddress;

        return $this;
    }

    /**
     * @return array|ExternalOrderItem[]
     */
    public function getItems(): array
    {
        return $this->ExternalOrderItems;
    }

    /**
     * @param array|ExternalOrderItem[] $ExternalOrderItems
     * @return $this
     */
    public function setItems(array $ExternalOrderItems): self
    {
        $this->ExternalOrderItems = $ExternalOrderItems;

        return $this;
    }

    public function getBuyerName(): string
    {
        return $this->buyerName;
    }

    public function setBuyerName(string $buyerName): self
    {
        $this->buyerName = $buyerName;

        return $this;
    }

}
