<?php
/**
 * Description of ExternalOrderFactory
 *
 * @author Ali2Woo Team
 *
 */

namespace AliNext_Lite;;

use WC_Order;
use WC_Product_Variable;
use WC_Product_Variation;

class ExternalOrderFactory
{
    /**
     * @throws FactoryException
     */
    public function createOrderFromWooOrder(WC_Order $WC_Order, array $WC_OrderItems): ExternalOrder
    {
        $ShippingAddress = $this->createExternalOrderShippingAddress($WC_Order);

        $ExternalOrderItems = $this->createExternalOrderItems($WC_Order, $WC_OrderItems);

        $buyerName = $this->getBuyerName($WC_Order);

        $ExternalOrder = new ExternalOrder(
            $WC_Order->get_id(),
            $ShippingAddress,
            $ExternalOrderItems,
            $buyerName
        );

        if (!$ShippingAddress->getPhone()) {
            throw new FactoryException(
                esc_html__('Phone number is required', 'ali2woo')
            );
        } else if (!$ShippingAddress->getAddress1() && !$ShippingAddress->getAddress2()) {
            throw new FactoryException(
                esc_html__('Address is required', 'ali2woo')
            );
        } else if (!$ShippingAddress->getCustomerName()) {
            throw new FactoryException(
                esc_html__('Contact name is required', 'ali2woo')
            );
        } else if (!$ShippingAddress->getCountryCode()) {
            throw new FactoryException(
                esc_html__('Country is required', 'ali2woo')
            );
        } else if (!$ShippingAddress->getCity() && !$ShippingAddress->getStateCode()) {
            throw new FactoryException(
                esc_html__('City/State/Province is required', 'ali2woo')
            );
        } else if (!$ShippingAddress->getPostcode()) {
            throw new FactoryException(
                esc_html__('Zip/Postal code is required', 'ali2woo')
            );
        } else if ($ShippingAddress->getCountryCode() === 'BR' && !$ShippingAddress->getCpf()) {
            throw new FactoryException(
                esc_html__('CPF is mandatory in Brazil', 'ali2woo')
            );
        } else if ($ShippingAddress->getCountryCode() === 'CL' && !$ShippingAddress->getRutNumber()) {
            throw new FactoryException(
                esc_html__('RUT number is mandatory for Chilean customer', 'ali2woo')
            );
        }

        return $ExternalOrder;
    }

