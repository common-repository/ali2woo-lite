<?php

/**
 * Description of OrderFulfillmentService
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

use Throwable;
use WC_Order;
use WC_Order_Item_Product;

class OrderFulfillmentService
{

    protected Aliexpress $AliexpressModel;
    protected ExternalOrderFactory $ExternalOrderFactory;

    public function __construct(
        Aliexpress $AliexpressModel,
        ExternalOrderFactory $ExternalOrderFactory,
    ) {
        $this->AliexpressModel = $AliexpressModel;
        $this->ExternalOrderFactory = $ExternalOrderFactory;
    }

    public function placeOrder(WC_Order $WC_Order, array $OrderItems): array
    {
        a2wl_init_error_handler();
        try {
            $ExternalOrder = $this->ExternalOrderFactory
                ->createOrderFromWooOrder($WC_Order, $OrderItems);
            $currencyCode = get_setting('local_currency', ' USD');
            $apiResult = $this->AliexpressModel->placeOrder($ExternalOrder, $currencyCode);

            if ($apiResult['state'] !== 'ok') {
                return $apiResult;
            }

            $aliexpressOrders = $apiResult['orders']['list'];

            foreach ($aliexpressOrders as $aliexpressOrder) {
                foreach ($aliexpressOrder['child_order_list']['ae_child_order_info'] as $ae_product_info) {
                    foreach ($OrderItems as $order_item) {
                        $a2wl_order_item = new WooCommerceOrderItem($order_item);
                        if ($a2wl_order_item->get_external_product_id() == $ae_product_info['product_id']) {
                            $a2wl_order_item->update_external_order($aliexpressOrder['order_id'], true);
                        }
                    }
                }
            }

            $result = ResultBuilder::buildOk();

            $placed_order_status = get_setting('placed_order_status');
            if ($placed_order_status) {
                $WC_Order->update_status($placed_order_status);
            }
            restore_error_handler();
        }
        catch (FactoryException $FactoryException) {
            $extraData = $FactoryException->getExtraData();

            $result = ResultBuilder::buildError(
                $FactoryException->getMessage(),
                $extraData ?? false
            );
        }
        catch (Throwable $Exception) {
            a2wl_print_throwable($Exception);
            $result = ResultBuilder::buildError($Exception->getMessage());
        }

        return $result;
    }

    /**
     * @param WC_Order $WC_Order
     * @param bool $isWpml
     * @return array|null
     */
    public function getFulfillmentOrderServiceData(WC_Order $WC_Order, bool $isWpml = false): ?array
    {
            $WC_OrderItems = $WC_Order->get_items();

            a2wl_init_error_handler();
            try {
                $ExternalOrder = $this->ExternalOrderFactory
                    ->createOrderFromWooOrder($WC_Order, $WC_OrderItems);

                $OrderPreviewResultDto = $this->AliexpressModel->getOrderPreview($ExternalOrder);
                restore_error_handler();
            } catch (Throwable $Exception) {
                a2wl_print_throwable($Exception);

                return null;
            }

            $shipping_address = $WC_Order->get_address('shipping');
            if (empty($shipping_address['country'])) {
                $shipping_address = $WC_Order->get_address();
            }
            $formatted_address = WC()->countries->get_formatted_address($shipping_address, ', ');

            $buyerName = $ExternalOrder->getBuyerName();

            $order_data = [
                'order_id' => $WC_Order->get_id(),
                'order_number' => $WC_Order->get_order_number(),
                'order' => $WC_Order,
                'buyer' => $buyerName,
                'currency' => $WC_Order->get_currency(),
                'shipping_to_country' => $ExternalOrder->getShippingAddress()->getCountryCode(),
                'shipping_address' => $shipping_address,
                'formatted_address' => $formatted_address,
                'total_cost' => 0,
                'items' => [],
            ];

            $deliveryTime = $OrderPreviewResultDto->getShippingTime();

            $testK = 0;
            foreach ($ExternalOrder->getItems() as $ExternalOrderItem) {

                $externalProductPrice = 0;

                $testJ = 0;
                foreach ($OrderPreviewResultDto->getItems() as $OrderPreviewResultItemDto)
                {
                   /* $searchItem = ($OrderPreviewResultItemDto->getExternalSkuId() ===
                        $ExternalOrderItem->getExternalSkuId()) && ($OrderPreviewResultItemDto->getExternalProductId() ===
                            $ExternalOrderItem->getExternalProductId());*/

                    $searchItem = ($testK === $testJ);

                    if ($searchItem)  {
                        $externalProductPrice = $OrderPreviewResultItemDto->getPrice();
                        $itemsCount = count($OrderPreviewResultDto->getItems());
                        $shippingCost = $OrderPreviewResultDto->getTotalShippingPrice() / $itemsCount;
                        $current_shipping_company = $OrderPreviewResultDto->getShippingName();
                    }

                    $testJ++;
                }

                $WC_Order_Item_Product = new WC_Order_Item_Product($ExternalOrderItem->getOrderItemId());
                $WC_Product = $WC_Order_Item_Product->get_product();

                $wpmlProductData = $this->getWpmlProductData($WC_Order_Item_Product->get_product_id(), $isWpml);

                $item_original_url = $wpmlProductData['item_original_url'];

                $attributes = $this->getFormattedOrderItemAttributes($ExternalOrderItem);


              /*  $shipping_info = Utils::get_product_shipping_info(
                    $WC_Product,
                    $ExternalOrderItem->getProductCount(),
                    $ExternalOrder->getShippingAddress()->getCountryCode(),
                    false
                );*/

                $shipping_info = [
                    'items' => []
                ];

                $totalCost = $shippingCost + $externalProductPrice;

                $order_data['items'][] = [
                    'order_item_id' => $WC_Order_Item_Product->get_id(),
                    'product_id' => $WC_Order_Item_Product->get_product_id(),
                    'image' => $WC_Product->get_image(),
                    'name' => $WC_Order_Item_Product->get_name(),
                    'url' => $item_original_url,
                    'sku' => $WC_Product->get_sku(),
                    'attributes' => implode(' / ', $attributes),
                    'cost' => $externalProductPrice,
                    'quantity' => $WC_Order_Item_Product->get_quantity(),
                    'shipping_items' => $shipping_info['items'],
                    'current_shipping' => $current_shipping_company,
                    'delivery_time' => $deliveryTime,
                    'shipping_cost' => $shippingCost,
                    'total_cost' => $totalCost,
                ];

                $order_data['total_cost'] += $totalCost;

                $testK++;
            }

        return $order_data;
    }

    /**
     * @param array|WC_Order[] $orders
     * @return array
     */
    public function getFulfillmentOrdersData(array $orders, bool $is_wpml = false): array
    {
        global $sitepress;
        $orders_data = [];

        foreach ($orders as $order) {
            // copied from woocommerce/includes/admin/list-tables/class-wc-admin-list-table-orders.php
            $buyer = '';
            if ($order->get_billing_first_name() || $order->get_billing_last_name()) {
                $buyerName = sprintf(
                    /* translators: %1$s first name %2$s last name */
                    _x('%1$s %2$s', 'full name', 'woocommerce'),
                    $order->get_billing_first_name(),
                    $order->get_billing_last_name()
                );
                $buyer = trim($buyerName);
            } elseif ($order->get_billing_company()) {
                $buyer = trim($order->get_billing_company());
            } elseif ($order->get_customer_id()) {
                $user = get_user_by('id', $order->get_customer_id());
                $buyer = ucwords($user->display_name);
            }

            /**
             * Filter buyer name in list table orders.
             *
             * @since 3.7.0
             * @param string   $buyer Buyer name.
             * @param WC_Order $order Order data.
             */
            $order_data['buyer'] = apply_filters('woocommerce_admin_order_buyer_name', $buyer, $order);
            $shipping_address = $order->get_address('shipping');
            if (empty($shipping_address['country'])) {
                $shipping_address = $order->get_address();
            }
            $formatted_address = WC()->countries->get_formatted_address($shipping_address, ', ');
            $shiping_to_country = ProductShippingMeta::normalize_country($shipping_address['country']);

            $order_data = [
                'order_id' => $order->get_id(),
                'order_number' => $order->get_order_number(),
                'order' => $order,
                'buyer' => $buyer,
                'currency' => $order->get_currency(),
                'shiping_to_country' => $shiping_to_country,
                'shipping_address' => $shipping_address,
                'formatted_address' => $formatted_address,
                'total_cost' => 0,
                'items' => [],
            ];

            foreach ($order->get_items() as $item) {
                /**
                 * todo: here can be a case when product is already deleted in Woocommerce
                 * in this case the order item can be retrieved.
                 */
                $a2wl_order_item = new WooCommerceOrderItem($item);

                if(!$a2wl_order_item->get_external_product_id()){
                    continue;
                }

                $product = $item->get_product();

                $image = $product->get_image();

                $product_id = $item->get_product_id();
                $variation_id = $item->get_variation_id();

                $shipping_meta = new ProductShippingMeta($product_id);

                $shipping_info = Utils::get_product_shipping_info(
                    $product, $item->get_quantity(), $shiping_to_country, false
                );

                $current_shipping_company = '';
                $current_delivery_time = '-';
                $current_shipping_cost = '';
                $shipping_meta_data = $item->get_meta(Shipping::get_order_item_shipping_meta_key());

                if ($shipping_meta_data) {
                    $shipping_meta_data = json_decode($shipping_meta_data, true);
                    $current_shipping_company = $shipping_meta_data['service_name'];
                    $current_delivery_time = $shipping_meta_data['delivery_time'];
                    $current_shipping_cost = $shipping_meta_data['shipping_cost'];
                }
                $current_shipping_company = $current_shipping_company ?: $shipping_info['default_method'];

                $wpml_product_id = $wpml_variation_id = '';
                if ($is_wpml) {
                    $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
                    if ($wpml_object_id != $product_id) {
                        $wpml_product = wc_get_product($wpml_object_id);
                        if ($wpml_product) {
                            $wpml_product_id = $wpml_object_id;
                        }
                    }
                    if ($product_id) {
                        $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
                        if ($wpml_object_id != $product_id) {
                            $wpml_variation = wc_get_product($wpml_object_id);
                            if ($wpml_variation) {
                                $wpml_variation_id = $wpml_object_id;
                            }
                        }
                    }
                }

                if ($wpml_product_id) {
                    $aliexpress_product_id = get_post_meta($wpml_product_id, '_a2w_external_id', true);
                    $item_original_url = get_post_meta($wpml_product_id, '_a2w_original_product_url', true);
                } else {
                    $aliexpress_product_id = get_post_meta($product_id, '_a2w_external_id', true);
                    $item_original_url = get_post_meta($product_id, '_a2w_original_product_url', true);
                }

                $aliexpress_price = $this->get_aliexpress_price($item, $is_wpml);

                $attributes = [];
                if ($meta_data = $item->get_formatted_meta_data('')) {
                    foreach ($meta_data as $meta_id => $meta) {
                        $shouldSkip = !str_starts_with($meta->key, "pa_");
                        if ($shouldSkip) {
                            continue;
                        }
                        $attributes[] = force_balance_tags($meta->display_value);
                    }
                }

                $total_cost = $aliexpress_price * $item->get_quantity() + ($current_shipping_cost ? $current_shipping_cost : 0);

                $order_data['items'][] = [
                    'order_item_id' => $item->get_id(),
                    'product_id' => $item->get_product_id(),
                    'image' => $image,
                    'name' => $item->get_name(),
                    'url' => $item_original_url,
                    'sku' => $product->get_sku(),
                    'attributes' => implode(' / ', $attributes),
                    'cost' => $aliexpress_price,
                    'quantity' => $item->get_quantity(),
                    'shipping_items' => $shipping_info['items'],
                    'current_shipping' => $current_shipping_company,
                    'current_delivery_time' => $current_delivery_time,
                    'current_shipping_cost' => $current_shipping_cost,
                    'total_cost' => $total_cost,
                ];

                $order_data['total_cost'] += $total_cost;
            }

            if ($order_data['items']) {
                $orders_data[] = $order_data;
            }
        }

        foreach ($orders_data as &$order_data) {
            $urls = array_column($order_data['items'], 'url');

            $order_data['sign_urls'] = $this->get_sign_urls($urls);
        }

        return $orders_data;
    }

    /**
     * @param WC_Order $order
     * @param array|OrderItemShippingDto[] $order_items
     * @param string $shiping_to_country
     * @param bool $is_wpml
     * @return UpdateFulfillmentShippingResult
     */
    public function updateFulfillmentShipping(
        WC_Order $order, array $order_items, string $shiping_to_country, bool $is_wpml = false
    ): UpdateFulfillmentShippingResult {
        $result_items = [];
        $total_order_price = 0;

        foreach ($order->get_items() as $item) {
            $OrderItemShippingDto = $this->getOrderItemById($item->get_id(), $order_items);
            if ($OrderItemShippingDto) {
                $aliexpress_price = $this->get_aliexpress_price($item, $is_wpml);

                $product = $item->get_product();
                $product_id = $item->get_product_id();

                $shipping_meta = new ProductShippingMeta($product_id);
                $shipping_info = Utils::get_product_shipping_info(
                    $product, $item->get_quantity(), $shiping_to_country, false
                );

                $shipping_meta_data = $item->get_meta(Shipping::get_order_item_shipping_meta_key());
                $shipping_meta_data = $shipping_meta_data ?
                    json_decode($shipping_meta_data, true) :
                    [
                        'company' => '', 'service_name' => '', 'delivery_time' => '', 'shipping_cost' => '',
                        'quantity' => $item->get_quantity(), 'cost_added' => true
                    ];
                $current_shipping_cost = 0;

                foreach ($shipping_info['items'] as $si) {
                    if ($si['serviceName'] == $OrderItemShippingDto->getShippingCode()) {
                        $current_shipping_cost = $si['freightAmount']['value'];

                        $shipping_meta_data['company'] = $si['company'];
                        $shipping_meta_data['service_name'] = $si['serviceName'];
                        $shipping_meta_data['shipping_cost'] = $si['freightAmount']['value'];
                        $shipping_meta_data['delivery_time'] = $si['time'];

                        $result_items[] = [
                            'order_item_id' => $item->get_id(),
                            'shiping_time' => $si['time'] . ' days',
                            'shiping_price' => wc_price(
                                $si['freightAmount']['value'], ['currency' => $order->get_currency()]
                            ),
                            'total_item_price' => wc_price(
                                $aliexpress_price * $item->get_quantity() + $si['freightAmount']['value'],
                                ['currency' => $order->get_currency()]
                            ),
                        ];
                    }
                }

                $item->update_meta_data(
                    Shipping::get_order_item_shipping_meta_key(),
                    wp_json_encode($shipping_meta_data)
                );
                $item->save_meta_data();

                $total_order_price += $aliexpress_price * $item->get_quantity() + $current_shipping_cost;
            }
        }

        return new UpdateFulfillmentShippingResult($total_order_price, $result_items);
    }

    private function get_aliexpress_price($order_item, $is_wpml = false)
    {
        $product_id = $order_item->get_product_id();
        $variation_id = $order_item->get_variation_id();

        $wpml_product_id = $wpml_variation_id = '';
        if ($is_wpml) {
            global $sitepress;
            $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
            if ($wpml_object_id != $product_id) {
                $wpml_product = wc_get_product($wpml_object_id);
                if ($wpml_product) {
                    $wpml_product_id = $wpml_object_id;
                }
            }
            if ($product_id) {
                $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
                if ($wpml_object_id != $product_id) {
                    $wpml_variation = wc_get_product($wpml_object_id);
                    if ($wpml_variation) {
                        $wpml_variation_id = $wpml_object_id;
                    }
                }
            }
        }
        if ($wpml_variation_id) {
            $aliexpress_price = get_post_meta($wpml_product_id, '_aliexpress_price', true);
        } else if ($variation_id) {
            $aliexpress_price = get_post_meta($variation_id, '_aliexpress_price', true);
        } else if ($wpml_product_id) {
            $aliexpress_price = get_post_meta($wpml_product_id, '_aliexpress_price', true);
        } else {
            $aliexpress_price = get_post_meta($product_id, '_aliexpress_price', true);
        }

        return $aliexpress_price;
    }

    private function get_sign_urls($urls): array
    {
        if (a2wl_check_defined('A2WL_DEMO_MODE')){
            return [];
        }

        $payload = [
            "urls" => $urls,
        ];

        $args = [];

        $request_url = RequestHelper::build_request('sign_urls');
        $request = a2wl_remote_post($request_url, $payload, $args);

        if (is_wp_error($request)) {
            $result = ResultBuilder::buildError($request->get_error_message());
        } else {
            if (intval($request['response']['code']) == 200) {
                $result = json_decode($request['body'], true);
            } else {
                $result = ResultBuilder::buildError($request['response']['code'] . ' - ' . $request['response']['message']);
            }
        }

        if ($result['state'] == 'error') {
            $result = [];
        } else {
            $result = $result['urls'];
        }

        return $result;
    }

    /**
     * @param int $orderItemId
     * @param array|OrderItemShippingDto[] $order_items
     * @return null|OrderItemShippingDto
     */
    private function getOrderItemById(int $orderItemId, array $order_items): ?OrderItemShippingDto
    {
        /**
         * @var OrderItemShippingDto $orderItemShipping
         */
        foreach ($order_items as $orderItemShipping) {
            if ($orderItemShipping->getOrderItemID() == $orderItemId) {
                return $orderItemShipping;
            }
        }

        return null;
    }

    private function getWpmlProductData($product_id, bool $isWpml = false): array
    {
        global $sitepress;

        $result = [
            'wpml_product_id' => '',
            'wpml_variation_id' => '',
            'aliexpress_product_id' => get_post_meta($product_id, '_a2w_external_id', true),
            'item_original_url' => get_post_meta($product_id, '_a2w_original_product_url', true)
        ];

        if ($isWpml) {
            $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
            if ($wpml_object_id != $product_id) {
                $wpml_product = wc_get_product($wpml_object_id);
                if ($wpml_product) {
                    $result['wpml_product_id'] = $wpml_object_id;
                }
            }
            if ($product_id) {
                $wpml_object_id = apply_filters('wpml_object_id', $product_id, 'product', false, $sitepress->get_default_language());
                if ($wpml_object_id != $product_id) {
                    $wpml_variation = wc_get_product($wpml_object_id);
                    if ($wpml_variation) {
                        $result['wpml_variation_id'] = $wpml_object_id;
                    }
                }
            }
            $wpml_product_id = $result['wpml_product_id'];

            if ($wpml_product_id) {
                $result['aliexpress_product_id'] = get_post_meta($wpml_product_id, '_a2w_external_id', true);
                $result['item_original_url'] = get_post_meta($wpml_product_id, '_a2w_original_product_url', true);
            }
        }

        return $result;
    }

    private function getProductAttributes(WC_Order_Item_Product $WC_Order_Item_Product): array
    {
        $attributes = [];
        if ($meta_data = $WC_Order_Item_Product->get_formatted_meta_data('')) {
            foreach ($meta_data as $meta_id => $meta) {
                $shouldSkip = !str_starts_with($meta->key, "pa_");
                if ($shouldSkip) {
                    continue;
                }
                $attributes[] = force_balance_tags($meta->display_value);
            }
        }

        return $attributes;
    }

    private function getFormattedOrderItemAttributes(ExternalOrderItem $ExternalOrderItem) : array
    {
        $attributes = [];
        $Attributes = $ExternalOrderItem->getAttributes();
        foreach($Attributes as $ExternalOrderItemAttribute) {
            $attributes[] = sprintf('%s: "%s"',
                $ExternalOrderItemAttribute->getName(),
                $ExternalOrderItemAttribute->getValue()
            );
        }

        return $attributes;
    }

}
