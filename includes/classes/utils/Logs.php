<?php

/**
 * Description of Logs
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

use Throwable;

class Logs {

    private static $_instance = null;

    private $a2wl_logs_file = '/ali2woo/a2wl_debug.log';

    protected function __construct() {
        if (get_setting('write_info_log')) {
            $upload_dir = wp_upload_dir();
            $log_file_parts = pathinfo($this->a2wl_logs_file);

            $a2wl_logs_dir = $upload_dir['basedir'].$log_file_parts['dirname'];
            $a2wl_logs_file = $a2wl_logs_dir.'/'.$log_file_parts['basename'];
        
            if (!file_exists($a2wl_logs_dir)) {
                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
                mkdir($a2wl_logs_dir, 0755, true);
            }

            if (!file_exists($a2wl_logs_file)) {
                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
                $fp = fopen($a2wl_logs_file, 'w');
                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
                fclose($fp);
                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_chmod
                chmod($a2wl_logs_file, 0644);
            }
        }
    }

    protected function __clone() {
        
    }

    static public function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function write($message): void
    {
        if(get_setting('write_info_log')){
            $ft = false;
            try {
                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
                $fp = fopen($this->log_path(), 'a');
                // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite
                fwrite($fp, $message."\r\n");
            } catch (Throwable $e) {
                error_log($e->getTraceAsString());
            } finally {
                if ($fp) {
                    // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
                    fclose($fp);
                }
            }
        }
    }

    public function delete(): void
    {
        // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
        unlink($this->log_path());
    }

    public function log_path(): string
    {
        $upload_dir = wp_upload_dir();

        return $upload_dir['basedir'] . $this->a2wl_logs_file;
    }

    public function log_url(): string
    {
        $upload_dir = wp_upload_dir();

        return $upload_dir['baseurl'] . $this->a2wl_logs_file;
    }

}