    private function createExternalOrderShippingAddress(WC_Order $WC_Order): ExternalOrderShippingAddress
    {
        $name = $this->getName($WC_Order);

        $shippingDestination = $this->getShippingDestination($WC_Order);
        $wooShippingCountryCode = $shippingDestination['woo_country'];
        $wooStateCode = $shippingDestination['state_code'];
        $wooCity = $shippingDestination['city'];
        $address1 = $shippingDestination['address1'];
        $address2 = $shippingDestination['address2'];
        $postCode = $shippingDestination['postcode'];

        $countryCode = ProductShippingMeta::normalize_country($wooShippingCountryCode);
        $country = Country::get_country($countryCode);
        $phoneInformation = $this->getPhoneInformation($WC_Order, $wooShippingCountryCode);
        $phone = Utils::sanitize_phone_number($phoneInformation['phone']);
        $phoneCode = $phoneInformation['code'];
        $states = Country::get_states($wooShippingCountryCode);
        $state = '';
        if ($wooStateCode) {
            $state = $states[$wooStateCode] ?? $wooStateCode;
        }

        $cpf = $this->getOrderCpf($WC_Order, $wooShippingCountryCode);
        $rutNumber = $this->getOrderRutNo($WC_Order, $wooShippingCountryCode);

        $passportNumber = $WC_Order->get_meta('_shipping_passport_no');
        $passportExpiryDate = $WC_Order->get_meta('_shipping_passport_no_date');
        $passportIssuingAgency = $WC_Order->get_meta('_shipping_passport_organization');
        $taxNumber = $WC_Order->get_meta('_shipping_tax_number');
        $foreignerPassportNumber = $WC_Order->get_meta('_shipping_foreigner_passport_no');

        $isForeigner = ($WC_Order->get_meta('_shipping_is_foreigner') === 'yes');
        $vatTaxNumber = $WC_Order->get_meta('_shipping_vat_no');
        $taxCompany = $WC_Order->get_meta('_shipping_tax_company');

        $locationTreeAddressId = null;
        $locale = null;

        if ($name) {
            $name = remove_accents($name);
        }

        if ($country) {
            $country = remove_accents($country);
        }

        if ($countryCode) {
            $countryCode = remove_accents($countryCode);
        }

        if ($state) {
            $state = remove_accents($state);
        }

        if ($wooStateCode) {
            $wooStateCode = remove_accents($wooStateCode);
        }

        if ($wooCity) {
            $wooCity = remove_accents($wooCity);
        }

        return (new ExternalOrderShippingAddress())
            ->setCustomerName($name ?? null)
            ->setPhone($phone ?? null)
            ->setPhoneCode($phoneCode ?? null)
            ->setCountry($country ?? null)
            ->setCountryCode($countryCode ?? null)
            ->setState($state ?? null)
            ->setStateCode($wooStateCode ?? null)
            ->setCity($wooCity ?? null)
            ->setAddress1($address1 ?? null)
            ->setPostcode($postCode ?? null)
            ->setAddress2($address2 ?? null)
            ->setCpf($cpf ?? null)
            ->setRutNumber($rutNumber ?? null)
            ->setPassportNumber($passportNumber ?? null)
            ->setPassportExpiryDate($passportExpiryDate ?? null)
            ->setPassportIssuingAgency($passportIssuingAgency ?? null)
            ->setTaxNumber($taxNumber ?? null)
            ->setForeignerPassportNumber($foreignerPassportNumber ?? null)
            ->setIsForeigner($isForeigner ?? null)
            ->setVatTaxNumber($vatTaxNumber ?? null)
            ->setTaxCompany($taxCompany ?? null)
            ->setLocationTreeAddressId($locationTreeAddressId ?? null)
            ->setLocale($locale ?? null);;
    }

    /**
     * @param WC_Order $WC_Order
     * @param array $orderItems
     * @return array|ExternalOrderItem[]
     * @throws FactoryException
     */
    private function createExternalOrderItems(WC_Order $WC_Order, array $orderItems): array
    {
        $order_note = get_setting('fulfillment_custom_note', '');
        $product_items = [];
        $errors = [];

        foreach ($orderItems as $orderItem) {
            if (get_class($orderItem) !== 'WC_Order_Item_Product') {
                continue;
            }

            $order_item_id = $orderItem->get_id();
            $quantity = $orderItem->get_quantity() + $WC_Order->get_qty_refunded_for_item($order_item_id);

            if ($quantity == 0) {
                continue;
            }

            $productId = $orderItem->get_product_id();

            $externalProductId = get_post_meta($productId, '_a2w_external_id', true);
            if (!$externalProductId) {
                $errors[] = [
                    'order_item_id' => $order_item_id,
                    'message' => esc_html__('AliExpress product not found', 'ali2woo')
                ];
                continue;
            }

            $productType = $orderItem->get_product()->get_type();

            if ($productType == 'variation') {
                $variationId = $orderItem->get_variation_id();
                $WC_Product_Variation = new WC_Product_Variation($variationId);
            } else {
                //if this is a simple product, try to take its first variable
                $WC_Product_Variable = new WC_Product_Variable($productId);
                $WC_Product_Variations = $WC_Product_Variable->get_available_variations();
                if (empty($WC_Product_Variations)) {
                    $text = 'Can`t match your order item with AliExpress variation. ' .
                        'Please synchronize appropriate Woocommerce product (#%d) with AliExpress and try again.';
                    $message = sprintf(
                        _x($text, 'error text', 'ali2woo'),
                        $productId);
                    throw new FactoryException($message);
                }
                $variationId = $WC_Product_Variations[0]['variation_id'];
                $WC_Product_Variation = new WC_Product_Variation($variationId);
            }

            $ExternalOrderItemAttributes = $this->getExternalOrderItemAttributes($WC_Product_Variation);

            $attachmentId = $WC_Product_Variation->get_image_id();
            $externalSkuId = get_post_meta($variationId, '_a2w_ali_sku_id', true);
            $imageUrl = get_post_meta($attachmentId, '_a2w_external_image_url', true);

            if ($productType == 'variation') {
                $sku_attr = get_post_meta($variationId, '_aliexpress_sku_props', true);
            } else {
                $sku_attr = get_post_meta($productId, '_aliexpress_sku_props', true);
            }

            $shipping_company = get_setting('fulfillment_prefship', '');
            $shipping_meta = $orderItem->get_meta(Shipping::get_order_item_shipping_meta_key());
            if ($shipping_meta) {
                $shipping_meta = json_decode($shipping_meta, true);
                $shipping_company = $shipping_meta['service_name'];
            }

            if (!$shipping_company) {
                $errors[] = [
                    'order_item_id' => $order_item_id,
                    'message' => esc_html__('Missing Shipping method', 'ali2woo')
                ];
                continue;
            }

            $product_items[] = (new ExternalOrderItem())
                ->setExternalProductId($externalProductId)
                ->setExternalProductSku($sku_attr)
                ->setExternalSkuId($externalSkuId)
                ->setProductCount($quantity)
                ->setShippingService($shipping_company)
                ->setComment($order_note)
                ->setImageUrl($imageUrl)
                ->setOrderItemId($order_item_id)
                ->setAttributes($ExternalOrderItemAttributes);
        }

        if (!empty($errors)) {
            $message =  esc_html__('Order data error.', 'ali2woo');

            foreach ($errors as $error) {
                if (!empty($error['message'])) {
                    $message .=	' ' . $error['message'];
                }
            }

            $extraData = [
                'error_code' => 'order_error',
                'errors' => $errors
            ];
            throw new FactoryException($message, $extraData);
        }

        return $product_items;
    }

