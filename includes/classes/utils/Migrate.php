<?php

/**
 * Description of Migrate
 *
 * @author Ali2Woo Team
 * 
 * @autoload: a2wl_admin_init
 */

namespace AliNext_Lite;;

class Migrate
{
    public function __construct() {
        $this->migrate();
    }
    
    public function migrate(): void
    {
        $cur_version = get_option('a2wl_db_version', '');
        if (version_compare($cur_version, "3.0.8", '<')) {
            $this->migrate_to_308();
        }

        if (version_compare($cur_version, A2WL()->version, '<')) {
            update_option('a2wl_db_version', A2WL()->version, 'no');
        }
    }

    private function migrate_to_308(): void
    {
        a2wl_error_log('migrate to 3.0.8');
        ProductShippingMeta::clear_in_all_product();;
    }
}
