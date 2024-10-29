<?php
// phpcs:ignoreFile WordPress.DB.PreparedSQL.InterpolatedNotPrepared
/**
 * Description of BaseJob
 *
 * @author Ali2Woo Team
 *
 * @position: 1
 */

namespace AliNext_Lite;;

use AliNext_Lite\Library\BackgroundProcessing\WP_Background_Process;

abstract class BaseJob extends WP_Background_Process implements BaseJobInterface
{
    protected string $title = 'Base Job';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSize(): int
    {
        global $wpdb;

        $table  = $wpdb->options;
        $column = 'option_name';

        if ( is_multisite() ) {
            $table  = $wpdb->sitemeta;
            $column = 'meta_key';
        }

        $key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

        $count = $wpdb->get_var( $wpdb->prepare( "
        SELECT COUNT(*)
        FROM {$table}
        WHERE {$column} LIKE %s
        ", $key ) );

        return $count;
    }

    public function getName(): string
    {
        return $this->action;
    }

    public function isQueued(): bool
    {
        return parent::is_queued();
    }

    public function isCancelled(): bool
    {
        return parent::is_cancelled();
    }

    public function cancel(): void
    {
        parent::cancel();
    }
}
