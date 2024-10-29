<?php

/**
 * Description of SystemInfo
 *
 * @author Ali2Woo Team
 * 
 * @autoload: a2wl_admin_init
 * 
 * @ajax: true
 */

namespace AliNext_Lite;;

class SystemInfo
{
    public function __construct() {
        add_action('wp_ajax_a2wl_ping', array($this, 'ajax_ping'));
        add_action('wp_ajax_nopriv_a2wl_ping', array($this, 'ajax_ping'));
        add_action('wp_ajax_a2wl_clear_log_file', array($this, 'ajax_clear_log_file'));
        add_action('wp_ajax_a2wl_clean_import_queue', [$this, 'ajax_clean_import_queue']);
        add_action('wp_ajax_a2wl_run_cron_import_queue', [$this, 'ajax_run_cron']);
    }

    public function ajax_clear_log_file() {
        Logs::getInstance()->delete();
        echo wp_json_encode(array('state'=>'ok'));
        wp_die();
    }

    public function ajax_clean_import_queue(): void
    {
        $import_process = new ImportProcess();
        $import_process->clean_queue();

        echo wp_json_encode(['state'=>'ok']);
        wp_die();
    }

    public function ajax_run_cron(): void
    {
        $import_process = new ImportProcess();
        $import_process->dispatch();

        echo wp_json_encode(['state'=>'ok']);
        wp_die();
    }

    public function ajax_ping() {
        echo wp_json_encode(array('state'=>'ok'));
        wp_die();
    }

    public static function ping(): ?array
    {
        $args = [
            'cookies' => $_COOKIE,
        ];

        $request = wp_remote_post( admin_url('admin-ajax.php')."?action=a2wl_ping", $args);

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

    public static function server_ping(): ?array
    {
        $ping_url = RequestHelper::build_request('ping', ['r' => wp_rand()]);
        $request = a2wl_remote_get($ping_url);

        if (is_wp_error($request)) {
            // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
            if (file_get_contents($ping_url)) {
                $result = ResultBuilder::buildError('a2wl_remote_get error');
            } else {
                $result = ResultBuilder::buildError($request->get_error_message());
            }
        } else if (intval($request['response']['code']) != 200) {
            $result = ResultBuilder::buildError(
                $request['response']['code']." ".$request['response']['message']
            );
        } else {
            $result = json_decode($request['body'], true);
        }

        return $result;
    }
    
    public static function php_check(){
        return ResultBuilder::buildOk();
    }

    public static function php_dom_check(){
        if (class_exists('DOMDocument')) {
            return ResultBuilder::buildOk();
        } else{
            return ResultBuilder::buildError('PHP DOM is disabled');
        }
    }
}
