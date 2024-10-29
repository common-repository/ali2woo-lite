<?php

/**
 * Description of ApplyPricingRulesProcess
 *
 * @author Ali2Woo Team
 *
 */

namespace AliNext_Lite;;

use Exception;
use Throwable;

class ApplyPricingRulesProcess extends BaseJob implements ApplyPricingRulesJobInterface
{
    public const SCOPE_IMPORT = 'import';
    public const SCOPE_SHOP = 'shop';
    public const ACTION_CODE = 'a2wl_apply_pricing_rules_process';

    protected $action = self::ACTION_CODE;
    protected string $title = 'Apply Pricing Rules';

    /**
     * @throws Exception
     */
    public function pushToQueue(array $productIds, string $scope, string $type): self
    {
        $this->push_to_queue([
            'productIds' => $productIds,
            'scope'=> $scope,
            'type' => $type,
        ]);
        $this->save();
        $size = $this->getSize();
        a2wl_info_log(sprintf(
            "Add new job: %s [size: %d; product ids: %s; scope: %s]",
            $this->getTitle(), $size, implode(', ', $productIds), $scope
        ));

        return $this;
    }

    protected function task($item): bool
    {
        a2wl_init_error_handler();
        try {
            $PriceFormulaService = A2WL()->getDI()->get('AliNext_Lite\PriceFormulaService');
            $timeStart = microtime(true);
            $scope = $item['scope'];
            $productIds = $item['productIds'];
            $type = $item['type'];
            a2wl_info_log(sprintf(
                "Start job: %s [product ids: %s; scope: %s]",
                $this->getTitle(), implode(', ', $productIds), $scope
            ));

            if ($item['scope'] === self::SCOPE_IMPORT) {
                $ProductImportModel = new ProductImport();
                foreach ($productIds as $product_id) {
                    $product = $ProductImportModel->get_product($product_id);
                    if (empty($product)) {
                        a2wl_info_log(sprintf(
                            "Process job: %s, Skip product id: %s (because no external data available); scope: %s]",
                            $this->getTitle(), $product_id, $scope
                        ));
                        continue;
                    }
                    if (!isset($product['disable_var_price_change']) || !$product['disable_var_price_change']) {
                        $product = $PriceFormulaService->applyFormula($product, 2, $type);
                        $ProductImportModel->upd_product($product);
                    } else {
                        a2wl_info_log(sprintf(
                            "Process job: %s, Skip product id: %s (because 'Disable variations price change' active for the product); scope: %s]",
                            $this->getTitle(), $product_id, $scope
                        ));
                        continue;
                    }
                    unset($product);
                }
            } elseif ($item['scope'] === self::SCOPE_SHOP) {
                /** @var Woocommerce $WoocommerceModel  */
                $WoocommerceModel = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');
                foreach ($productIds as $product_id) {
                    $product = $WoocommerceModel->get_product_by_post_id($product_id);
                    if (empty($product)) {
                        a2wl_info_log(sprintf(
                            "Process job: %s, skip product id: %s (because no external data available); scope: %s]",
                            $this->getTitle(), $product_id, $scope
                        ));
                        continue;
                    }
                    if (!isset($product['disable_var_price_change']) || !$product['disable_var_price_change']) {
                        $product = $PriceFormulaService->applyFormula($product, 2, $type);
                        if (isset($product['sku_products']['variations']) && count($product['sku_products']['variations']) > 0) {
                            $WoocommerceModel->update_price($product_id, $product['sku_products']['variations'][0]);
                            foreach ($product['sku_products']['variations'] as $var) {
                                $variation_id = get_posts(
                                    [
                                        'post_type' => 'product_variation',
                                        'fields' => 'ids',
                                        'numberposts' => 100,
                                        'post_parent' => $product_id,
                                        'meta_query' => [
                                            ['key' => 'external_variation_id', 'value' => $var['id']]
                                        ]
                                    ]
                                );
                                $variation_id = $variation_id ? $variation_id[0] : false;
                                if ($variation_id) {
                                    $WoocommerceModel->update_price($variation_id, $var);
                                }
                            }
                            wc_delete_product_transients($product_id);
                        }
                    } else {
                        a2wl_info_log(sprintf(
                            "Process job: %s, Skip product id: %s (because 'Disable variations price change' active for the product); scope: %s]",
                            $this->getTitle(), $product_id, $scope
                        ));
                        continue;
                    }
                    unset($product);
                }
            }

            $size = $this->getSize();
            $time = microtime(true)-$timeStart;
            a2wl_info_log(sprintf(
                "Done job: %s [time: %s, size: %d, product ids: %s; scope: %s]",
                $this->getTitle(), $time, $size, implode(', ', $productIds), $scope
            ));
        }
        catch (Throwable $Exception) {
            a2wl_print_throwable($Exception);
        }

        return false;
    }

}
