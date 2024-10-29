<?php

/**
 * Description of ReviewBackendController
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

class ReviewBackendController extends AbstractController
{

    private $upd_rvws_task_id = "a2wl_product_update_reviews_manual";
    
    function __construct() {
        parent::__construct();

        add_action('admin_enqueue_scripts', [$this, 'assets']);

        add_action('wp_ajax_a2wl_arvi_remove_reviews', [$this, 'ajax_remove_all_reviews']);

        add_action('wp_ajax_a2wl_arvi_remove_product_reviews', [$this, 'ajax_remove_product_reviews']);
        add_action('wp_ajax_a2wl_arvi_get_comment_photos', [$this, 'ajax_get_comment_photos']);
        add_action('wp_ajax_a2wl_arvi_save_comment_photos', [$this, 'ajax_save_comment_photos']);

        add_filter('a2wl_ajax_product_info', [$this, 'product_info'], 4, 10);

        //todo: this doesn't work for new Woocommerce, because they moved reviews to a separate section
        add_filter('comment_row_actions', [$this, 'row_actions'], 10, 2);

        if (is_admin()) {
            // add bulk action to product list
            add_filter('a2wl_wcpl_bulk_actions_init', [$this, 'bulk_actions_init']);
        }
    }

    public function assets(): void
    {
        $current_screen = get_current_screen();

        if ($current_screen->id === "product" || $current_screen->id === "edit-comments") {
            wp_enqueue_style(
                'a2wl-review-comment-widget-style',
                A2WL()->plugin_url() . '/assets/css/review/comment_widget.css',
                [],
                A2WL()->version
            );
            wp_enqueue_script(
                'a2wl-review-comment-widget-script',
                A2WL()->plugin_url() . '/assets/js/review/comment_widget.js',
                [],
                A2WL()->version,
                true
            );
            
            $data = [
                'current_page' => $current_screen->id,
                'i18n_please_wait' => 'Please wait...',
                'i18n_done' => 'Done!',
                'i18n_error_occur' => 'Server error occurred!',
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce_action' => wp_create_nonce(self::AJAX_NONCE_ACTION),
            ];
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            if ($current_screen->id === "product" && isset($_GET['post'])) {
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                $data['product_id'] = intval($_GET['post']);
            }

            wp_localize_script('a2wl-review-comment-widget-script', 'WPDATA', $data);
        }
    }

    public function row_actions($actions, $comment) {
        if (Review::get_comment_photos($comment->comment_ID)) {
            $actions = array_merge($actions, array('a2wl_comment_edit_photo_link' => sprintf('<a id="a2wl-%1$d" href="#">%2$s</a>', $comment->comment_ID, 'Edit Photos')));
        }
        return $actions;
    }

    function bulk_actions_init($bulk_actions_array) {
        if (get_setting('load_review')) {
            $bulk_actions_array[0][] = $this->upd_rvws_task_id;
            $bulk_actions_array[1][$this->upd_rvws_task_id] = 'Update reviews';
        }

        return $bulk_actions_array;
    }

    public function product_info($content, $post_id, $external_id) {
        $time_value = get_post_meta($post_id, '_a2w_reviews_last_update', true);
        $time_value = $time_value ? gmdate("Y-m-d H:i:s", $time_value) : 'not loaded';

        $content[] = "Reviews update: <span class='a2wl_value'>" . $time_value . "</span>";

        return $content;
    }

    public function ajax_remove_all_reviews(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        a2wl_init_error_handler();
        $result = ResultBuilder::buildOk();

        try {
            $comments = Review::get_all_review_ids();
            Review::remove_reviews_by_ids($comments);

            restore_error_handler();
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_remove_product_reviews(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        $result = ResultBuilder::buildOk();
        
        $post_id = isset($_POST['id']) ? $_POST['id'] : false;
        
        if (!$post_id) {
            echo wp_json_encode(ResultBuilder::buildError("Product related with this ID not found"));
            wp_die();
        }
        
        a2wl_init_error_handler();
        try {
            $comments = Review::get_product_review_ids($post_id);
            Review::remove_reviews_by_ids($comments);
            restore_error_handler();
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
            $result = ResultBuilder::buildError($e->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();    
    }

    public function ajax_get_comment_photos(): void
    {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        $result = ["state" => "ok", "message" => ""];

        $comment_id = isset($_POST['id']) ? $_POST['id'] : 0;
        $photos = Review::get_comment_photos($comment_id);
        if ($photos) {
            $result['photos'] = $photos;
        } else {
            $result['state'] = 'error';
            $result['message'] = 'No photos available';
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajax_save_comment_photos(): void {
        check_admin_referer(self::AJAX_NONCE_ACTION, self::NONCE);

        $result = ["state" => "ok", "message" => ""];

        $comment_id = isset($_POST['id']) ? intval($_POST['id']) : false;
        $photos = isset($_POST['photos']) ? $_POST['photos'] : [];

        if (is_numeric($comment_id)) {
            $photos = $this->normalizePhotoArray($photos);
            Review::save_comment_photos($comment_id, $photos);
        } else {
            $result['state'] = 'error';
            $result['message'] = _x(
                'Comment id is not provided', 'error text', 'ali2woo'
            );
        }

        echo wp_json_encode($result);
        wp_die();
    }
    
    private function normalizePhotoArray($photo_array): array
    {
        $result = [];
        foreach ($photo_array as $photo){
            $result[] =  $photo['photo_id'];   
        }

        return $result;  
    }

}
