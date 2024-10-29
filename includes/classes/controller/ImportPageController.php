<?php

/**
 * Description of ImportPageController
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_admin_init
 */

namespace AliNext_Lite;;

class ImportPageController extends AbstractAdminPage
{
    public function __construct()
    {
        $products_cnt = 0;
        if (is_admin()) {
            $product_import_model = new ProductImport();
            $products_cnt = $product_import_model->get_products_count();
        }

        parent::__construct(esc_html__('Import List', 'ali2woo'), esc_html__('Import List', 'ali2woo') . ' ' . ($products_cnt ? ' <span class="update-plugins count-' . $products_cnt . '"><span class="plugin-count">' . $products_cnt . '</span></span>' : ''), 'import', 'a2wl_import', 20);

        add_filter('tiny_mce_before_init', array($this, 'tiny_mce_before_init'), 30);
        add_filter('a2wl_configure_lang_data', array($this, 'configure_lang_data'), 30);
    }

    public function configure_lang_data($data){
        $data['attr_new_name'] = esc_html__('New name', 'ali2woo');
        $data['attr_name_duplicate_error'] = esc_html__('this name is already used', 'ali2woo');

        return $data;
    }

    public function before_admin_render(): void
    {
        if (!empty($_REQUEST['delete_id']) || !empty($_REQUEST['action']) || !empty($_REQUEST['action2'])) {
            check_admin_referer(self::PAGE_NONCE_ACTION, self::NONCE);
        }

        if (!current_user_can('manage_options')) {
            wp_die($this->getErrorTextNoPermissions());
        }

        $product_import_model = new ProductImport();
        if (isset($_REQUEST['delete_id']) && $_REQUEST['delete_id']) {
            if ($product = $product_import_model->get_product($_REQUEST['delete_id'])) {
                foreach ($product['tmp_edit_images'] as $edit_image) {
                    if (isset($edit_image['attachment_id'])) {
                        Utils::delete_attachment($edit_image['attachment_id'], true);
                    }
                }
                $product_import_model->del_product($_REQUEST['delete_id']);
            }
            wp_redirect(admin_url('admin.php?page=a2wl_import'));
        } else if ((isset($_REQUEST['action']) && $_REQUEST['action'] == "delete_all") || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == "delete_all")) {
            $product_ids = $product_import_model->get_product_id_list();

            foreach ($product_ids as $product_id) {
                if ($product = $product_import_model->get_product($product_id)) {
                    foreach ($product['tmp_edit_images'] as $edit_image) {
                        if (isset($edit_image['attachment_id'])) {
                            Utils::delete_attachment($edit_image['attachment_id'], true);
                        }
                    }
                }
            }

            $product_import_model->del_product($product_ids);

            wp_redirect(admin_url('admin.php?page=a2wl_import'));
        } else if ((isset($_REQUEST['action']) && $_REQUEST['action'] == "push_all") || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == "push_all")) {
            // push all
            wp_redirect(admin_url('admin.php?page=a2wl_import'));
        } else if (((isset($_REQUEST['action']) && $_REQUEST['action'] == "delete") || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == "delete")) && isset($_REQUEST['gi']) && is_array($_REQUEST['gi']) && $_REQUEST['gi']) {
            $product_import_model->del_product($_REQUEST['gi']);

            wp_redirect(admin_url('admin.php?page=a2wl_import'));
        }
    }

    public function render($params = []): void
    {
        if (!empty($_REQUEST['s']) || !empty($_REQUEST['o'])) {
            check_admin_referer(self::PAGE_NONCE_ACTION, self::NONCE);
        }

        if (!current_user_can('manage_options')) {
            wp_die($this->getErrorTextNoPermissions());
        }

        $product_import_model = new ProductImport();
        /** @var $woocommerce_model  Woocommerce */ 
        $woocommerce_model = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');
        $country_model = new Country();
        $override_model = new Override();

        $serach_query = !empty($_REQUEST['s']) ? $_REQUEST['s'] : '';
        $sort_query = !empty($_REQUEST['o']) ? $_REQUEST['o'] : $product_import_model->default_sort();

        $default_shipping_from_country = get_setting('aliship_shipfrom', 'CN');
        $default_shipping_to_country = get_setting('aliship_shipto', 'US');

        $products_cnt = $product_import_model->get_products_count();
        $paginator = Paginator::build($products_cnt);

        if (a2wl_check_defined('A2WL_SKIP_IMPORT_SORTING')) {
            $product_list = $product_import_model->get_product_list(
                true, $serach_query,
                $sort_query, $paginator['per_page'],
                ($paginator['cur_page'] - 1) * $paginator['per_page']
            );
        } else {
            $product_list_all = $product_import_model->get_product_list(true, $serach_query, $sort_query);
            $product_list = array_slice(
                $product_list_all,
                $paginator['per_page'] * ($paginator['cur_page'] - 1),
                $paginator['per_page']
            );
            unset($product_list_all);
        }
        foreach ($product_list as &$product) {
            if (empty($product['sku_products'])) {
                $product['sku_products'] = [
                    'variations' => [],
                    'attributes' => []
                ];
            }

            $tmp_all_images = Utils::get_all_images_from_product($product);

            if (empty($product['description'])) {
                $product['description'] = '';
            }

            $product['gallery_images'] = [];
            $product['variant_images'] = [];
            $product['description_images'] = [];

            foreach ($tmp_all_images as $img_id => $img) {
                if ($img['type'] === 'gallery') {
                    $product['gallery_images'][$img_id] = $img['image'];
                } else if ($img['type'] === 'variant') {
                    $product['variant_images'][$img_id] = $img['image'];
                } else if ($img['type'] === 'description') {
                    $product['description_images'][$img_id] = $img['image'];
                }
            }
            foreach ($product['tmp_copy_images'] as $img_id => $source) {
                if (isset($tmp_all_images[$img_id])) {
                    $product['gallery_images'][$img_id] = $tmp_all_images[$img_id]['image'];
                }
            }

            foreach ($product['tmp_move_images'] as $img_id => $source) {
                if (isset($tmp_all_images[$img_id])) {
                    $product['gallery_images'][$img_id] = $tmp_all_images[$img_id]['image'];
                }
            }

            if (!isset($product['thumb_id']) && $product['gallery_images']) {
                $k = array_keys($product['gallery_images']);
                $product['thumb_id'] = $k[0];
            }
        }

        $product_links = [];

        foreach ($product_list as $index => $item) {
            $product_links[$index]['remove_product_link'] =
                $this->getRemoveProductLink($_REQUEST['page'], $item);

            if (!empty($item['store_id']) && !empty($item['seller_id'])) {
                $product_links[$index]['find_all_products_in_store_link'] =
                    $this->getFindAllProductInStoreLink($item);
            }
        }

        $links = [
            'remove_all_products_link' =>
                $this->getRemoveAllProductsLink($_REQUEST['page'])
        ];


        $this->model_put("paginator", $paginator);
        $this->model_put("serach_query", $serach_query);
        $this->model_put("sort_query", $sort_query);
        $this->model_put("sort_list", $product_import_model->sort_list());
        $this->model_put("product_list", $product_list);
        $this->model_put("product_links", $product_links);
        $this->model_put("links", $links);
        $this->model_put("localizator", AliexpressLocalizator::getInstance());
        $this->model_put("categories", $woocommerce_model->get_categories());
        $this->model_put('countries', $country_model->get_countries());
        $this->model_put('override_model', $override_model);
        
        $promoModel = Promo::getInstance();
        $this->model_put('promo_data', $promoModel->getPromoData());
        

        $this->include_view("import.php");
    }

    public function tiny_mce_before_init($initArray)
    {
        if ($this->is_current_page()) {
            $initArray['setup'] = 'function(ed) {ed.on("change", function(e) { a2wl_update_product(e.target.id, { description:encodeURIComponent(e.target.getContent())}); });}';
        }
        return $initArray;
    }

    private function getFindAllProductInStoreLink(array $product): string
    {
        $url = admin_url('admin.php?page=a2wl_store') .
            '&a2wl_store_id=' . $product['store_id'] .
            '&a2wl_seller_id=' . $product['seller_id'] .
            '&a2wl_search=1';

        return wp_nonce_url($url, self::PAGE_NONCE_ACTION, self::NONCE);
    }

    private function getRemoveProductLink(string $page, array $product): string
    {
        $url =  admin_url('admin.php?page=' . $page) .
            '&delete_id=' . $product['import_id'];

        return wp_nonce_url($url, self::PAGE_NONCE_ACTION, self::NONCE);
    }

    private function getRemoveAllProductsLink(string $page): string
    {
        $url = admin_url('admin.php?page=' . $page) .
            '&action=delete_all';

        return wp_nonce_url($url, self::PAGE_NONCE_ACTION, self::NONCE);
    }

}

