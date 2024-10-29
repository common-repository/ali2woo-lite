<?php
/**
 * Description of BlankConverter
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_admin_init
 */

namespace AliNext_Lite;;

class BlankConverter extends AbstractAdminPage
{
    public function __construct()
    {
        if (!apply_filters('a2wl_converter_installed', false)) {
            parent::__construct(
                    esc_html__('Migration Tool', 'ali2woo'),
                    esc_html__('Migration Tool', 'ali2woo'),
                    'import',
                    'a2wl_converter',
                    1000
            );
        }
    }

    public function render($params = [])
    {
        ?>
        <h1>Migration Tool</h1>
        <p>The conversion plugin is not installed.</p>
        <p><a href="#">Download and install plugin</a></p>
        <?php
    }
}