    private function getName(WC_Order $WC_Order): string
    {
        $name = trim($WC_Order->get_formatted_shipping_full_name());
        if (!$name) {
            $name = trim($WC_Order->get_formatted_billing_full_name());
        }
        if (!$name) {
            $user = $WC_Order->get_user();
            if ($user) {
                if (!empty($user->display_name)) {
                    $name = $user->display_name;
                } elseif (!empty($user->user_nicename)) {
                    $name = $user->user_nicename;
                } elseif (!empty($user->user_login)) {
                    $name = $user->user_login;
                }
            }
        }

        return $name;
    }

    private function getPhoneInformation(WC_Order $WC_Order, string $wooShippingCountryCode): array
    {
        $country = ProductShippingMeta::normalize_country($wooShippingCountryCode);
        $phone_country = Utils::get_phone_country_code($country);

        $phone = $WC_Order->get_billing_phone();
        $default_phone_number = get_setting('fulfillment_phone_number', '');
        $default_phone_code = get_setting('fulfillment_phone_code', '');
        if ($phone && !$default_phone_number) {
            $phone = str_replace($phone_country, '', $phone);
            if (!$phone_country && function_exists('WC')) {
                $phone_country = WC()->countries->get_country_calling_code($wooShippingCountryCode);
            }
        } else {
            $phone = $default_phone_number;
            if ($default_phone_code) {
                $phone_country = $default_phone_code;
            }
        }

        return [
            'phone' => $phone,
            'code' => $phone_country,
        ];
    }

