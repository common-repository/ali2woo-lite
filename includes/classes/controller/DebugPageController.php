<?php

/**
 * Description of DebugPageController
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_before_admin_menu
 */

namespace AliNext_Lite;;

class DebugPageController extends AbstractAdminPage
{

    public function __construct()
    {
        if (a2wl_check_defined('A2WL_DEBUG_PAGE')) {
            parent::__construct(esc_html__('Debug', 'ali2woo'), esc_html__('Debug', 'ali2woo'), 'edit_plugins', 'a2wl_debug', 1100);
        }
    }

    public function render($params = array())
    {
        echo "<br/><b>DEBUG</b><br/>";
    }

}
