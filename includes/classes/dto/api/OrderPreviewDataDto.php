<?php

/**
 * Description of OrderPreviewDataDto
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class OrderPreviewDataDto
{
    /**
     * @param array|OrderPreviewItemDto[] $items
     */
    public function __construct(
        private array $items,
        private string $countryCode,
        private string $country,
        private string $province = '',
        private string $city = '',
    ) {}

    /**
     * @return array|OrderPreviewItemDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function getCity(): string
    {
        return $this->city;
    }

}
