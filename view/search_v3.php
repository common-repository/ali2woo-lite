<?php
/**
 * @var string $page
 * @var string $curPage
 */
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div class="a2wl-content">
    <div class="page-main">
        <div class="_a2wfo a2wl-info"><div>You are using AliNext (Lite version) Lite. If you want to unlock all features and get premium support, purchase the full version of the plugin.</div><a href="https://ali2woo.com/pricing/?utm_source=lite&utm_medium=lite&utm_campaign=alinext-lite" target="_blank" class="btn">GET FULL VERSOIN</a></div>
        <?php use AliNext_Lite\AbstractAdminPage;

        include_once A2WL()->plugin_path() . '/view/chrome_notify.php';?>
        

        <?php include_once A2WL()->plugin_path() . '/view/setup_wizard_notify.php';?>
        <form class="search-panel" method="GET" id="a2wl-search-form">
            <?php wp_nonce_field(AbstractAdminPage::PAGE_NONCE_ACTION, AbstractAdminPage::NONCE); ?>
            <input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
            <input type="hidden" name="cur_page" id="cur_page" value="<?php echo $curPage; ?>" />
            <input type="hidden" name="a2wl_sort" id="a2wl_sort" value="<?php echo $filter['sort']; ?>" />
            <input type="hidden" name="a2wl_search" id="a2wl_search" value="1" />
            <input type="hidden" id="a2wl_locale" value="<?php echo $locale; ?>" />
            <input type="hidden" id="a2wl_currency" value="<?php echo $currency; ?>" />
            <input type="hidden" id="a2wl_chrome_ext_import" value="<?php echo a2wl_check_defined('A2WL_CHROME_EXT_IMPORT'); ?>" />
            <input type="hidden" id="a2wl_chrome_url" value="<?php echo A2WL()->chrome_url; ?>" />

            <div class="search-panel-header">
                <h3 class="search-panel-title"><?php  esc_html_e('Search for products', 'ali2woo');?></h3>
                <div class="upload-icon to-right _a2wfv" type="button" title="<?php  esc_html_e('Import from CSV', 'ali2woo');?>">
                    <input id="upload-csv" type="file" class="upload-icon__input" name="import_csv">
                    <label for="upload-csv" class="upload-icon__label">
                        <svg class="upload-icon__icon icon-csv" viewBox="-4 0 64 64">
                            <path d="M5.106 0c-2.802 0-5.073 2.272-5.073 5.074v53.841c0 2.803 2.271 5.074 5.073 5.074h45.774c2.801 0 5.074-2.271 5.074-5.074v-38.605l-18.903-20.31h-31.945z" fill-rule="evenodd" clip-rule="evenodd" fill="#45B058"/>
                            <path d="M20.306 43.197c.126.144.198.324.198.522 0 .378-.306.72-.703.72-.18 0-.378-.072-.504-.234-.702-.846-1.891-1.387-3.007-1.387-2.629 0-4.627 2.017-4.627 4.88 0 2.845 1.999 4.879 4.627 4.879 1.134 0 2.25-.486 3.007-1.369.125-.144.324-.233.504-.233.415 0 .703.359.703.738 0 .18-.072.36-.198.504-.937.972-2.215 1.693-4.015 1.693-3.457 0-6.176-2.521-6.176-6.212s2.719-6.212 6.176-6.212c1.8.001 3.096.721 4.015 1.711zm6.802 10.714c-1.782 0-3.187-.594-4.213-1.495-.162-.144-.234-.342-.234-.54 0-.361.27-.757.702-.757.144 0 .306.036.432.144.828.739 1.98 1.314 3.367 1.314 2.143 0 2.827-1.152 2.827-2.071 0-3.097-7.112-1.386-7.112-5.672 0-1.98 1.764-3.331 4.123-3.331 1.548 0 2.881.467 3.853 1.278.162.144.252.342.252.54 0 .36-.306.72-.703.72-.144 0-.306-.054-.432-.162-.882-.72-1.98-1.044-3.079-1.044-1.44 0-2.467.774-2.467 1.909 0 2.701 7.112 1.152 7.112 5.636.001 1.748-1.187 3.531-4.428 3.531zm16.994-11.254l-4.159 10.335c-.198.486-.685.81-1.188.81h-.036c-.522 0-1.008-.324-1.207-.81l-4.142-10.335c-.036-.09-.054-.18-.054-.288 0-.36.323-.793.81-.793.306 0 .594.18.72.486l3.889 9.992 3.889-9.992c.108-.288.396-.486.72-.486.468 0 .81.378.81.793.001.09-.017.198-.052.288z" fill="#fff"/>
                            <g fill-rule="evenodd" clip-rule="evenodd">
                                <path d="M56.001 20.357v1h-12.8s-6.312-1.26-6.128-6.707c0 0 .208 5.707 6.003 5.707h12.925z" fill="#349C42"/>
                                <path d="M37.098.006v14.561c0 1.656 1.104 5.791 6.104 5.791h12.8l-18.904-20.352z" opacity=".5" fill="#fff"/>
                            </g>
                        </svg>
                        <!--<svg class="upload-icon__icon icon-csv">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-csv"></use>
                        </svg>-->
                    </label>
                </div>
                <button class="btn btn-default to-right modal-search-open" type="button"><?php  esc_html_e('Import product by URL or ID', 'ali2woo');?></button>
            </div>
            <div class="search-panel-body">
                <div class="search-panel-simple">
                    <div class="search-panel-inputs">
                        <input class="form-control" type="text" name="a2wl_keywords" class="search-keyword" id="a2wl_keywords" placeholder="<?php  esc_html_e('Enter Keywords', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['keywords']) ? $filter['keywords'] : ""); ?>">
                        <select id="a2wl_category" class="form-control" name="a2wl_category" aria-invalid="false">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php if (isset($filter['category']) && $filter['category'] == $cat['id']): ?>selected="selected"<?php endif;?>><?php if (intval($cat['level']) > 1): ?> - <?php endif;?><?php echo $cat['name']; ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="search-panel-buttons">
                        <button class="btn btn-info no-outline" id="a2wl-do-filter" type="button"><?php _ex('Search', 'Button', 'ali2woo');?></button>
                        <button class="btn btn-link no-outline" id="search-trigger" type="button"><?php _ex('Advance', 'Button', 'ali2woo');?></button>
                    </div>
                </div>
                <div class="search-panel-advanced" <?php if ($adv_search): ?>style="display: block;"<?php endif;?>>
                    <div class="search-panel-row">
                        <div class="search-panel-col">
                            <label><?php  esc_html_e('Price', 'ali2woo');?></label>
                            <div class="container-flex flex-wrap container-flex_fill container-flex_p20">
                                <div class="opt">
                                    <input type="text" class="form-control" name="a2wl_min_price" placeholder="<?php  esc_html_e('Price from', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['min_price']) ? $filter['min_price'] : ""); ?>">
                                </div>
                                <div class="opt">
                                    <input type="text" class="form-control" name="a2wl_max_price" placeholder="<?php  esc_html_e('Price to', 'ali2woo');?>" value="<?php echo esc_attr(isset($filter['max_price']) ? $filter['max_price'] : ""); ?>">
                                </div>
                            </div>
                        </div>
                        <?php /*
                        <div class="search-panel-col size-2-3">
                            <label><?php  esc_html_e("Special filters", 'ali2woo');?></label>
                            <div class="container-flex flex-wrap">
                                <div class="opt">
                                    <div class="label">
                                        <input type="checkbox" class="form-control" id="a2wl_freeshipping" name="a2wl_freeshipping" value="1" <?php if (isset($filter['freeshipping'])): ?>checked<?php endif;?>/>
                                        <label for="a2wl_freeshipping"><?php  esc_html_e("Free shipping", 'ali2woo');?></label>
                                    </div>
                                </div>
                                <div class="opt">
                                    <div class="label label_h32">
                                        <input type="checkbox" class="form-control" id="a2wl_return" name="a2wl_return" value="1" <?php if (isset($filter['return'])): ?>checked<?php endif;?>/>
                                        <label for="a2wl_return"><?php  esc_html_e("Free return", 'ali2woo');?></label>
                                    </div>
                                </div>
                                <div class="opt">
                                    <div class="label label_h32">
                                        <input type="checkbox" class="form-control" id="a2wl_popular" name="a2wl_popular" value="1" <?php if (isset($filter['popular'])): ?>checked<?php endif;?>/>
                                        <label for="a2wl_popular"><?php  esc_html_e("Popular products", 'ali2woo');?></label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        */ ?>
                    </div>
                </div>
                
            </div>

            <div class="modal-overlay modal-search">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><?php  esc_html_e('Import product by URL or ID', 'ali2woo');?></h3>
                        <a class="modal-btn-close" href="#"></a>
                    </div>
                    <div class="modal-body">
                        <label><?php  esc_html_e('Product URL', 'ali2woo');?></label>
                        <input class="form-control" type="text" id="url_value">
                        <div class="separator"><?php  esc_html_e('or', 'ali2woo');?></div>
                        <label><?php  esc_html_e('Product ID', 'ali2woo');?></label>
                        <input class="form-control" type="text" id="id_value">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default modal-close" type="button"><?php  esc_html_e('Cancel');?></button>
                        <button id="import-by-id-url-btn" class="btn btn-success" type="button">
                            <div class="btn-icon-wrap cssload-container"><div class="cssload-speeding-wheel"></div></div>
                            <?php  esc_html_e('Import', 'ali2woo');?>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div>
            <div class="import-all-panel">
                <button type="button" class="btn btn-success no-outline btn-icon-left import_all"><div class="btn-loader-wrap"><div class="e2w-loader"></div></div><span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span><?php  esc_html_e('Add all to import list', 'ali2woo');?></button>
            </div>
            <div class="sort-panel">
                <label for="a2wl-sort-selector"><?php  esc_html_e('Sort by:', 'ali2woo');?></label>
                <select class="form-control" id="a2wl-sort-selector">
                    <option value="bestMatch" <?php if ($filter['sort'] == 'bestMatch'): ?>selected="selected"<?php endif;?>><?php _ex('Best Match', 'sort by', 'ali2woo');?></option>
                    <option value="orignalPriceUp" <?php if ($filter['sort'] == 'orignalPriceUp'): ?>selected="selected"<?php endif;?>><?php _ex('Lowest price', 'sort by', 'ali2woo');?></option>
                    <option value="orignalPriceDown" <?php if ($filter['sort'] == 'orignalPriceDown'): ?>selected="selected"<?php endif;?>><?php _ex('Highest price', 'sort by', 'ali2woo');?></option>
                    <!--
                    <option value="sellerRateDown" <?php if ($filter['sort'] == 'sellerRateDown'): ?>selected="selected"<?php endif;?>><?php _ex("Seller's feedback score", 'sort by', 'ali2woo');?></option>
                    <option value="commissionRateUp" <?php if ($filter['sort'] == 'commissionRateUp'): ?>selected="selected"<?php endif;?>><?php _ex('Lowest commission rate', 'sort by', 'ali2woo');?></option>
                    <option value="commissionRateDown" <?php if ($filter['sort'] == 'commissionRateDown'): ?>selected="selected"<?php endif;?>><?php _ex('Highest commission rate', 'sort by', 'ali2woo');?></option>
                    -->
                    <option value="salesDesc" <?php if ($filter['sort'] == 'salesDesc'): ?>selected="selected"<?php endif;?>><?php _ex('Highest sales', 'sort by', 'ali2woo');?></option>
                    <option value="latest" <?php if ($filter['sort'] == 'latest'): ?>selected="selected"<?php endif;?>><?php _ex('Latest', 'sort by', 'ali2woo');?></option>
                    <!--
                    <option value="validTimeUp" <?php if ($filter['sort'] == 'validTimeUp'): ?>selected="selected"<?php endif;?>><?php _ex('Lowest valid time', 'sort by', 'ali2woo');?></option>
                    <option value="validTimeDown" <?php if ($filter['sort'] == 'validTimeDown'): ?>selected="selected"<?php endif;?>><?php _ex('Highest valid time', 'sort by', 'ali2woo');?></option>
                    -->
                </select>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="search-result">
            <div class="messages"><?php settings_errors('a2wl_products_list');?></div>
            <?php $localizator = AliNext_Lite\AliexpressLocalizator::getInstance();?>
            <?php $out_curr = $localizator->getLocaleCurr();?>
            <?php if ($load_products_result['state'] != 'error'): ?>
                <?php if (!$load_products_result['total']): ?>
                    <p><?php esc_html_e('products not found', 'ali2woo');?></p>
                <?php else: ?>
                    <?php $row_ind = 0;
                    $ind = 0;?>
                    <?php foreach ($load_products_result['products'] as $product): ?>
                        <?php
                        if ($row_ind == 0) {
                            echo '<div class="search-result__row">';
                        }
                        ?>
                        
                        <?php if (isset($promo_data) && $ind == 3): ?>
                        <article class="product-card product-card--promo">
                            <div class="product-card__img"><a href="<?php echo $promo_data['full_plugin_link']; ?>" target="_blank"><img src="<?php echo $promo_data['promo_image_url']; ?>" class="lazy" alt="#"></a>
                                <div class="product-card__marked-corner">
                                    <svg class="product-card__marked-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-selected"></use></svg>
                                </div>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__meta">
                                    <div class="product-card__title"><a href="<?php echo $promo_data['full_plugin_link']; ?>" target="_blank"><?php echo $promo_data['title']; ?></a></div>
                                </div>
                                <div class="product-card__price-wrapper">
                                    <h4>
                                        <span class="product-card__price"><?php echo $promo_data['currency']; ?> <?php echo $promo_data['local_price']; ?></span>
                                        <?php if (!empty($promo_data['local_regular_price'])): ?><span class="product-card__discount"><?php echo $promo_data['currency']; ?> <?php echo $promo_data['local_regular_price']; ?></span><?php endif;?>
                                    </h4>
                                </div>
                                <div class="product-card__meta-wrapper">
                                    <div class="product-card__rating">
                                        <?php for ($i = 0; $i < round($promo_data['evaluateScore']); $i++): ?>
                                            <svg class="icon-star"><use xlink:href="#icon-star"></use></svg>
                                        <?php endfor;?>
                                        <?php for ($i = round($promo_data['evaluateScore']); $i < 5; $i++): ?>
                                            <svg class="icon-empty-star"><use xlink:href="#icon-star"></use></svg>
                                        <?php endfor;?>
                                    </div>
                                    <div class="product-card__supplier">
                                        <div class="product-card__orderscount"><?php echo $promo_data['purchases']; ?> <span><?php esc_html_e('Purchases', 'ali2woo');?></span></div>
                                    </div>
                                </div>
                                <div class="product-card__actions">
                                    <button class="btn promo btn-success no-outline btn-icon-left"><span class="title"><?php echo $promo_data['button_cta']; ?></span>
                                        <div class="btn-loader-wrap"><div class="a2wl-loader"></div></div>
                                        <span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span>
                                    </button>
                                </div>
                            </div>

                        </article>
                        <?php else: ?>
                        
                        <article class="product-card<?php if ($product['post_id'] || $product['import_id']): ?> product-card--added<?php endif;?>" data-id="<?php echo $product['id'] ?>">
                            <div class="product-card__img"><a href="<?php echo $product['affiliate_url'] ?>" target="_blank"><img src="<?php echo A2WL()->plugin_url() . '/assets/img/blank_image.png'; ?>" class="lazy" data-original="<?php echo !empty($product['thumb']) ? $product['thumb'] : ""; ?>" alt="#"></a>
                                <div class="product-card__marked-corner">
                                    <svg class="product-card__marked-icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-selected"></use></svg>
                                </div>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__meta">
                                    <div class="product-card__title"><a href="<?php echo $product['affiliate_url'] ?>" target="_blank"><?php echo $product['title']; ?></a></div>
                                </div>
                                <div class="product-card__price-wrapper">
                                    <h4>
                                        <span class="product-card__price"><?php echo $out_curr; ?><?php echo $product['local_price']; ?></span>
                                        <?php if (!empty($product['local_regular_price'])): ?><span class="product-card__discount"><?php echo $out_curr; ?><?php echo $product['local_regular_price']; ?></span><?php endif;?>
                                    </h4>
                                </div>
                                
                                <span class="product-card__subtitle">
                                    <div>
                                        <div class="product-card-shipping-info"<?php if (isset($product['shipping_to_country'])): ?> data-country="<?php echo $product['shipping_to_country'] ?>"<?php endif;?>>
                                            <div class="shipping-title"><?php  esc_html_e('Choose shipping country', 'ali2woo');?></div>
                                            <div class="delivery-time"></div>
                                        </div>
                                    </div>
                                </span>
                                
                                <div class="product-card__meta-wrapper">
                                    <div class="product-card__rating">
                                        <?php for ($i = 0; $i < round($product['evaluateScore']); $i++): ?>
                                            <svg class="icon-star"><use xlink:href="#icon-star"></use></svg>
                                        <?php endfor;?>
                                        <?php for ($i = round($product['evaluateScore']); $i < 5; $i++): ?>
                                            <svg class="icon-empty-star"><use xlink:href="#icon-star"></use></svg>
                                        <?php endfor;?>
                                    </div>
                                    <div class="product-card__supplier">
                                        <div class="product-card__orderscount"><?php echo $product['volume']; ?> <span><?php esc_html_e('Orders', 'ali2woo');?></span></div><img class="supplier-icon" src="<?php echo A2WL()->plugin_url() . '/assets/img/icons/supplier_ali_2x.png'; ?>" width="16" height="16">
                                    </div>
                                </div>
                                <div class="product-card__actions">
                                    <button class="btn <?php echo ($product['post_id'] || $product['import_id']) ? 'btn-default' : 'btn-success'; ?> no-outline btn-icon-left"><span class="title"><?php if ($product['post_id'] || $product['import_id']): ?><?php  esc_html_e('Remove from import list', 'ali2woo');?><?php else: ?><?php  esc_html_e('Add to import list', 'ali2woo');?><?php endif;?></span>
                                        <div class="btn-loader-wrap"><div class="a2wl-loader"></div></div>
                                        <span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span>
                                        <span class="btn-icon-wrap remove"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-cross"></use></svg></span>
                                    </button>
                                </div>
                            </div>
                        </article>
                        
                        <?php endif;?>
                        
                        <?php $row_ind++;
