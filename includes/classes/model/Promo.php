<?php

/**
 * Description of Promo
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class Promo
{
    private static ?Promo $_instance = null;

    public array $promo_data = [];

    public static function getInstance(): ?Promo
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function __construct()
    {
        $this->promo_data = [
            'full_plugin_link' => 'https://ali2woo.com/pricing/',
            'promo_image_url' => A2WL()->plugin_url() . '/assets/img/alinext-plugin-box-268.jpg',
            'title' => 'AliNext full version',
            'description' => '<ul style="list-style: decimal;">' .
            '<li><strong>All features from the free version:</strong> All your settings/imported products/fulfilled orders are remained after upgrade</li>' .
            '<li><strong>Premium support</strong></li>' .
            '<li><strong>Premium plugin updates</strong></li>' .
            '<li><strong>Increased daily usage quota:</strong> Instead of 100 quota, you will get 1,500 or 5,500, or 25,500 depending on AliNext (Lite version)`s package you order</li>' .
            '<li><strong>Import full category tree from Aliexpress</strong> for your products with increased daily limits. Lite version allows to make only 5 category requests per day.</li>' .
            '<li><strong>Order fulfillment using API:</strong> Place unlimited orders on AliExpress through the AliExpress API. In contrast to AliNext (Lite version) Lite allowing you to place only one order using the API.</li>' .
            '<li><strong>Order Sync using API:</strong> Sync unlimited orders AliExpress through the AliExpress API. In contrast to AliNext (Lite version) Lite allowing you to sync only one order using the API.</li>' .
            '<li><strong>Frontend shipping:</strong> Instead of importing product with specific shipping cost, you can allow customers to select shipping company based on their country just like shopping on AliExpress. Shipping companies can be masked to hide the fact that you work as dropshipper.</li>' .
            '<li><strong>Automatically update product price and quantity:</strong> Product price and quantity can now be synced with AliExpress automatically using CRON. If a product is out of stock/change price or is offline, you will receive email notification. You can also check the progress in the log file.</li>' .
            '<li><strong>Automatically update product reviews:</strong> When new reviews appear on AliExpress, the plugin adds them automatically to already imported products.</li>' .
            '<li><strong>Shipping markup:</strong> Add separate pricing rules for shipping options imported from AliExpress</li></ul>',
            'local_price' => "12.00",
            'local_regular_price' => "24.00",
            'currency' => 'USD',
            'evaluateScore' => 4.8,
            'purchases' => 1265,
            'button_cta' => 'Get full version'
        ];
    }

    public function getPromoData(): array
    {
        return $this->promo_data;
    }

}
