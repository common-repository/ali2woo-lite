<?php
// phpcs:ignoreFile WordPress.DB.PreparedSQL.InterpolatedNotPrepared
/**
 * Description of ImportProcess
 *
 * @author Ali2Woo Team
 *
 */

namespace AliNext_Lite;;

use Exception;
use Throwable;
use function AliNext_Lite\get_setting;

class ImportProcess extends BaseJob implements ImportJobInterface
{

    public const ACTION_CODE = 'a2wl_import_process';
    
    protected $action = self::ACTION_CODE;
    protected string $title = 'Import Product';

    /**
     * Task
     *
     * @param mixed $item Queue item to iterate over
     *
     * @return bool
     */
    protected function task( $item ): bool
    {
        a2wl_init_error_handler();
        try {
            $ts = microtime(true);
            a2wl_info_log("START_STEP[id:".$item['product_id'].", extId: ".$item['id'].", step: ".$item['step']."]");

            if (str_starts_with($item['step'], 'reviews')) {
                if (get_setting('load_review')) {
                    $reviews_model = new Review();

                    $result = $reviews_model->load($item['product_id'], true, array('step'=>$item['step']));

                    if (!empty($result['new_steps'])) {
                        // add new steps to new queue
                        ImportProcess::create_new_queue($item['product_id'], $item['id'], $result['new_steps'], false);
                    }

                    if ($result['state'] === 'error') {
                        throw new Exception($result['message']);
                    }
                }
            } else {
                /** @var $woocommerce_model  Woocommerce */ 
                $woocommerce_model = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');
                $product_import_model = new ProductImport();

                $product = $product_import_model->get_product($item['id'], true);

                unset($product_import_model);

                if ($product) {
                    $result = $woocommerce_model->add_product($product, $item);

                    unset($woocommerce_model, $product);

                    if (!empty($result['new_steps'])) {
                        // add new steps to new queue
                        ImportProcess::create_new_queue($item['product_id'], $item['id'], $result['new_steps']);
                    }

                    if ($result['state'] === 'error') {
                        throw new Exception($result['message']);
                    }
                } else {
                    throw new Exception('product not found in import list');
                }    
            }

            a2wl_info_log("DONE_STEP[time: ".(microtime(true)-$ts).", id:".$item['product_id'].", extId: ".$item['id'].", step: ".$item['step']."]");
            
        } catch (Throwable $e) {
            a2wl_print_throwable($e);
        }

        return false;
    }

    //todo: remove this function and refactor client code (use pushToQueue method instead)
    public static function create_new_queue($product_id, $external_id, $steps, $start = true): self
    {
        $new_queue = new ImportProcess();
        foreach($steps as $step) {
            $new_queue->push_to_queue(array('id'=>$external_id, 'step'=>$step, 'product_id'=>$product_id));
            a2wl_info_log("ADD_STEP[id:".$product_id.", extId: ".$external_id.", step: ".$step."]");
        }
        $new_queue->save();
        if($start) {
            $new_queue->dispatch();
        }
        return $new_queue;
    }

    //todo: refactor this function (maybe add to BaseJob)
    public function clean_queue() {
        global $wpdb;

        $table        = $wpdb->options;
        $column       = 'option_name';
        $key_column   = 'option_id';
        $value_column = 'option_value';

        if ( is_multisite() ) {
            $table        = $wpdb->sitemeta;
            $column       = 'meta_key';
            $key_column   = 'meta_id';
            $value_column = 'meta_value';
        }

        $key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

        $query = $wpdb->get_results( $wpdb->prepare( "
        SELECT *
        FROM {$table}
        WHERE {$column} LIKE %s
        ORDER BY {$key_column} ASC
        ", $key ) );

        foreach ( $query as $row ) {
            $this->delete( $row->$column );
        }
    }

    public function pushToQueue(int $product_id, int $external_id, array $steps, bool $start = true): ImportJobInterface
    {
        // TODO: Implement pushToQueue() method.
    }
}