$ind++;?>
                        <?php
if ($row_ind == 4) {
    echo '</div>';
    $row_ind = 0;
}
?>
                    <?php endforeach;?>
                    <?php
if (0 < $row_ind && $row_ind < 4) {
    echo '</div>';
}
?>
                    <?php if (isset($filter['country'])): ?>
                        <script>
                            (function ($) {
                                $(function () {
                                    chech_products_view();
                                    $(window).scroll(function () {
                                        chech_products_view();
                                    });
                                });
                            })(jQuery);
                        </script>
                    <?php endif;?>
                <?php endif;?>
            <?php endif;?>

        </div>
        <?php if ($load_products_result['state'] != 'error' && $load_products_result['total_pages'] > 0): ?>
            <div id="a2wl-search-pagination" class="pagination">
                <div class="pagination__wrapper">
                    <ul class="pagination__list">
                        <li <?php if (1 == $load_products_result['page']): ?>class="disabled"<?php endif;?>><a href="#" rel="<?php echo $load_products_result['page'] - 1; ?>">«</a></li>
                        <?php foreach ($load_products_result['pages_list'] as $p): ?>
                            <?php if ($p): ?>
                                <?php if ($p == $load_products_result['page']): ?>
                                    <li class="active"><span><?php echo $p; ?></span></li>
                                <?php else: ?>
                                    <li><a href="#" rel="<?php echo $p; ?>"><?php echo $p; ?></a></li>
                                <?php endif;?>
                            <?php else: ?>
                                <li class="disabled"><span>...</span></li>
                            <?php endif;?>
                        <?php endforeach;?>
                        <li <?php if ($load_products_result['total_pages'] == $load_products_result['page']): ?>class="disabled"<?php endif;?>><a href="#" rel="<?php echo $load_products_result['page'] + 1; ?>">»</a></li>
                    </ul>
                </div>
            </div>
        <?php endif;?>

        <?php include_once 'includes/confirm_modal.php';?>
        <?php include_once 'includes/shipping_modal.php';?>
    </div>
</div>