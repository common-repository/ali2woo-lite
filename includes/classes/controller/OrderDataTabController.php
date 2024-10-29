<?php
/* * class
 * Description of OrderDataTabController
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_admin_init
 *
 * @ajax: true
 */
// phpcs:ignoreFile WordPress.Security.NonceVerification.Recommended
namespace AliNext_Lite;;
use Automattic\WooCommerce\Utilities\OrderUtil;
use Throwable;

class OrderDataTabController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();

        //the following 4 methods are shared with tracking plugin
        add_action('admin_enqueue_scripts', [$this, 'assets']);
        add_action('woocommerce_admin_order_data_after_order_details', array($this, 'woocommerce_admin_order_data_after_order_details'));
        add_action('woocommerce_process_shop_order_meta', array($this, 'woocommerce_process_shop_order_meta'));
        add_action('wp_ajax_a2wl_reset_order_data', [$this, 'ajax_reset_order_data']);

        //Modify order item data
        add_filter('woocommerce_order_item_display_meta_key', array($this, 'woocommerce_order_item_display_meta_key'), 99, 3);
        add_filter('woocommerce_order_item_display_meta_value', array($this, 'woocommerce_order_item_display_meta_value'), 10, 3);

        //Filter order meta in emails
        add_filter('woocommerce_email_order_items_args', array($this, 'woocommerce_email_order_items_args'), 10);
    }

    public function assets(): void
    {
        if (OrderUtil::custom_orders_table_usage_is_enabled()) {
            $currentPage = $_REQUEST['page'] ?? false;
            $orderId = $_REQUEST['id'] ?? false;
            if ($currentPage === 'wc-orders' && $orderId !== false) {
                $this->enqueueAssets();
            }
        } else {
            if (isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] === 'edit') {
                $this->enqueueAssets();
            }
        }

        if (wp_script_is('a2wl-wc-order-edit-script', 'enqueued')) {
            $data = [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce_action' => wp_create_nonce(self::AJAX_NONCE_ACTION),
            ];
            wp_localize_script('a2wl-wc-order-edit-script', 'a2wl_order_edit_script', $data);
        }

        if (!wp_script_is('a2wl-admin-script', 'enqueued')) {
            wp_enqueue_script('a2wl-admin-script',
                A2WL()->plugin_url() . '/assets/js/admin_script.js',
                array('jquery'),
                A2WL()->version
            );
        }

        AbstractAdminPage::localizeAdminScript();
    }

    public function ajax_reset_order_data(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $result = ResultBuilder::buildOk();

        try {
            $order_id = (int) $_POST['id'];
            $order = wc_get_order($order_id);
            if ($order) {
                $order_items = $order->get_items();
                foreach ($order_items as $item_id => $item) {
                    $item->delete_meta_data(Constants::order_item_external_order_meta());
                    $item->delete_meta_data(Constants::order_item_tracking_data_meta());
                    $item->save();
                }
                $order->add_order_note(esc_html__('The order external ID(s) and tracking numbers have been reset.', 'ali2woo'), false, true);
            } else {
                $result = ResultBuilder::buildError('did not find the order id: №' . $order_id);
            }
            restore_error_handler();
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function woocommerce_admin_order_data_after_order_details($WC_Order): void
    {
        ?>

        <br class="clear" />
        <h3><?php  esc_html_e('A2WL Order Data', 'ali2woo')?><a href="#" class="edit_address"><?php  esc_html_e('Edit')?></a></h3>

        <div class="address order_items">

        <?php
        $order_items = $WC_Order->get_items();

        ?>

        <?php if (count($order_items)): ?>
        <p>
        <?php foreach ($order_items as $item_id => $item): ?>


        <?php

        $a2wl_order_item = new WooCommerceOrderItem($item);

        $external_order_id = $a2wl_order_item->get_external_order_id();

        $ali_order_link = $a2wl_order_item->get_ali_order_item_link();

        $order_item_tracking_codes = $a2wl_order_item->get_formated_tracking_codes();

        //$carrier_link = $a2wl_order_item->get_formated_carrier_link();

        $carrier_name = $a2wl_order_item->get_carrier_name();

        ?>

        <strong><?php  esc_html_e('Item', 'ali2woo')?>:</strong> <a target="_blank" href="<?php echo get_edit_post_link($item['product_id']); ?>"><?php echo $this->shorten_display_value($item->get_name()); ?></a>
        <a class="product_view" target="_blank" title="<?php  esc_html_e('View product', 'ali2woo')?>" href="<?php echo get_permalink($item['product_id']); ?>"></a><br/>
        <strong><?php  esc_html_e('AliExpress order ID', 'ali2woo')?>:</strong> <?php echo $ali_order_link; ?><br/>
        <strong><?php  esc_html_e('Tracking numbers', 'ali2woo')?>:</strong> <?php echo $order_item_tracking_codes; ?><br/>
        <?php if ($carrier_name): ?><strong><?php  esc_html_e('Carrier', 'ali2woo')?>:</strong> <?php echo $carrier_name; ?><br/><?php endif;?>
        <br/>
        <?php endforeach;?>
        </p>
        <?php endif;?>

        <a role="button" class="a2wl_reset_order_data" href="#"><?php  esc_html_e('Reset A2W Order Data', 'ali2woo')?></a>

        <?php

        /** todo: In previous plugin version the Tracking numbers and external order id are save in other metas
         * in the order data instead of order item data
         * The plugin still shows this data in read-only format, it will be removed completely in the future plugin version
         **/
        $order_external_id_array = get_post_meta($WC_Order->get_id(), Constants::old_order_external_order_id());

        $order_tracking_codes = get_post_meta($WC_Order->get_id(), Constants::old_order_tracking_code());

        ?>

        <?php if ($order_external_id_array || $order_tracking_codes): ?>
        <hr/>
        <p><?php  esc_html_e('Old order data goes below. It will be  removed completely in a future versions of the Ali2Woo plugin.', 'ali2woo')?></p>
        <?php endif;?>

        <?php if ($order_external_id_array): ?>
        <?php  esc_html_e('AliExpress order ID(s)', 'ali2woo')?>:
        <div class="a2wl_external_order_id">
        <?php foreach ($order_external_id_array as $k => $order_external_id): ?>
        <?php echo htmlentities($order_external_id) ?><?php if ($k < count($order_external_id_array) - 1): ?>,<?php endif;?>
        <?php endforeach;?>
        </div>

        <?php endif;?>


        <?php if ($order_tracking_codes): ?>
        <?php  esc_html_e('AliExpress tracking numbers', 'ali2woo')?>:
        <div class="a2wl_tracking_code_data">
        <?php foreach ($order_tracking_codes as $k => $tracking_code): ?>
        <?php echo htmlentities($tracking_code) ?><?php if ($k < count($order_tracking_codes) - 1): ?>,<?php endif;?>
        <?php endforeach;?>
        </div>

        <?php endif;?>

        </div>
        <div class="edit_address">

        <?php if (count($order_items)): ?>
        <?php foreach ($order_items as $item_id => $item): ?>
        <div class="clear"></div>
        <h3><?php  esc_html_e('Item', 'ali2woo')?>: <span><a target="_blank" href="<?php echo get_edit_post_link($item['product_id']); ?>"><?php echo $this->shorten_display_value($item->get_name()); ?></a> <a class="product_view" target="_blank" title="<?php  esc_html_e('View product', 'ali2woo')?>" href="<?php echo get_permalink($item['product_id']); ?>"></a></span></h3>

        <?php

        $a2wl_order_item = new WooCommerceOrderItem($item);

        $external_order_id = $a2wl_order_item->get_external_order_id();

        $order_item_tracking_codes = $a2wl_order_item->get_formated_tracking_codes(true);
        ?>

        <?php woocommerce_wp_text_input(array(
            'id' => 'a2wl_external_order_id_' . $item_id,
            'name' => 'a2wl_external_order_id_' . $item_id,
            'label' => esc_html__('AliExpress Order ID', 'ali2woo'),
            'value' => $external_order_id,
            'wrapper_class' => 'form-field-wide',
        ));
        ?>

        <?php woocommerce_wp_text_input(array(
            'id' => 'a2wl_tracking_code_' . $item_id,
            'name' => 'a2wl_tracking_code_' . $item_id,
            'label' => esc_html__('Tracking numbers', 'ali2woo'),
            /* Here it can be an array of Tracking numbers, because sometimes AliExpress replace old tracking code with new one for some orders */
            'value' => $order_item_tracking_codes,
            'wrapper_class' => 'form-field-wide',
        ));
        ?>

        <?php endforeach;?>
        <?php endif;?>
        </div>
    <?php }

    public function woocommerce_process_shop_order_meta($order_id): void
    {
        $order = wc_get_order($order_id);
        $order_items = $order->get_items();

        foreach ($order_items as $item_id => $item) {

            $a2wl_order_item = new WooCommerceOrderItem($item);

            if (isset($_POST['a2wl_external_order_id_' . $item_id]) && !empty($_POST['a2wl_external_order_id_' . $item_id])) {

                $external_order_id = intval($_POST['a2wl_external_order_id_' . $item_id]);

                $a2wl_order_item->update_external_order($external_order_id);

            }

            if (isset($_POST['a2wl_tracking_code_' . $item_id]) && !empty($_POST['a2wl_tracking_code_' . $item_id])) {
                $tracking_codes = explode(',', $_POST['a2wl_tracking_code_' . $item_id]);
                $tracking_codes = array_map('trim', $tracking_codes);

                $a2wl_order_item->update_tracking_codes($tracking_codes);

            }

            if ($a2wl_order_item->save()) {
                $order->add_order_note(esc_html__('The order external ID(s) and/or tracking numbers have been added to the order manually.', 'ali2woo'), false, true);
            }

        }

    }

    public function woocommerce_order_item_display_meta_key($display_key, $meta, $item)
    {

        //todo: maybe remove this code? THese metas are not displayed on th order edit page

        if ($meta->key === Shipping::get_order_item_shipping_meta_key()) {
            $display_key = esc_html__('Shipping Info', 'ali2woo');
        }

        if ($meta->key == Constants::order_item_external_order_meta()) {
            $display_key = esc_html__('AliExpress Order ID', 'ali2woo');
        }

        if ($meta->key == Constants::order_item_tracking_data_meta()) {
            $display_key = esc_html__('Tracking numbers', 'ali2woo');
        }

        return $display_key;
    }

    public function woocommerce_order_item_display_meta_value($display_value, $meta, $item)
    {
        if ($meta->key === Shipping::get_order_item_shipping_meta_key()) {
            $value = $meta->value;
            if ($value) {
                $order_id = $item->get_order_id();
                $display_value = Shipping::get_formated_shipping_info_meta($order_id, $value);
            }
        }

        if ($meta->key == Constants::order_item_tracking_data_meta()) {
            $display_value = $this->get_formated_order_item_tracking_data_meta($value);
        }

        return $display_value;

    }

    public function woocommerce_email_order_items_args($args)
    {

        if ($args['sent_to_admin']) {
            return $args;
        }

        //a little hack, save in global that we are in the email template
        $_POST['a2wl_email_template_check'] = true;

        return $args;

    }

    private function get_formated_order_item_tracking_data_meta($value)
    {

        $tracking_codes = "";

        if ($value && isset($value['tracking_codes'])) {
            $tracking_codes = implode(",", $value['tracking_codes']);
        }

        return $tracking_codes;
    }

    private function shorten_display_value($string)
    {
        if (strlen($string) >= 40) {
            return substr($string, 0, 30) . " ... " . substr($string, -10);
        } else {
            return $string;
        }
    }

    private function enqueueAssets(): void
    {
        wp_enqueue_script('a2wl-wc-order-edit-script', A2WL()->plugin_url() . '/assets/js/wc_order_edit.js', [], A2WL()->version);
        wp_enqueue_style('a2wl-wc-order-edit-style', A2WL()->plugin_url() . '/assets/css/wc_order_edit.css', [], A2WL()->version);
    }

}
