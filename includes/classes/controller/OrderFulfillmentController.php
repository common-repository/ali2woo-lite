<?php

/**
 * Description of OrderFulfillmentController
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_admin_init
 *
 * @ajax: true
 */
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
namespace AliNext_Lite;;

use WC_Order;
use Automattic\WooCommerce\Utilities\OrderUtil;

class OrderFulfillmentController extends AbstractController
{
    protected static array $shipping_fields = [];
    protected static array $additional_shipping_fields = [];

    public function __construct()
    {
        parent::__construct(A2WL()->plugin_path() . '/view/');
        add_action('admin_init', [$this, 'admin_init']);

        add_filter('a2wl_wcol_bulk_actions_init', array($this, 'bulk_actions'));
        //todo: rudiment method for chrome extension fulfillment
        add_action('wp_ajax_a2wl_get_aliexpress_order_data', [$this, 'ajax_get_aliexpress_order_data']);

        add_action('wp_ajax_a2wl_load_fulfillment_model', [$this, 'ajax_load_fulfillment_model_html']);
        add_action('wp_ajax_a2wl_load_fulfillment_orders', [$this, 'ajax_load_fulfillment_orders_html']);
        add_action('wp_ajax_a2wl_load_fulfillment_orders_service', [$this, 'ajax_load_fulfillment_orders_service_html']);
        add_action('wp_ajax_a2wl_save_order_shipping_info', [$this, 'ajax_save_order_shipping_info']);

        add_action('wp_ajax_a2wl_fulfillment_place_order', [$this, 'ajax_load_fulfillment_place_order']);

        add_action('wp_ajax_a2wl_update_fulfillment_shipping', [$this, 'ajax_update_fulfillment_shipping']);

        add_action('wp_ajax_a2wl_sync_order_info', [$this, 'ajax_sync_order_info']);

        add_filter('a2wl_fill_additional_shipping_fields', array($this, 'fill_additional_shipping_fields'), 10, 3);
        add_action('a2wl_update_custom_shipping_field', [$this, 'update_custom_shipping_field'], 10, 3);

        add_action( 'init', array($this, 'init') );
    }