    private function getShippingDestination(WC_Order $WC_Order): array
    {
        $shipping_country = $WC_Order->get_shipping_country();
        $billing_country = $WC_Order->get_billing_country();
        if ($WC_Order->has_shipping_address()) {
            $state_code = $WC_Order->get_shipping_state();
            $city = $WC_Order->get_shipping_city();
            $address1 = $WC_Order->get_shipping_address_1();
            $address2 = $WC_Order->get_shipping_address_2();
            $post_code = $WC_Order->get_shipping_postcode();
        } else {
            $state_code = $WC_Order->get_billing_state();
            $city = $WC_Order->get_billing_city();
            $address1 = $WC_Order->get_billing_address_1();
            $address2 = $WC_Order->get_billing_address_2();
            $post_code = $WC_Order->get_billing_postcode();
        }

        $woo_country = $shipping_country ?: $billing_country;

        $billingNumber = $WC_Order->get_meta('_billing_number');
        $shippingNumber = $WC_Order->get_meta('_shipping_number');

        $streetNumber = $shippingNumber ? $shippingNumber : ($billingNumber ? $billingNumber : '');
        $streetNumber = $streetNumber ? preg_replace("/[^0-9]/", "", $streetNumber) : '';

        $shippingNeighborhood = $WC_Order->get_meta('_shipping_neighborhood');

        if ($address1) {
            $address1 = remove_accents($address1);
        }

        if ($address2) {
            $address2 = remove_accents($address2);
        }

        if ($streetNumber) {
            $address1 = $address1 . ', ' . remove_accents($streetNumber);
        }

        if ($shippingNeighborhood) {
            if ($address2) {
                $address2 = $address2 . ', ' . remove_accents($shippingNeighborhood);
            } else {
                $address1 = $address1 . ', ' . remove_accents($shippingNeighborhood);
            }
        }

        if (!$address1 && $address2) {
            $address1 = $address2;
            $address2 = '';
        }

        return [
            'state_code' => $state_code,
            'city' => $city,
            'address1' => $address1,
            'address2' => $address2,
            'postcode' => $post_code,
            'woo_country' => $woo_country,
        ];
    }

    private function getOrderCpf(WC_Order $WC_Order, string $wooShippingCountryCode): ?string
    {
        if ($wooShippingCountryCode !== 'BR') {
            return null;
        }

        $cpfMetaKey = get_setting('fulfillment_cpf_meta_key', '');

        if (!$cpfMetaKey) {
            return null;
        }

        $cpfMeta = $WC_Order->get_meta($cpfMetaKey);

        if (!$cpfMeta) {
            return null;
        }

        //todo: move this to address fixer
        return substr(Utils::sanitize_phone_number($cpfMeta), 0, 11);
    }

    private function getOrderRutNo(WC_Order $WC_Order, string $wooShippingCountryCode): ?string
    {
        if ($wooShippingCountryCode !== 'CL') {
            return null;
        }

        $rutMetaKey = get_setting('fulfillment_rut_meta_key', '');

        if (!$rutMetaKey) {
            return null;
        }

        $rutMeta = $WC_Order->get_meta($rutMetaKey);

        if (!$rutMeta) {
            return null;
        }

        //todo: move this to address fixer
        return substr($rutMeta, 0, 12);
    }

    private function getBuyerName(WC_Order $WC_Order): string
    {
        // copied from woocommerce/includes/admin/list-tables/class-wc-admin-list-table-orders.php
        $buyer = '';
        if ($WC_Order->get_billing_first_name() || $WC_Order->get_billing_last_name()) {
            $buyerName = sprintf(
            /* translators: %1$s first name %2$s last name */
                _x('%1$s %2$s', 'full name', 'woocommerce'),
                $WC_Order->get_billing_first_name(),
                $WC_Order->get_billing_last_name()
            );
            $buyer = trim($buyerName);
        } elseif ($WC_Order->get_billing_company()) {
            $buyer = trim($WC_Order->get_billing_company());
        } elseif ($WC_Order->get_customer_id()) {
            $user = get_user_by('id', $WC_Order->get_customer_id());
            $buyer = ucwords($user->display_name);
        }

        /**
         * Filter buyer name in list table orders.
         *
         * @since 3.7.0
         * @param string   $buyer Buyer name.
         * @param WC_Order $order Order data.
         */
        return apply_filters('woocommerce_admin_order_buyer_name', $buyer, $WC_Order);
    }

    /**
     * @param WC_Product_Variation $WC_Product_Variation
     * @return array|ExternalOrderItemAttribute[]
     */
    private function getExternalOrderItemAttributes(WC_Product_Variation $WC_Product_Variation): array
    {
        $variationAttributes = $WC_Product_Variation->get_variation_attributes(false);
        $ExternalOrderItemAttributes = [];

        foreach ($variationAttributes as $attributeName => $attribute) {
            $name = str_replace( 'pa_', '', $attributeName );
            $ExternalOrderItemAttributes[] = (new ExternalOrderItemAttribute())
                ->setName($name)
                ->setValue($attribute);
        }

        return $ExternalOrderItemAttributes;
    }

}
