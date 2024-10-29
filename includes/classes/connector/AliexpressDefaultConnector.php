<?php

/**
 * Description of AliexpressDefaultConnector
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
use Exception;

class AliexpressDefaultConnector extends AbstractConnector
{
    //todo: fix product description loading
    public function load_product($product_id, $params = [])
    {
        $params['product_id'] = $product_id;
        $request_url = RequestHelper::build_request('get_product', $params);
        $request = a2wl_remote_get($request_url);

        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else if (intval($request['response']['code']) != 200) {
            $result = ResultBuilder::buildError(
                $request['response']['code'] . " " . $request['response']['message']
            );
        } else {
            $result = json_decode($request['body'], true);
        }

        return $result;
    }

    public function loadCategory($categoryId): array
    {
        $params = [
            'category_id' => $categoryId,
        ];

        $request_url = RequestHelper::build_request('get_category', $params);
        $request = a2wl_remote_get($request_url);

        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else if (intval($request['response']['code']) != 200) {
            $result = ResultBuilder::buildError(
                $request['response']['code'] . " " . $request['response']['message']
            );
        } else {
            $result = json_decode($request['body'], true);
        }

        return $result;
    }

    public function load_products($filter, $page = 1, $per_page = 20, $params = [])
    {
        $request_url = RequestHelper::build_request(
            'get_products',
            array_merge(['page' => $page, 'per_page' => $per_page], $filter)
        );
        $request = a2wl_remote_get($request_url);

        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else if (intval($request['response']['code']) != 200) {
            $result = ResultBuilder::buildError($request['response']['code'] . " " . $request['response']['message']);
        } else {
            $result = json_decode($request['body'], true);
        }

        return $result;
    }

    public function load_store_products($filter, $page = 1, $per_page = 20, $params = [])
    {
        $request_url = RequestHelper::build_request(
            'get_store_products',
            array_merge(['page' => $page, 'per_page' => $per_page], $filter)
        );
        $request = a2wl_remote_get($request_url);

        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else if (intval($request['response']['code']) != 200) {
            $result = ResultBuilder::buildError($request['response']['code'] . " " . $request['response']['message']);
        } else {
            $result = json_decode($request['body'], true);
        }

        return $result;
    }
    
    public function load_reviews($product_id, $page, $page_size = 20, $params = [])
    {
        $request_url = RequestHelper::build_request('get_reviews',
           [
               'lang' => AliexpressLocalizator::getInstance()->language,
               'product_id' => $product_id,
               'page' => $page,
               'page_size' => $page_size
           ]
        );
        $request = a2wl_remote_get($request_url);

        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else {
            $result = json_decode($request['body'], true);
        }

        return $result;
    }
    
    public function check_affiliate($product_id): array
    {
        $request_url = RequestHelper::build_request('check_affiliate', ['product_id' => $product_id]);
        $request = a2wl_remote_get($request_url);
        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else {
            $result = json_decode($request['body'], true);
        }
        return $result;
    }

    public function load_shipping_info(
        $product_id, $quantity, $country_code, $country_code_from = 'CN',
        $min_price = '', $max_price = '', $province = '', $city = '', $extra_data = '', $sku_id = ''
    ) {
        $country_code = ProductShippingMeta::normalize_country($country_code);
        $params = [
            'product_id' => $product_id,
            'sku_id' => $sku_id,
            'quantity' => $quantity,
            'country_code' => $country_code,
            'extra_data' => $extra_data,
        ];
        if (!empty($country_code_from)) {
            $params['country_code_from'] = ProductShippingMeta::normalize_country($country_code_from);
        }

        $request_url = RequestHelper::build_request('get_shipping_info', $params);
        $request = a2wl_remote_get($request_url);
        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else {
            if (intval($request['response']['code']) == 200) {
                $result = json_decode($request['body'], true);
                if ($result['state'] != 'error') {
                    $result = ResultBuilder::buildOk([
                        'items' => $result['items'],
                        'from_cach' => $result['from_cach']
                    ]);
                } else {
                    $result = ResultBuilder::buildError($result['message']);
                }
            } else {
                $result = ResultBuilder::buildError(
                    $request['response']['code'] . ' - ' . $request['response']['message']
                );
            }
        }

        return $result;
    }

    public function placeOrder(
       ExternalOrder $ExternalOrder, string $currencyCode = 'USD'
    ): array {
        try {
            $this->get_access_token();

            $params = [
                'logistics_address' => $this->buildLogisticsAddress($ExternalOrder),
                'product_items' => $this->buildProductItems($ExternalOrder),
                'currency_code' => $currencyCode,
            ];

            $json = wp_json_encode($params);

            $args = [
                'headers' => ['Content-Type' => 'application/json'],
            ];

            $request_url = RequestHelper::build_request('place_order');
            $request = a2wl_remote_post($request_url, $json, $args);
            $result = $this->handleRequestResult($request);

            if ($result['state'] !== 'error') {
                $result = ResultBuilder::buildOk([
                    'orders' => $result['orders'],
                ]);
            }

        } catch (Exception $Exception) {
            $result = ResultBuilder::buildError($Exception->getMessage());
        }

        return $result;
    }

    public function load_order(string $order_id): array
    {
        try {
            $this->get_access_token();
        } catch (Exception $Exception) {
            return ResultBuilder::buildError($Exception->getMessage());
        }

        $params = [
            'order_id' => $order_id,
        ];

        $request_url = RequestHelper::build_request('load_order',$params);
        $request = a2wl_remote_get($request_url);
        $result = $this->handleRequestResult($request);

        if ($result['state'] !== 'error') {
            $result = ResultBuilder::buildOk([
                'order' => $result['order'],
            ]);
        }

        return $result;
    }

    public static function get_images_from_description($product)
    {
        $src_result = array();

        if (isset($product['desc_meta']) && isset($product['desc_meta']['images']) && is_array($product['desc_meta']['images'])) {
            foreach ($product['desc_meta']['images'] as $image_src) {
                $image_key = Utils::buildImageIdFromPath($image_src);
                $src_result[$image_key] = $image_src;
            }
        }

        return $src_result;
    }

    private function buildLogisticsAddress(ExternalOrder $ExternalOrder): array
    {
        $ShippingAddress = $ExternalOrder->getShippingAddress();
        $logisticsAddress = [
            'address' => $ShippingAddress->getAddress1(),
            'address2' => $ShippingAddress->getAddress2(),
            'city' => $ShippingAddress->getCity(),
            'contact_person' => $ShippingAddress->getCustomerName(),
            'country' => $ShippingAddress->getCountryCode(),
            'full_name' => $ShippingAddress->getCustomerName(),
            'mobile_no' => $ShippingAddress->getPhone(),
            'phone_country' => $ShippingAddress->getPhoneCode(),
            'province' => $ShippingAddress->getState(),
        ];

        $locale = $ShippingAddress->getLocale();
        if ($locale) {
            $logisticsAddress['locale'] = $locale;
        }

        $locationTreeAddressId = $ShippingAddress->getLocationTreeAddressId();
        if ($locationTreeAddressId) {
            $logisticsAddress['location_tree_address_id'] = $locationTreeAddressId;
        }

        $cpf = $ShippingAddress->getCpf();
        if ($cpf) {
            $logisticsAddress['cpf'] = $cpf;
        }

        $rutNo = $ShippingAddress->getRutNumber();
        if ($rutNo) {
            $logisticsAddress['rut_no'] = $rutNo;
        }

        $postcode = $ShippingAddress->getPostcode();
        if ($postcode) {
            $logisticsAddress['zip'] = $postcode;
        }

        $passportNo = $ShippingAddress->getPassportNumber();
        if ($passportNo) {
            $logisticsAddress['passport_no'] = $passportNo;
        }

        $passportNoDate = $ShippingAddress->getPassportExpiryDate();
        if ($passportNoDate) {
            $logisticsAddress['passport_no_date'] = $passportNoDate;
        }

        $passportOrganization = $ShippingAddress->getPassportIssuingAgency();
        if ($passportOrganization) {
            $logisticsAddress['passport_organization'] = $passportOrganization;
        }

        $taxNumber = $ShippingAddress->getTaxNumber();
        if ($taxNumber) {
            $logisticsAddress['tax_number'] = $taxNumber;
        }

        $foreignerPassportNo = $ShippingAddress->getForeignerPassportNumber();
        if ($foreignerPassportNo) {
            $logisticsAddress['foreigner_passport_no'] = $foreignerPassportNo;
        }

        $isForeigner = $ShippingAddress->getIsForeigner();
        if ($isForeigner) {
            $logisticsAddress['is_foreigner'] = $isForeigner;
        }

        $vatNo = $ShippingAddress->getVatTaxNumber();
        if ($vatNo) {
            $logisticsAddress['vat_no'] = $vatNo;
        }

        $taxCompany = $ShippingAddress->getTaxCompany();
        if ($taxCompany) {
            $logisticsAddress['tax_company'] = $taxCompany;
        }

        $logisticsAddress = apply_filters('a2wl_orders_logistics_address', $logisticsAddress, $ExternalOrder);

        //todo: add woocommerce order id to this log to identify it later
        return $this->fix_shipping_address($logisticsAddress);
    }

    private function buildProductItems(ExternalOrder $ExternalOrder): array
    {
        $productItems = [];
        foreach ($ExternalOrder->getItems() as $ExternalOrderItem) {
            $productItems[] = [
                'product_count' => $ExternalOrderItem->getProductCount(),
                'product_id' => $ExternalOrderItem->getExternalProductId(),
                'sku_attr' => $ExternalOrderItem->getExternalProductSku(),
                'logistics_service_name' => $ExternalOrderItem->getShippingService(),
                'order_memo' => $ExternalOrderItem->getComment(),
            ];
        }

        return $productItems;
    }

    private function fix_shipping_address($shipping_address)
    {
        if (a2wl_check_defined('A2WL_DEMO_MODE')){
            return $shipping_address;
        }

        $json = wp_json_encode($shipping_address);

        $args = [
            'headers' => array('Content-Type' => 'application/json'),
        ];

        $request_url = RequestHelper::build_request('fix_shipping_address');
        $request = a2wl_remote_post($request_url, $json, $args);

        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else {
            if (intval($request['response']['code']) == 200) {
                $result = json_decode($request['body'], true);
            } else {
                $result = ResultBuilder::buildError(
                    $request['response']['code'] . ' - ' . $request['response']['message']
                );
            }
        }

        if ($result['state'] !== 'error' && isset($result['shipping_address'])) {
            $shipping_address = $result['shipping_address'];

            //clear accents characters after fix shipping address
            $shipping_address['province'] = remove_accents($shipping_address['province']);
            $shipping_address['city'] = remove_accents($shipping_address['city']);
            $shipping_address['address'] = remove_accents($shipping_address['address']);
        }

        return $shipping_address;
    }

    /**
     * @throws Exception
     */
    private function get_access_token()
    {
        Utils::clear_system_error_messages();

        $token = AliexpressToken::getInstance()->defaultToken();

        if (!$token) {
            $msg = sprintf(
                esc_html__(
                    'AliExpress access token is not found. <a target="_blank" href="%s">Please check our instruction</a>.',
                    'ali2woo'
                ),
            'https://help.ali2woo.com/codex/how-to-get-access-token-from-aliexpress/'
            );

            Utils::show_system_error_message($msg);

            //todo: add here a check whether token has expired 

            throw new Exception($msg);
        }

        return $token['access_token'];
    }

    private function handleRequestResult($request): array
    {
        if (is_wp_error($request)) {
           return ResultBuilder::buildError($request->get_error_message());
        }

        if (intval($request['response']['code']) !== 200) {
            return ResultBuilder::buildError(
                $request['response']['code'] . ' - ' . $request['response']['message']
            );
        }

        $result = json_decode($request['body'], true);

        if ($result['state'] === 'error') {
            return ResultBuilder::buildError($result['message']);
        }

        return $result;
    }
}