    public function admin_init(): void
    {
        if (OrderUtil::custom_orders_table_usage_is_enabled()) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $currentPage = $_REQUEST['page'] ?? '';
            if ($currentPage === 'wc-orders') {
                add_action('admin_enqueue_scripts', [$this, 'assets']);
                add_action('admin_footer', [$this, 'place_orders_bulk_popup']);
                add_action('admin_footer', [$this, 'place_shipping_modal']);
            }
        }
        else {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $post_type = $_GET['post_type'] ?? ($_REQUEST['post_type'] ?? "");
            if (is_admin() && $post_type == "shop_order") {
                add_action('admin_enqueue_scripts', [$this, 'assets']);
                add_action('admin_footer', [$this, 'place_orders_bulk_popup']);
                add_action('admin_footer', [$this, 'place_shipping_modal']);
            }
        }
    }

    public function init() {
        self::$shipping_fields = apply_filters(
            'woocommerce_admin_shipping_fields',
            [
                'first_name' => array(
                    'label' => esc_html__( 'First name', 'woocommerce' ),
                    'show'  => false,
                ),
                'last_name'  => array(
                    'label' => esc_html__( 'Last name', 'woocommerce' ),
                    'show'  => false,
                ),
                'company'    => array(
                    'label' => esc_html__( 'Company', 'woocommerce' ),
                    'show'  => false,
                ),
                'address_1'  => array(
                    'label' => esc_html__( 'Address line 1', 'woocommerce' ),
                    'show'  => false,
                ),
                'address_2'  => array(
                    'label' => esc_html__( 'Address line 2', 'woocommerce' ),
                    'show'  => false,
                ),
                'city'       => array(
                    'label' => esc_html__( 'City', 'woocommerce' ),
                    'show'  => false,
                ),
                'postcode'   => array(
                    'label' => esc_html__( 'Postcode / ZIP', 'woocommerce' ),
                    'show'  => false,
                ),
                'country'    => array(
                    'label'   => esc_html__( 'Country / Region', 'woocommerce' ),
                    'show'    => false,
                    'type'    => 'select',
                    'class'   => 'js_field-country select short',
                    'options' => array( '' => esc_html__( 'Select a country / region&hellip;', 'woocommerce' ) ) + WC()->countries->get_shipping_countries(),
                ),
                'state'      => array(
                    'label' => esc_html__( 'State / County', 'woocommerce' ),
                    'class' => 'js_field-state select short',
                    'show'  => false,
                ),
                'phone'      => array(
                    'label' => esc_html__( 'Phone', 'woocommerce' ),
                ),
            ],
            false,
            false
        );

        self::$additional_shipping_fields = apply_filters(
            'woocommerce_admin_additional_shipping_fields',
            array(
                'passport_no' => array(
                    'label' => esc_html__( 'Passport number', 'ali2woo' ),
                    'show'  => false,
                ),
                'passport_no_date'  => array(
                    'label' => esc_html__( 'Passport date', 'ali2woo' ),
                    'show'  => false,
                ),
                'passport_organization'    => array(
                    'label' => esc_html__( 'Passport issuing agency', 'ali2woo' ),
                    'show'  => false,
                ),
                'tax_number'  => array(
                    'label' => esc_html__( 'Tax number', 'ali2woo' ),
                    'show'  => false,
                ),
                'foreigner_passport_no'  => array(
                    'label' => esc_html__( 'Foreign tax number (For Koreans, foreigners must fill in the registration number or passport number)', 'ali2woo' ),
                    'show'  => false,
                ),
                'is_foreigner'       => array(
                    'type'  => 'checkbox',
                    'label' => esc_html__( 'Is foreigner?', 'ali2woo' ),
                    'show'  => false,
                ),
                'vat_no'   => array(
                    'label' => esc_html__( 'VAT number', 'ali2woo' ),
                    'show'  => false,
                ),
                'tax_company'   => array(
                    'label' => esc_html__( 'Company Name', 'ali2woo' ),
                    'show'  => false,
                ),
            )
        );
    }

    public function assets()
    {
        wp_enqueue_style('a2wl-admin-style', A2WL()->plugin_url() . '/assets/css/admin_style.css', array(), A2WL()->version);
        // wp_enqueue_style('a2wl-bootstrap-style', A2WL()->plugin_url() . '/assets/js/bootstrap/css/bootstrap.min.css', array(), A2WL()->version);

        wp_enqueue_script('a2wl-admin-script',
            A2WL()->plugin_url() . '/assets/js/admin_script.js',
            array('jquery'),
            A2WL()->version
        );
        AbstractAdminPage::localizeAdminScript();
        wp_enqueue_script('a2wl-ali-orderfulfill-js', A2WL()->plugin_url() . '/assets/js/orderfulfill.js', array('a2wl-admin-script'), A2WL()->version, true);

        wp_enqueue_script('a2wl-sprintf-script', A2WL()->plugin_url() . '/assets/js/sprintf.js', array(), A2WL()->version);

        $lang_data = array(
            'placing_orders_d_of_d' => _x('Placing orders %d/%d...', 'Status', 'ali2woo'),
            'please_wait_data_loads' => _x('Please wait, data loads..', 'Status', 'ali2woo'),
            'process_update_d_of_d_erros_d' => _x('Process update %d of %d. Errors: %d.', 'Status', 'ali2woo'),
            'process_sync_d_of_d_erros_d' => _x('Process sync %d of %d. Errors: %d.', 'Status', 'ali2woo'),
            'complete_result_updated_d_erros_d' => _x('Complete! Result updated: %d; errors: %d.', 'Status', 'ali2woo'),
            'complete_result_sync_d_erros_d' => _x('Complete! Successfully synced: %d; errors: %d.', 'Status', 'ali2woo'),
            'install_chrome_ext' => _x('Please install and connect to your website the Ali2Woo chrome extension to use this feature.', 'Status', 'ali2woo'),
            'please_connect_chrome_extension_check_d' => _x('Please connect the Chrome extension to your store and then continue. Need help? Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'we_found_old_order' => _x('We found an old order fulfillment process and removed it. Press the "Continue" button.', 'Status', 'ali2woo'),
            'login_into_aliexpress_account' => _x('Please switch to AliExpress tab and login into your AliExpress account.', 'Status', 'ali2woo'),
            'detected_old_aliexpress_interface' => _x('Detected old AliExpress interface. Please contact Ali2Woo support.', 'Status', 'ali2woo'),
            'your_customer_address_entered' => _x('Your customer address is entered. Wait...', 'Status', 'ali2woo'),
            'product_is_added_to_cart' => _x('Product (%d) is added to the cart. Wait...', 'Status', 'ali2woo'),
            'all_products_are_added' => _x('All products are added to the cart. Wait...', 'Status', 'ali2woo'),
            'cart_is_cleared' => _x('The previous cart data is cleared. Wait...', 'Status', 'ali2woo'),
            'get_no_responces_from_chrome_ext_d' => _x('Get no responces from the chrome extension for 30s. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'fill_order_note' => _x('Filling order notes...', 'Status', 'ali2woo'),
            'cant_add_product_to_cart_d' => _x('Can`t add this product to the cart. Switch to AliExpress and choose another one or add a similar product from another supplier manually. Then continue. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'please_type_customer_address' => _x('Please switch to AliExpress tab, type the address or skip this order.', 'Status', 'ali2woo'),
            'please_input_captcha' => _x('Please switch to AliExpress and input the Captcha code manually or wait for your Captcha solver to do the job...', 'Status', 'ali2woo'),
            'order_is_placed' => _x('The order is placed. Wait...', 'Status', 'ali2woo'),
            'internal_aliexpress_error' => _x('Internal AliExpress error. Please continue to try again or skip this order.', 'Status', 'ali2woo'),
            'all_orders_are_placed' => _x('All orders are placed! Click "Orders List" to be directed to the orders list on the AliExpress website.', 'Status', 'ali2woo'),
            'cant_process_your_orders' => _x('We can`t process your orders. Check out the "Status Page" for more details.', 'Status', 'ali2woo'),
            'cant_get_order_id' => _x('Can`t get the external order ID, please copy it manually to your WC order. Then continue.', 'Status', 'ali2woo'),
            'payment_is_failed' => _x('The payment is failed, please finish this order manually. Then continue.', 'Status', 'ali2woo'),
            'done_pay_manually' => _x('Please switch to AliExpress and pay for the order.', 'Status', 'ali2woo'),
            'choose_payment_method' => _x('Please switch to AliExpress and choose payment method.', 'Status', 'ali2woo'),

            'please_activate_right_store_apikey_in_chrome' => _x('This website is not connected to the Ali2Woo chrome extension. Please check that you choose right API key.', 'Status', 'ali2woo'),

            'bad_product_id' => _x('Can`t find the WC order with a given ID.', 'Status', 'ali2woo'),
            'no_variable_data' => _x('This order has a variable product but doesn`t contain the variable data for some reason. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'no_product_url' => _x('This order doesn`t contain the `product_url` field for some reason. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),
            'no_ali_products' => _x('No AliExpress products in the current order. Check out <a href="%s">the instruction</a>', 'Status', 'ali2woo'),

            'unknown_error' => _x('Unknown error occured. Please contact support.', 'Status', 'ali2woo'),
            'server_error' => _x('Server error. Continue to try again.', 'Status', 'ali2woo'),
        );

        $data = [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce_action' => wp_create_nonce(self::AJAX_NONCE_ACTION),
            'lang' => $lang_data,
        ];

        wp_localize_script('a2wl-ali-orderfulfill-js', 'a2wl_ali_orderfulfill_js', $data);

        $lang_data = [
            'sync_failed_in_fulfillment_popup' => esc_html_x(
                'Product can`t be synchronized, so its shipping information cannot be loaded.',
                'Status',
                'ali2woo'
            ),
        ];
        wp_localize_script('a2wl-admin-script', 'a2wl_sync_data', ['lang' => $lang_data]);
    }

    public function bulk_actions(array $bulk_actions): array
    {
        $bulk_actions['a2wl_order_place_bulk'] = esc_html__("Place on AliExpress", 'ali2woo');
        $bulk_actions['a2wl_order_sync_bulk'] = esc_html__("Sync with AliExpress", 'ali2woo');

        return $bulk_actions;
    }

    public function fill_additional_shipping_fields($additional_shipping_fields, $order, $country = false)
    {
        if (!$country) {
            $country = $this->get_order_shipping_to_country($order);
        }

        $rutMetaKey = get_setting('fulfillment_rut_meta_key', '');

        $custom_attributes = [];

        $value = '';

        if (empty($rutMetaKey)) {
            $custom_attributes['disabled'] = 'disabled';
        } else {
            $value = get_post_meta($order->get_id(), $rutMetaKey, true);
        }

        $additional_shipping_fields['rut'] = [
            'id' => 'rut',
            'description' => esc_html__('You have to configure RUT meta field in the plugin settings',  'ali2woo'),
            'desc_tip' => true,
            'label' => esc_html__( 'RUT', 'ali2woo' ),
            'show'  => false,
            'wrapper_class' => '_shipping_rut '.($country !== 'CL' ? 'hidden' : ''),
            'custom_attributes' => $custom_attributes,
            'value' => $value,
            'custom' => [
                'meta_key' => $rutMetaKey
            ]
        ];

        $cpfMetaKey = get_setting('fulfillment_cpf_meta_key', '');

        $custom_attributes = [];

        $value = '';

        if (empty($cpfMetaKey)) {
            $custom_attributes['disabled'] = 'disabled';
        } else {
            $value = get_post_meta($order->get_id(), $cpfMetaKey, true);
        }

        $additional_shipping_fields['cpf'] = [
            'id' => 'cpf',
            'description' => esc_html__('You have to configure CPF meta field in the plugin settings',  'ali2woo'),
            'desc_tip' => true,
            'label' => esc_html__( 'CPF', 'ali2woo' ),
            'show'  => false,
            'wrapper_class' => '_shipping_cpf '.($country !== 'BR' ? 'hidden' : ''),
            'custom_attributes' => $custom_attributes,
            'value' => $value,
            'custom' => [
                'meta_key' => $cpfMetaKey
            ]
        ];

        return $additional_shipping_fields;
    }

    public function update_custom_shipping_field($key, $field, $order): void
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        if (in_array($key, ['rut', 'cpf']) && $field['custom']['meta_key'] && !empty($_POST[$key])) {
            // phpcs:ignore WordPress.Security.NonceVerification.Missing
            update_post_meta($order->get_id(), $field['custom']['meta_key'], $_POST[$key]);
        }
    }

    public function place_orders_bulk_popup()
    {
        $this->include_view('includes/place_orders_bulk_popup.php');
    }

    public function place_shipping_modal()
    {
        $this->model_put('countries', WC()->countries->get_countries());
        $this->include_view('includes/shipping_modal.php');
    }

    public function ajax_get_aliexpress_order_data(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $result = array("state" => "ok", "data" => "", "action" => "");

        $post_id = $_POST['id'] ?? false;

        if (!$post_id) {
            $result['state'] = 'error';
            $result['error_code'] = -1;
            echo wp_json_encode($result);
            wp_die();
        }

        $order = new WC_Order($post_id);

        $def_prefship = get_setting('fulfillment_prefship');
        $def_customer_note = get_setting('fulfillment_custom_note');
        $def_phone_number = get_setting('fulfillment_phone_number');
        $def_phone_code = get_setting('fulfillment_phone_code');

        $content = array('id' => $post_id,
            'defaultShipping' => $def_prefship,
            'note' => $def_customer_note !== "" ? $def_customer_note : $this->get_customer_note($order),
            'products' => array(),
            'countryRegion' => $this->get_country_region($order),
            'region' => strtolower($this->get_region($order)),
            'city' => $this->get_city($order),
            'contactName' => $this->get_contactName($order),
            'address1' => $this->get_address1($order),
            'address2' => $this->get_address2($order),
            'mobile' => $def_phone_number !== "" ? $def_phone_number : $this->get_phone($order),
            'mobile_code' => $def_phone_code !== "" ? $def_phone_code : '',
            'zip' => $this->get_zip($order),
            'autopay' => false /* todo: rudiment option remove it*/,
            'awaitingpay' => false /* todo: rudiment option remove it*/,
            'cpf' => $this->get_cpf($order),
            'storeurl' => get_site_url(),
            'currency' => $this->get_currency($order),
        );

        $items = $order->get_items();

        $k = 0;
        $total = 0;
        foreach ($items as $item) {

            $normalized_item = new WooCommerceOrderItem($item);
            $product_id = $normalized_item->get_product_id();
            $variation_id = $normalized_item->get_variation_id();
            $quantity = $normalized_item->get_quantity();

            $external_id = get_post_meta($product_id, '_a2w_external_id', true);

            if ($external_id) {

                $skuArray = $this->getSkuArray($normalized_item);

                if (empty($skuArray) && $variation_id && $variation_id > 0) {
                    $result['error_code'] = -2;
                    $result['state'] = 'error';
                    echo wp_json_encode($result);
                    wp_die();
                }

                $original_url = get_post_meta($product_id, '_a2w_product_url', true);

                if (empty($original_url)) {
                    $result['error_code'] = -3;
                    $result['state'] = 'error';
                    echo wp_json_encode($result);
                    wp_die();
                }

                //try to use shipping method that user choose on the product page, cart or checkout
                //if it returns empty, then keep it
                //because chrome extension chosoe default shipping method in this case
                $shipping_service_name = $normalized_item->get_ali_shipping_code();

                //todo: make an ability to change the shipping method
                //before place order on AliExpress

                $content['products'][$k] = array(
                    'url' => $original_url,
                    'productId' => $external_id,
                    'originalId' => $product_id,
                    'qty' => $quantity,
                    'sku' => $skuArray,
                    'shipping' => $shipping_service_name,
                );

                $k++;
            }

            $total++;
        }

        if ($k < 1) {
            $result['error_code'] = -4;
            $result['state'] = 'error';
            echo wp_json_encode($result);
            wp_die();
        }

        if ($k == $total) {
            $result['action'] = 'upd_ord_status';
        }

        $result['data'] = array('content' => $content, 'id' => $post_id);

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_load_fulfillment_model_html(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        
        
        $purchase_code = 1;
        
        ?>
        <div class="modal-overlay modal-fulfillment">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php _ex('Order fulfillment', 'popup title', 'ali2woo');?></h3>
                    <a class="modal-btn-close" href="#"></a>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <?php if ($purchase_code):?>
                    <div style="display: inline-block;">
                    <a id="pay-for-orders" target="_blank" class="btn btn-success" href="https://www.aliexpress.com/p/order/index.html" title="<?php  esc_html_e('You will be redirected to the AlIExpress portal. You must be authorized in your account to make the payment', 'ali2woo');?>"><?php  esc_html_e('Pay for order(s)', 'ali2woo');?></a>
                    <button id="fulfillment-auto" class="btn btn-success" type="button">
                        <div class="btn-icon-wrap cssload-container"><div class="cssload-speeding-wheel"></div></div>
                        <?php  esc_html_e('Fulfil orders automatically', 'ali2woo');?>
                    </button>
                    </div>

                    <?php endif; ?>

                    <?php /*
                    <?php if($purchase_code):?>
                    <button id="fulfillment-chrome" class="btn btn-success" type="button">
                        <div class="btn-icon-wrap cssload-container"><div class="cssload-speeding-wheel"></div></div>
                        <?php  esc_html_e('Fulfil orders via Chrome extension', 'ali2woo');?>
                    </button>
                    <?php endif; ?>
                    */ ?>
                    <button class="btn btn-default modal-close" type="button"><?php  esc_html_e('Close');?></button>
                </div>
            </div>
        </div>

    <?php wp_die();
    }

    public function get_order_shipping_to_country($order): string
    {
        $shipping_address = $order->get_address('shipping');
        if (empty($shipping_address['country'])) {
            $shipping_address = $order->get_address('billing');
        }

        $AliexpressHelper = A2WL()->getDI()->get('AliNext_Lite\AliexpressHelper');

        return $AliexpressHelper->convertToAliexpressCountryCode($shipping_address['country']);

      //  return ProductShippingMeta::normalize_country($shipping_address['country']);
    }

    public function ajax_load_fulfillment_orders_html(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $ids = array_map(
                'intval',
                isset($_POST['ids']) ? (is_array($_POST['ids']) ? $_POST['ids'] : [$_POST['ids']]) : []
        );

        $orders = [];
        if (!empty($ids)) {
            foreach ($ids as $order_id) {
                $orders[] = new WC_Order($order_id);
            }
        }

        $is_wpml = $this->isWpml();

        /** @var OrderFulfillmentService $OrderFulfillmentService  */
        $OrderFulfillmentService = A2WL()->getDI()->get('AliNext_Lite\OrderFulfillmentService');
        $orders_data = $OrderFulfillmentService->getFulfillmentOrdersData($orders, $is_wpml);

         if (empty($orders_data)) {
            $text = esc_html__("Orders not found", 'ali2woo');
            $this->model_put("text", $text);
            $this->include_view("order-fulfillment/error_container.php");
        } else {
            $this->model_put("shipping_fields", self::$shipping_fields);
            $this->model_put("additional_shipping_fields", self::$additional_shipping_fields);
            $this->model_put("countries", WC()->countries->get_countries());

            foreach ($orders_data as $order_data) {
                $urls_to_data = '';
                if (!empty($order_data['sign_urls'])) {
                    $urls_to_data = implode(';', $order_data['sign_urls']);
                }

                $this->model_put("order_data", $order_data);
                $this->model_put("urls_to_data", $urls_to_data);
                $this->include_view("order-fulfillment/single_order_container.php");
            }
        }

        wp_die();
    }

    public function ajax_load_fulfillment_orders_service_html(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        //todo: should support single id only!
        $ids = array_map(
            'intval',
            isset($_POST['ids']) ? (is_array($_POST['ids']) ? $_POST['ids'] : [$_POST['ids']]) : []
        );

     //  $orders = [];
        if (!empty($ids)) {
            foreach ($ids as $order_id) {
               // $orders[] = new WC_Order($order_id);
                $WC_Order = new WC_Order($order_id);
            }
        }

        $is_wpml = $this->isWpml();

        /** @var OrderFulfillmentService $OrderFulfillmentService  */
        $OrderFulfillmentService = A2WL()->getDI()->get('AliNext_Lite\OrderFulfillmentService');
        $orderData = $OrderFulfillmentService->getFulfillmentOrderServiceData($WC_Order, $is_wpml);

        if (!($orderData)) {
            wp_die();
        }

        $columns = [
            [
                'title' => esc_html__('Item', 'ali2woo'),
                'class' => 'name',
                'colspan' => 2,
            ],
            [
                'title' => esc_html__('Shipping Company', 'ali2woo'),
                'class' => 'shipping_company',
            ],
            [
                'title' => esc_html__('Delivery Time', 'ali2woo'),
                'class' => 'delivery_time',
            ],
            [
                'title' => esc_html__('Shipping Cost', 'ali2woo'),
                'class' => 'shipping_cost',
            ],
            [
                'title' => esc_html__('Cost', 'ali2woo'),
                'class' => 'cost',
            ],
            [
                'title' => esc_html__('Total', 'ali2woo'),
                'class' => 'total',
            ],
            [
                'title' => '',
                'class' => 'actions',
            ]
        ];

        $this->model_put("table_class", 'service-fulfillment-order-items');
        $this->model_put("columns", $columns);
        $this->model_put("order_data", $orderData);
        $this->include_view("order-fulfillment/partials/table_order_items.php");

        wp_die();
    }

    public function ajax_save_order_shipping_info(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $shiping_to_country = false;

        if(isset($_POST['_shipping_country'])) {
            $shiping_to_country = ProductShippingMeta::normalize_country($_POST['_shipping_country']);
        }

        if(!isset($_POST['order_id'])) {
            $result=array('state'=>'error', 'message'=>'waiting for order id');
        } else {
            // Get order object.
            $order = wc_get_order($_POST['order_id']);
            $props = array();

            $additional_shipping_fields = apply_filters('a2wl_fill_additional_shipping_fields', self::$additional_shipping_fields, $order, $shiping_to_country);
            $shipping_fields = array_merge(self::$shipping_fields, $additional_shipping_fields);

            // Update shipping fields.
            if ( !empty($shipping_fields)) {
                foreach ($shipping_fields as $key => $field) {
                    if (!isset($field['id'])) {
                        $field['id'] = '_shipping_' . $key;
                    }

                    if (!isset($_POST[$field['id']])) {
                        continue;
                    }

                    if (!empty($field['custom'])) {
                        do_action('a2wl_update_custom_shipping_field', $key, $field, $order);
                    } else {
                        if (is_callable(array($order, 'set_shipping_' . $key))) {
                            $props['shipping_' . $key] = wc_clean(wp_unslash($_POST[$field['id']]));
                        } else {
                            $order->update_meta_data($field['id'], wc_clean(wp_unslash($_POST[$field['id']])));
                        }
                    }

                }
            }

            // Save order data.
            $order->set_props( $props );
            $order->save();

            if($shiping_to_country) {
                foreach ($order->get_items() as $item) {
                    $product = $item->get_product();

                    $shipping_info = Utils::get_product_shipping_info($product, $item->get_quantity(), $shiping_to_country, false);

                    $shipping_meta_data = $item->get_meta(Shipping::get_order_item_shipping_meta_key());
                    $shipping_meta_data = $shipping_meta_data ? json_decode($shipping_meta_data, true) : array('company' => '', 'service_name' => '', 'delivery_time' => '', 'shipping_cost' => '', 'quantity' => $item->get_quantity(), 'cost_added' => true);
                    foreach ($shipping_info['items'] as $si) {
                        if ($si['serviceName'] == $shipping_info['default_method']) {
                            $shipping_meta_data['company'] = $si['company'];
                            $shipping_meta_data['service_name'] = $si['serviceName'];
                            $shipping_meta_data['shipping_cost'] = $si['freightAmount']['value'];
                            $shipping_meta_data['delivery_time'] = $si['time'];  
                        }
                    }

                    $item->update_meta_data(Shipping::get_order_item_shipping_meta_key(), wp_json_encode($shipping_meta_data));
                    $item->save_meta_data();
                }
            }

            $result = array('state'=>'ok');
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_update_fulfillment_shipping(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $errorText = _x('Shipping method not found', 'error text', 'ali2woo');
        $result = ResultBuilder::buildError($errorText);

        $is_wpml = $this->isWpml();

        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $shiping_to_country = $_POST['shiping_to_country'] ?? false;
        $items = isset($_POST['items']) && is_array($_POST['items']) ? $_POST['items'] : [];

        $order_items = [];
        foreach ($items as $item) {
            $order_items[] = new OrderItemShippingDto($item['order_item_id'], $item['shipping']);
        }

        if ($shiping_to_country && $order_id) {
            $order = new WC_Order($order_id);
            /** @var OrderFulfillmentService $OrderFulfillmentService  */
            $OrderFulfillmentService = A2WL()->getDI()->get('AliNext_Lite\OrderFulfillmentService');
            $UpdateFulfillmentShippingResult = $OrderFulfillmentService->updateFulfillmentShipping(
                    $order, $order_items, $shiping_to_country, $is_wpml
            );

            $result = ResultBuilder::buildOk(['result' => [
                'order_id' => $order_id,
                'total_order_price' => wc_price(
                        $UpdateFulfillmentShippingResult->getTotalOrderPrice(),
                        ['currency' => $order->get_currency()]
                ),
                'items' => $UpdateFulfillmentShippingResult->getResultItems(),
            ]]);
        } else {
            $errorText = _x('wrong params', 'error text', 'ali2woo');
            $result = ResultBuilder::buildError($errorText);
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_load_fulfillment_place_order(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $orderItemsIds = isset($_POST['items']) && is_array($_POST['items']) ?
            array_map('intval', $_POST['items']) : [];

        /** @var OrderFulfillmentService $OrderFulfillmentService  */
        $OrderFulfillmentService = A2WL()->getDI()->get('AliNext_Lite\OrderFulfillmentService');

        if ($order_id && $orderItemsIds) {
            $WC_Order = new WC_Order($order_id);
            $OrderItems = [];
            foreach ($WC_Order->get_items() as $orderItem) {
                if (in_array($orderItem->get_id(), $orderItemsIds)) {
                    $OrderItems[] = $orderItem;
                }
            }
            $result = $OrderFulfillmentService->placeOrder($WC_Order, $OrderItems);
        } else {
            $result = ResultBuilder::buildError('Wrong params');
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_sync_order_info(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        if (empty($_POST['order_id'])) {
            $result = ResultBuilder::buildError('wrong params');
        } else {
            /**
             * @var Woocommerce $wc_api
             */
            $wc_api = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');
            $result = $wc_api->sync_order_with_aliexpress($_POST['order_id']);
        }

        echo wp_json_encode($result);
        wp_die();
    }

    private function isWpml(): bool
    {
        $is_wpml = false;
        if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
            $default_lang = apply_filters('wpml_default_language', null);
            $current_language = apply_filters('wpml_current_language', null);
            if ($current_language && $current_language !== $default_lang) {
                $is_wpml = true;
            }
        }

        return $is_wpml;
    }

    private function format_field($str): string
    {
        $str = trim($str);

        if (!empty($str)) {
            $str = ucwords(strtolower($str));
        }

        return $str;
    }

    private function get_currency($order): string
    {
        return strtolower($order->get_currency());
    }

    private function get_cpf($order)
    {
        $b_cpf = $order->get_meta('_billing_cpf');
        $s_cpf = $order->get_meta('_shipping_cpf');

        $cpf = $b_cpf ?: ($s_cpf ?: '');

        return $cpf ? preg_replace("/[^0-9]/", "", $cpf) : '';
    }

    private function get_phone($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->billing_phone ?: $order->shipping_phone;
        } else {
            $result = $order->get_billing_phone();
        }

        return preg_replace('/[^0-9]+/', '', $result);
    }

    private function get_customer_note($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->customer_note;
        } else {
            $result = $order->get_customer_note();
        }

        return $this->translitirate($result);
    }

    private function get_country_region($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_country ? $this->format_field_country($order->shipping_country) : $this->format_field_country($order->billing_country);
        } else {
            $result = $order->get_shipping_country() ? $this->format_field_country($order->get_shipping_country()) : $this->format_field_country($order->get_billing_country());
        }

        return $this->translitirate($result);
    }

    private function get_region($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_state ? $this->format_field_state($order->shipping_country, $order->shipping_state) : $this->format_field_state($order->billing_country, $order->billing_state);
        } else {
            $result = $order->get_shipping_state() ? $this->format_field_state($order->get_shipping_country(), $order->get_shipping_state()) : $this->format_field_state($order->get_billing_country(), $order->get_billing_state());
        }

        return $this->translitirate($result);
    }

    private function get_city($order)
    {

        if (WC()->version < '3.0.0') {
            $result = $order->shipping_city ? $this->format_field($order->shipping_city) : $this->format_field($order->billing_city);
        } else {
            $result = $order->get_shipping_city() ? $this->format_field($order->get_shipping_city()) : $this->format_field($order->get_billing_city());
        }

        return $this->translitirate($result);
    }

    private function get_contactName($order)
    {

        if (WC()->version < '3.0.0') {

            if ($order->shipping_first_name) {
                $result = $order->shipping_first_name . ' ' . $order->shipping_last_name;

                if (isset($this->shipping_third_name)) {
                    $result .= ' ' . $order->shipping_third_name;
                }
            } else {
                $result = $order->billing_first_name . ' ' . $order->billing_last_name;

                if (isset($this->billing_third_name)) {
                    $result .= ' ' . $order->billing_third_name;
                }
            }

        } else {
            $result = $order->get_shipping_first_name() ? $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() . ' ' . $order->get_meta('_shipping_third_name') : $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() . ' ' . $order->get_meta('_billing_third_name');
        }

        return $this->translitirate($result);
    }

    private function get_address_number($order)
    {
        $b_number = $order->get_meta('_billing_number');
        $s_number = $order->get_meta('_shipping_number');

        $number = $b_number ?: ($s_number ?: '');

        return $number ? preg_replace("/[^0-9]/", "", $number) : '';
    }

    private function get_address1($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_address_1 ?: $order->billing_address_1;
        } else {
            $result = $order->get_shipping_address_1() ? $order->get_shipping_address_1() : $order->get_billing_address_1();
        }

        //Add street number if it's available
        $result = $result . " " . $this->get_address_number($order);

        return $this->translitirate($result);
    }

    private function get_address2($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_address_2 ?: $order->billing_address_2;
        } else {
            $result = $order->get_shipping_address_2() ? $order->get_shipping_address_2() : $order->get_billing_address_2();
        }

        return $this->translitirate($result);
    }

    private function get_zip($order)
    {
        if (WC()->version < '3.0.0') {
            $result = $order->shipping_postcode ?: $order->billing_postcode;
        } else {
            $result = $order->get_shipping_postcode() ? $order->get_shipping_postcode() : $order->get_billing_postcode();
        }

        return $result;
    }

    private function format_field_country($str): string
    {
        $str = trim($str);

        if (!empty($str)) {
            $str = strtoupper($str);
        }

        if ($str === "GB") {
            $str = "UK";
        }

        if ($str == "RS") {
            $str = "SRB";
        }

        if ($str == "ME") {
            $str = "MNE";
        }

        return $str;
    }

    private function format_field_state($country_code, $state_code): string
    {
        if (isset(WC()->countries->states[$country_code]) && isset(WC()->countries->states[$country_code][$state_code])) {
            $result = $this->format_field(WC()->countries->states[$country_code][$state_code]);
        } else {
            $result = $state_code;
        }

        //WooCommerce translation file has html entities
        return html_entity_decode($result, ENT_QUOTES, 'UTF-8');
    }

    private function getSkuArray($item): array
    {
        if ($item->get_variation_id() !== 0) {
            $variation_id = $item->get_variation_id();
            $sku = $this->getSkuArrayByVariationID($variation_id);

        } else {
            $product_id = $item->get_product_id();
            $sku = $this->getSkuArrayByVariationID($product_id);

            // if (empty($sku)){
            //     // Backward-compatible code to get sku data for Simple type product
            //     $handle=new \WC_Product_Variable($product_id);
            //     if ($handle){
            //         $variations_ids=$handle->get_children();
            //         if ($variations_ids && count($variations_ids) > 0){
            //             $first_variation_id = $variations_ids[0];
            //             $sku = $this->getSkuArrayByVariationID($first_variation_id);
            //         }
            //     }
            // }
        }
        return $sku;
    }

    private function getSkuArrayByVariationID($variation_id): array
    {
        $sku = array();

        $external_var_data = get_post_meta($variation_id, '_aliexpress_sku_props', true);

        if (empty($external_var_data)) {
            return $sku;
        }

        if ($external_var_data) {
            $items = explode(';', $external_var_data);

            foreach ($items as $item) {
                list(, $sku[]) = explode(':', $item);
            }
        }

        return $sku;
    }

    private function translitirate($result)
    {
        if (get_setting('order_translitirate')) {
            $result = Utils::safeTransliterate($result);
        }

        return $result;
    }

}
