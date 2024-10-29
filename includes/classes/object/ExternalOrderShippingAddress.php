<?php

/**
 * Description of ExternalOrderShippingAddress
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class ExternalOrderShippingAddress
{
    function __construct(
        private ?string $customerName = null,
        private ?int $phone = null,
        private ?string $phoneCode = null,
        private ?string $country = null,
        private ?string $countryCode = null,
        private ?string $state = null,
        private ?string $stateCode = null,
        private ?string $city = null,
        private ?string $address1 = null,
        private ?string $postcode = null,
        private ?string $address2 = null,
        private ?string $cpf = null,
        private ?string $rutNumber = null,
        private ?string $passportNumber = null,
        private ?string $passportExpiryDate = null,
        private ?string $passportIssuingAgency = null,
        private ?string $taxNumber = null,
        private ?string $foreignerPassportNumber = null,
        private ?bool $isForeigner = null,
        private ?string $vatTaxNumber = null,
        private ?string $taxCompany = null,
        private ?string $locationTreeAddressId = null,
        private ?string $locale = null,
    ) {

    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): self
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhoneCode(): ?string
    {
        return $this->phoneCode;
    }

    public function setPhoneCode(?string $phoneCode): self
    {
        $this->phoneCode = $phoneCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getStateCode(): ?string
    {
        return $this->stateCode;
    }

    public function setStateCode(?string $stateCode): self
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(?string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getRutNumber(): ?string
    {
        return $this->rutNumber;
    }

    public function setRutNumber(?string $rutNumber): self
    {
        $this->rutNumber = $rutNumber;

        return $this;
    }

    public function getPassportNumber(): ?string
    {
        return $this->passportNumber;
    }

    public function setPassportNumber(?string $passportNumber): self
    {
        $this->passportNumber = $passportNumber;

        return $this;
    }

    public function getPassportExpiryDate(): ?string
    {
        return $this->passportExpiryDate;
    }

    public function setPassportExpiryDate(?string $passportExpiryDate): self
    {
        $this->passportExpiryDate = $passportExpiryDate;

        return $this;
    }

    public function getPassportIssuingAgency(): ?string
    {
        return $this->passportIssuingAgency;
    }

    public function setPassportIssuingAgency(?string $passportIssuingAgency): self
    {
        $this->passportIssuingAgency = $passportIssuingAgency;

        return $this;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getForeignerPassportNumber(): ?string
    {
        return $this->foreignerPassportNumber;
    }

    public function setForeignerPassportNumber(?string $foreignerPassportNumber): self
    {
        $this->foreignerPassportNumber = $foreignerPassportNumber;

        return $this;
    }

    public function getIsForeigner(): ?bool
    {
        return $this->isForeigner;
    }

    public function setIsForeigner(?bool $isForeigner): self
    {
        $this->isForeigner = $isForeigner;

        return $this;
    }

    public function getVatTaxNumber(): ?string
    {
        return $this->vatTaxNumber;
    }

    public function setVatTaxNumber(?string $vatTaxNumber): self
    {
        $this->vatTaxNumber = $vatTaxNumber;

        return $this;
    }

    public function getTaxCompany(): ?string
    {
        return $this->taxCompany;
    }

    public function setTaxCompany(?string $taxCompany): self
    {
        $this->taxCompany = $taxCompany;

        return $this;
    }

    public function getLocationTreeAddressId(): ?string
    {
        return $this->locationTreeAddressId;
    }

    public function setLocationTreeAddressId(?string $locationTreeAddressId): self
    {
        $this->locationTreeAddressId = $locationTreeAddressId;

        return $this;
    }
    //@description 'en_US'
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

}
