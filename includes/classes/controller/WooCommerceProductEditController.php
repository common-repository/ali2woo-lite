<?php

/* * class
 * Description of WooCommerceProductEditController
 *
 * @author Ali2Woo Team
 * 
 * @autoload: a2wl_admin_init
 * 
 * @ajax: true
 */

namespace AliNext_Lite;;

class WooCommerceProductEditController extends AbstractController
{

    public function __construct() {
        parent::__construct();

        add_action('current_screen', [$this, 'current_screen']);
        add_action('edit_form_advanced', array($this, 'edit_form_advanced'));
        add_action('a2wl_after_import', array($this, 'edit_form_advanced'));
        add_action('wp_ajax_a2wl_get_image_by_id', [$this, 'ajax_get_image_by_id']);
        
        
        add_action('wp_ajax_a2wl_edit_image_url', [$this, 'ajax_edit_image_url']);

        add_filter('get_sample_permalink_html', array($this, 'get_sample_permalink_html'), 10, 2);
    }

    public function get_sample_permalink_html($return, $id ){
        $return .= '<button type="button" data-id="' .
            $id .
            '" class="sync-ali-product button button-small hide-if-no-js">' .
            esc_html__("AliExpress Sync", 'ali2woo') .
            '</button>';

        return $return;
    }

    function current_screen($current_screen): void
    {
        $importPageScreenId = A2WL()->plugin_slug . '_page_a2wl_import';
        $checkPage = $current_screen->id == 'product' || $current_screen->id == $importPageScreenId;
        if ($current_screen->in_admin() && $checkPage) {
            if (!wp_script_is('a2wl-admin-script', 'enqueued')) {
                wp_enqueue_script('a2wl-admin-script',
                    A2WL()->plugin_url() . '/assets/js/admin_script.js',
                    ['jquery'],
                    A2WL()->version
                );
            }

            AbstractAdminPage::localizeAdminScript();
            
            $lang_data = [
                /* translators: %d is replaced with "digit" */
                'process_loading_d_of_d_erros_d' => esc_html_x(
                    'Process loading $d of $d. Errors: $d.',
                    'Status',
                    'ali2woo'
                ),
                /* translators: %d is replaced with "digit" */
                'load_button_text' => esc_html_x('Load %d images', 'Status', 'ali2woo'),
                'all_images_loaded_text' => esc_html_x('All images loaded', 'Status', 'ali2woo'),
            ];
            wp_localize_script(
                'a2wl-admin-script',
                'a2wl_external_images_data',
                ['lang' => $lang_data]
            );

            $lang_data = [
                'sync_successfully' => esc_html_x('Synchronized successfully.', 'Status', 'ali2woo'),
                'sync_failed' => esc_html_x('Sync failed.', 'Status', 'ali2woo'),
            ];
            wp_localize_script('a2wl-admin-script', 'a2wl_sync_data', ['lang' => $lang_data]);


            wp_enqueue_style(
                'a2wl-admin-style',
                A2WL()->plugin_url() . '/assets/css/admin_style.css',
                [],
                A2WL()->version
            );

            wp_enqueue_style(
                'a2wl-wc-spectrum-style',
                A2WL()->plugin_url() . '/assets/js/spectrum/spectrum.css',
                [],
                A2WL()->version
            );

            wp_enqueue_script(
                'a2wl-wc-spectrum-script',
                A2WL()->plugin_url() . '/assets/js/spectrum/spectrum.js',
                [],
                A2WL()->version
            );

            wp_enqueue_script(
                'tui-image-editor-FileSaver',
                A2WL()->plugin_url() . '/assets/js/image-editor/FileSaver.min.js',
                ['jquery'],
                A2WL()->version)
            ;
            wp_enqueue_script(
                'tui-image-editor',
                A2WL()->plugin_url() . '/assets/js/image-editor/tui-image-editor.js',
                ['jquery'],
                A2WL()->version
            );

            wp_enqueue_script(
                'a2wl-wc-pe-script',
                A2WL()->plugin_url() . '/assets/js/wc_pe_script.js',
                [],
                A2WL()->version
            );
            wp_enqueue_style(
                'a2wl-wc-pe-style',
                A2WL()->plugin_url() . '/assets/css/wc_pe_style.css',
                [],
                A2WL()->version
            );

            $data = [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce_action' => wp_create_nonce(self::AJAX_NONCE_ACTION),
            ];
            wp_localize_script('a2wl-wc-pe-script', 'a2wl_wc_pe_script', $data);
        }
    }

    function edit_form_advanced($post)
    {
        $current_screen = get_current_screen();
        $importPageScreenId = A2WL()->plugin_slug . '_page_a2wl_import';
        $checkPage = $current_screen->id == 'product' || $current_screen->id == $importPageScreenId;
        if ($current_screen && $current_screen->in_admin() && $checkPage) {
            $srickers = get_setting('image_editor_srickers', []);
            
            foreach($srickers as $key=>$sricker){
                if(substr($sricker, 0, strlen("http")) !== "http"){
                    $srickers[$key] = A2WL()->plugin_url().$sricker;
                }
            }
            
            $this->model_put('srickers', $srickers);
            $this->include_view('product_edit_photo.php');
        }
    }
    
    function ajax_get_image_by_id(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        if (empty($_POST['attachment_id'])) {
            $result = ResultBuilder::buildError("waiting for attachment_id...");
        } else {
            $image_url = wp_get_attachment_url($_POST['attachment_id']);
            if (!$image_url){
                $result = ResultBuilder::buildError("waiting for attachment_id...");
            } else{
                $result = ResultBuilder::buildOk(array('image_url' => $image_url));
            }
        }

        echo wp_json_encode($result);
        wp_die();
    }
    
    
    function ajax_edit_image_url(): void
    {
        $result = ResultBuilder::buildOk();

        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        if (empty($_POST['url'])) {
            $result = ResultBuilder::buildError("waiting url...");
        } else {
            $result = ResultBuilder::buildOk([
                'url' => a2wl_image_url($_POST['url'])
            ]);
        }

        echo wp_json_encode($result);
        wp_die();
    }

}
