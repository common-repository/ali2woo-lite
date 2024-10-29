<?php

/**
 * Description of SettingPageAjaxController
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_admin_init
 *
 * @ajax: true
 */

namespace AliNext_Lite;;

use Exception;
use Throwable;

class SettingPageAjaxController extends AbstractController
{
    public const FREE_TARIFF_CODE = 'free';

    public function __construct()
    {
        parent::__construct();

        add_action('wp_ajax_a2wl_update_price_rules', [$this, 'ajax_update_price_rules']);

        add_action('wp_ajax_a2wl_apply_pricing_rules', [$this, 'ajax_apply_pricing_rules']);

        add_action('wp_ajax_a2wl_update_phrase_rules', [$this, 'ajax_update_phrase_rules']);

        add_action('wp_ajax_a2wl_apply_phrase_rules', [$this, 'ajax_apply_phrase_rules']);

        add_action('wp_ajax_a2wl_reset_shipping_meta', [$this, 'ajax_reset_shipping_meta']);

        add_action('wp_ajax_a2wl_calc_external_images_count', [$this, 'ajax_calc_external_images_count']);
        add_action('wp_ajax_a2wl_calc_external_images', [$this, 'ajax_calc_external_images']);
        add_action('wp_ajax_a2wl_load_external_image', [$this, 'ajax_load_external_image']);

        add_action('wp_ajax_a2wl_purchase_code_info', [$this, 'ajax_purchase_code_info']);

        add_action('wp_ajax_a2wl_build_aliexpress_api_auth_url', [$this, 'ajax_build_aliexpress_api_auth_url']);
        add_action('wp_ajax_a2wl_save_access_token', [$this, 'ajax_save_access_token']);
        add_action('wp_ajax_a2wl_delete_access_token', [$this, 'ajax_delete_access_token']);
        add_action('wp_ajax_a2wl_import_cancel_process_action', [$this, 'ajax_import_cancel_process_action']);
    }

