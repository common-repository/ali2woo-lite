<?php
/**
 * Description of HelpController
 *
 * @author Ali2Woo Team
 * 
 * @autoload: a2wl_admin_init
 */

namespace AliNext_Lite;;

class HelpController {

    public $tab_class = '';
    public $tab_id = '';
    public $tab_title = '';
    public $tab_icon = '';

    public function __construct() {    
        add_action('a2wl_init_admin_menu', array($this, 'add_submenu_page'), 200);  
    }

    public function add_submenu_page($parent_slug)
    {
        add_submenu_page(
            $parent_slug, '', 'Help',
            'manage_options', 'https://help.ali2woo.com/alinext-kb/'
        );
    }
}