    public function ajax_update_phrase_rules(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        a2wl_init_error_handler();

        $result = ResultBuilder::buildOk();
        try {

            PhraseFilter::deleteAll();

            if (isset($_POST['phrases'])) {
                foreach ($_POST['phrases'] as $phrase) {
                    $filter = new PhraseFilter($phrase);
                    $filter->save();
                }
            }

            $result = ResultBuilder::buildOk(array('phrases' => PhraseFilter::load_phrases()));

            restore_error_handler();
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_import_cancel_process_action(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        a2wl_init_error_handler();
        $result = ResultBuilder::buildWarn(
            esc_html__('Process is successfully cancelled! Please wait for a few seconds.', 'ali2woo')
        );
        try {
            if (!isset($_POST['process']) || !$_POST['process']) {
                throw new Exception(esc_html__('Invalid process', 'ali2woo'));
            }
            $processCode = trim($_POST['process']);
            /** @var BackgroundProcessFactory $BackgroundProcessFactory */
            $BackgroundProcessFactory = A2WL()->getDI()->get('AliNext_Lite\BackgroundProcessFactory');
            $BackgroundProcess = $BackgroundProcessFactory->createProcessByCode($processCode);
            if ($BackgroundProcess->isCancelled()) {
                throw new Exception(esc_html__('The process is already cancelled.', 'ali2woo'));
            }
            $BackgroundProcess->cancel();

            restore_error_handler();
        } catch (Throwable $Exception) {
            a2wl_print_throwable($Exception);
            $result = ResultBuilder::buildError($Exception->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_apply_phrase_rules(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        a2wl_init_error_handler();

        $result = ResultBuilder::buildOk();
        try {
            $product_import_model = new ProductImport();

            $type = isset($_POST['type']) ? $_POST['type'] : false;
            $scope = isset($_POST['scope']) ? $_POST['scope'] : false;

            if ($type === 'products' || $type === 'all_types') {
                if ($scope === 'all' || $scope === 'import') {
                    $products = $product_import_model->get_product_list(false);

                    foreach ($products as $product) {

                        $product = PhraseFilter::apply_filter_to_product($product);
                        $product_import_model->upd_product($product);
                    }
                }

                if ($scope === 'all' || $scope === 'shop') {
                    //todo: update attributes as well
                    PhraseFilter::apply_filter_to_products();
                }
            }

            if ($type === 'all_types' || $type === 'reviews') {

                PhraseFilter::apply_filter_to_reviews();
            }

            if ($type === 'all_types' || $type === 'shippings') {

            }
            restore_error_handler();
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_update_price_rules(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        a2wl_init_error_handler();

        $result = ResultBuilder::buildOk();
        try {
            settings()->auto_commit(false);

            $PriceFormulaRepository = A2WL()->getDI()->get('AliNext_Lite\PriceFormulaRepository');
            $PriceFormulaFactory = A2WL()->getDI()->get('AliNext_Lite\PriceFormulaFactory');

            $pricing_rules_types = array_keys(PriceFormula::pricing_rules_types());
            set_setting('pricing_rules_type', $_POST['pricing_rules_type'] && in_array($_POST['pricing_rules_type'], $pricing_rules_types) ? $_POST['pricing_rules_type'] : $pricing_rules_types[0]);

            $use_extended_price_markup = isset($_POST['use_extended_price_markup']) ? filter_var($_POST['use_extended_price_markup'], FILTER_VALIDATE_BOOLEAN) : false;
            $use_compared_price_markup = isset($_POST['use_compared_price_markup']) ? filter_var($_POST['use_compared_price_markup'], FILTER_VALIDATE_BOOLEAN) : false;

            set_setting('price_cents', isset($_POST['cents']) && intval($_POST['cents']) > -1 && intval($_POST['cents']) <= 99 ? intval(wp_unslash($_POST['cents'])) : -1);
            if ($use_compared_price_markup) {
                set_setting('price_compared_cents', isset($_POST['compared_cents']) && intval($_POST['compared_cents']) > -1 && intval($_POST['compared_cents']) <= 99 ? intval(wp_unslash($_POST['compared_cents'])) : -1);
            } else {
                set_setting('price_compared_cents', -1);
            }

            set_setting('use_extended_price_markup', $use_extended_price_markup);
            set_setting('use_compared_price_markup', $use_compared_price_markup);

            set_setting('add_shipping_to_price', !empty($_POST['add_shipping_to_price']) ? filter_var($_POST['add_shipping_to_price'], FILTER_VALIDATE_BOOLEAN) : false);
            set_setting('apply_price_rules_after_shipping_cost', !empty($_POST['apply_price_rules_after_shipping_cost']) ? filter_var($_POST['apply_price_rules_after_shipping_cost'], FILTER_VALIDATE_BOOLEAN) : false);

            settings()->commit();
            settings()->auto_commit(true);

            if (isset($_POST['rules'])) {
                $PriceFormulaRepository->deleteAll();
                foreach ($_POST['rules'] as $rule) {
                    $formula = $PriceFormulaFactory->createFormulaFromData($rule);
                    $PriceFormulaRepository->saveExtendedFormula($formula);
                }
            }

            if (isset($_POST['default_rule'])) {
                $PriceFormulaDefault = $PriceFormulaFactory->createFormulaFromData($_POST['default_rule']);
                $PriceFormulaRepository->setDefaultFormula($PriceFormulaDefault);
            }

            $result = ResultBuilder::buildOk(
                [
                    'rules' => $PriceFormulaRepository->getExtendedFormulas(),
                    'default_rule' => $PriceFormulaRepository->getDefaultFormula(),
                    'use_extended_price_markup' => $use_extended_price_markup,
                    'use_compared_price_markup' => $use_compared_price_markup
                ]
            );

            restore_error_handler();
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_apply_pricing_rules(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        a2wl_init_error_handler();

        $result = ResultBuilder::buildOk(['done' => 1]);
        try {
            $type = $_POST['type'] ?? false;
            $scope = $_POST['scope'] ?? false;
          /*  $page = $_POST['page'] ?? 0;
            $import_page = $_POST['import_page'] ?? 0;*/

            $ApplyPricingRulesProcess = new ApplyPricingRulesProcess();

            if ($ApplyPricingRulesProcess->is_queued()) {
                $message = _x('Please wait until previous update operation complete.', 'error text', 'ali2woo');
                throw new Exception($message);
            }

            if ($scope === 'all' || $scope === 'import') {
                $ProductImportModel = new ProductImport();
                $products_count = $ProductImportModel->get_products_count();

                $update_per_request = a2wl_check_defined('A2WL_UPDATE_PRODUCT_IN_IMPORTLIST_PER_REQUEST');
                $update_per_request = $update_per_request ? A2WL_UPDATE_PRODUCT_IN_IMPORTLIST_PER_REQUEST : 30;

                $import_page = -1;
                do {
                   $import_page++;
                    $products_id_list = $ProductImportModel->get_product_id_list($update_per_request, $update_per_request * $import_page);
                    if (!empty($products_id_list)) {
                        $ApplyPricingRulesProcess->pushToQueue(
                            $products_id_list,
                            ApplyPricingRulesProcess::SCOPE_IMPORT,
                            $type
                        );
                        unset($products_id_list);
                    }
                }  while (($import_page * $update_per_request + $update_per_request) < $products_count);
            }
            if ($scope === 'all' || $scope === 'shop') {
                /** @var $WoocommerceModel  Woocommerce */
                $WoocommerceModel = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');
                $update_per_request = a2wl_check_defined('A2WL_UPDATE_PRODUCT_PER_REQUEST');
                $update_per_request = $update_per_request ? A2WL_UPDATE_PRODUCT_PER_REQUEST : 5;

                $products_count = $WoocommerceModel->get_products_count();
                $page = -1;
                do {
                    $page++;
                    $product_ids = $WoocommerceModel->get_products_ids($page, $update_per_request);
                    if (!empty($product_ids)) {
                        $ApplyPricingRulesProcess->pushToQueue(
                            $product_ids,
                            ApplyPricingRulesProcess::SCOPE_SHOP,
                            $type
                        );
                        unset($product_ids);
                    }
                } while (($page * $update_per_request + $update_per_request) < $products_count);
            }

            $result = ResultBuilder::buildOk([
                'done' => 1,
                'info' => 'Please wait until prices will be updated.',
            ]);

            $ApplyPricingRulesProcess->dispatch();

            restore_error_handler();
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_calc_external_images_count(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $result = ResultBuilder::buildOk(array('total_images' => Attachment::calc_total_external_images()));

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_calc_external_images(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $page_size = isset($_POST['page_size']) && intval($_POST['page_size']) > 0 ? intval($_POST['page_size']) : 1000;
        $result = ResultBuilder::buildOk(array('ids' => Attachment::find_external_images($page_size)));

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_load_external_image(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        a2wl_init_error_handler();
        $attachment_model = new Attachment('local');
        $image_id = isset($_POST['id']) && intval($_POST['id']) > 0 ? intval($_POST['id']) : 0;

        if ($image_id) {
            try {
                $attachment_model->load_external_image($image_id);

                $result = ResultBuilder::buildOk();
            } catch (Throwable $e) {
                a2wl_print_throwable($e);
                $result = ResultBuilder::buildError($e->getMessage());
            }
        } else {
            $result = ResultBuilder::buildError("load_external_image: waiting for ID...");
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_reset_shipping_meta(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $result = ResultBuilder::buildOk();
        //remove saved shipping meta
        ProductShippingMeta::clear_in_all_product();

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_purchase_code_info(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $result = SystemInfo::server_ping();
        if ($result['state'] !== 'error') {
            $isFreeTariff = empty($result['tariff_code']) || $result['tariff_code'] === self::FREE_TARIFF_CODE;
            $result['tariff_name'] = $isFreeTariff ? 'Free' : ucfirst($result['tariff_code']);

            if ($isFreeTariff){
                //fix how we display limits in lite version
                $result['limits']['reviews'] = 0;
                $result['limits']['shipping'] = 0;
            }

            $valid_to = !empty($result['valid_to']) ? strtotime($result['valid_to']) : false;
            $tariff_to = !empty($result['tariff_to']) ? strtotime($result['tariff_to']) : false;

            $supported_until = ($valid_to && $tariff_to && $tariff_to > $valid_to) ? $tariff_to : $valid_to;

            if ($supported_until && $supported_until < time()) {
                $result['supported_until'] = "Support expired on " . gmdate("F j, Y", $supported_until);
            } else if ($supported_until) {
                $result['supported_until'] = gmdate("F j, Y", $supported_until);
            } else {
                $result['supported_until'] = "";
            }
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_build_aliexpress_api_auth_url(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $state = urlencode(trailingslashit(get_bloginfo('wpurl')));

        $result = [
            'state' => 'ok',
            'url' => $this->buildAuthEndpointUrl($state)
        ];
    
        

        echo wp_json_encode($result);
        wp_die();
    }

    private function buildAuthEndpointUrl(string $state): string
    {
        $authEndpoint = 'https://api-sg.aliexpress.com/oauth/authorize';
        $redirectUri = get_setting('api_endpoint').'auth.php&state=' . $state;
        $clientId = get_setting('client_id');

        return sprintf(
            '%s?response_type=code&force_auth=true&redirect_uri=%s&client_id=%s',
            $authEndpoint,
            $redirectUri,
            $clientId
        );
    }

    public function ajax_save_access_token(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $result = array('state' => 'error', 'message' => 'Wrong params');
        if (isset($_POST['token'])) {
            $token = AliexpressToken::getInstance();
            $token->add($_POST['token']);
			//todo: have to think about this method, perhaps it should be refactored
            Utils::clear_system_error_messages();

            $tokens = $token->tokens();
            $data = '';
            foreach ($tokens as $t) {
                $data .= '<tr>';
                $data .= '<td>' . esc_attr($t['user_nick']) . '</td>';
                $data .= '<td>' . esc_attr(gmdate("F j, Y, H:i:s", round($t['expire_time'] / 1000))) . '</td>';
                $data .= '<td><input type="checkbox" class="default" value="yes" ' . (isset($t['default']) && $t['default'] ? " checked" : "") . '/></td>';
                $data .= '<td><a href="#" data-token-id="' . $t['user_id'] . '">Delete</a></td>';
                $data .= '</tr>';
            }
            $result = array('state' => 'ok', 'data' => $data);
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_delete_access_token(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $result = array('state' => 'error', 'message' => 'Wrong params');
        if (isset($_POST['id'])) {
            $token = AliexpressToken::getInstance();
            $token->del($_POST['id']);
            $result = array('state' => 'ok');
        }

        echo wp_json_encode($result);
        wp_die();
    }
}
