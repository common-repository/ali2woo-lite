<?php
/**
 * @var array $links
 * @var array $product_links
 */
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
use AliNext_Lite\AbstractController;
use AliNext_Lite\Utils;

?>
<div class="a2wl-content">
    <div class="container">
        <div class="_a2wfo a2wl-info"><div>You are using AliNext (Lite version) Lite. If you want to unlock all features and get premium support, purchase the full version of the plugin.</div><a href="https://ali2woo.com/pricing/?utm_source=lite&utm_medium=lite&utm_campaign=alinext-lite" target="_blank" class="btn">GET FULL VERSOIN</a></div>
        <?php include_once A2WL()->plugin_path() . '/view/chrome_notify.php'; ?>
        
        
        <div id="a2wl-import-empty" class="panel panel-default margin-top"<?php if ($serach_query || count($product_list) !== 0): ?> style="display:none;"<?php endif; ?>>
            <div class="panel-body">
                <?php  esc_html_e('Your Import List is Empty.', 'ali2woo'); ?>
                <br/>
                <br/>
                <?php  esc_html_e('You can add products to this list from the “Search Products” page, or import products while browsing AliExpress using free chrome extension.', 'ali2woo'); ?>
            </div>
        </div>


        <div id="a2wl-import-content"<?php if (!$serach_query && count($product_list) === 0): ?> style="display:none;"<?php endif; ?>>
            <div id="a2wl-import-filter">
                <div class="grid grid_default grid_import-filter">
                    <div class="grid__col">
                        <h3><?php  esc_html_e('Import List', 'ali2woo'); ?></h3>
                    </div>
                    <div class="grid__col grid__col_to-right">
                        <form method="GET" action="<?php echo admin_url('admin.php'); ?>">
                            <?php wp_nonce_field(AbstractController::PAGE_NONCE_ACTION, AbstractController::NONCE); ?>
                            <input type="hidden" name="page" value="a2wl_import"/>
                            <table class="float-right">
                                <tr>
                                    <td class="padding-small-right">
                                        <select class="form-control" name="o" style="padding-right: 25px;">
                                            <?php foreach ($sort_list as $k => $v): ?><option value="<?php echo $k; ?>"<?php if ($sort_query === $k): ?> selected="selected"<?php endif; ?>><?php echo $v; ?></option><?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="padding-small-right"><input type="search" name="s" class="form-control" value="<?php echo $serach_query; ?>"></td>
                                    <td><input type="submit" class="btn btn-default" value="<?php  esc_html_e('Search products', 'ali2woo'); ?>"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

            <div id="a2wl-import-actions">
                <div class="grid grid_default grid_import-actions">
                    <div class="grid__col">
                        <div class="container-flex" style="height: 32px;">
                            <div class="margin-right">
                                <input type="checkbox" class="check-all form-control"><span class="space-small-left"><strong><?php  esc_html_e('Select All Products', 'ali2woo'); ?></strong></span>
                            </div>
                            <div class="action-with-check" style="display: none;">
                                <select class="form-control">
                                    <option value="0">Bulk Actions (0 selected)</option>
                                    <option value="remove"><?php  esc_html_e('Remove from Import List', 'ali2woo'); ?></option>
                                    <option value="push"><?php  esc_html_e('Push Products to Shop', 'ali2woo'); ?></option>
                                    <option value="link-category"><?php  esc_html_e('Link to category', 'ali2woo'); ?></option>
                                </select>
                                <div class="loader"><div class="a2wl-loader"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="grid__col grid__col_to-right">
                        <a href="#" class="btn btn-default link_category_all"><?php  esc_html_e('Link All products to category', 'ali2woo'); ?></a>
                        <a href="<?php echo $links['remove_all_products_link']; ?>" class="btn btn-danger margin-small-left delete_all"><?php  esc_html_e('Remove All Products', 'ali2woo'); ?></a>
                        <button type="button" class="btn btn-success no-outline btn-icon-left margin-small-left push_all">
                            <div class="btn-loader-wrap"><div class="a2wl-loader"></div></div>
                            <span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span><?php  esc_html_e('Push All Products to Shop', 'ali2woo'); ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="panel panel-default margin-top"<?php if (count($product_list) !== 0): ?> style="display:none;"<?php endif; ?>>
                <div class="panel-body">
                    <?php  esc_html_e('No products found.', 'ali2woo'); ?>
                </div>
            </div>

            <div class="a2wl-product-import-list">
                <?php foreach ($product_list as $ind => $product): ?>
                    
                    <?php if (isset($promo_data) && $ind == 1) : ?>
                    <div class='row space-top'>
                        <div class='col-xs-12'>
                            <div class='product product-promo'>
                                <div class="a2wl-row">
                                    <ul class="nav nav-tabs">
                                        <li class="select darker-background"><span class="for-checkbox"><input type="checkbox" class="form-control" disabled></span></li>
                                        <li class="active"><a href="#" rel="product"><?php  esc_html_e('Plugin', 'alinext-lite'); ?></a></li>
                                        
                                    </ul>
                                    <div class="actions">
                                        <button type="button" class="btn promo btn-success no-outline btn-icon-left margin-right post_import">
                                            <div class="btn-loader-wrap"><div class="a2wl-loader"></div></div>
                                            <span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span>
                                            <span class="btn-title"><?php echo $promo_data['button_cta']; ?></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="tabs-content active" rel="product">
                                    <div class="product-info-container"></div>
                                    <div class="grid grid_default grid_product-info">
                                        <div class="grid__col grid__col_image">
                                            <div class="product-img">
                                                <img class="border-img lazy" src="<?php echo $promo_data['promo_image_url']; ?>" alt="<?php echo $promo_data['title']; ?>">
                                            </div>
                                        </div>
                                        <div class="grid__col">
                                            <div>
                                                <h3>
                                                    <a class="blue-color" href="<?php echo $promo_data['full_plugin_link']; ?>" target="_blank">
                                                    <?php echo $promo_data['title']; ?>
                                                    </a>
                                                    <span class="red-color"></span>
                                                </h3>
                                                <div class="promo-description"><?php echo empty($promo_data['description'])?'':$promo_data['description']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;  ?>
                    
                    <div class='row space-top'>
                        <div class='col-xs-12'>                            
                            <div class='product<?php echo isset($product['shipping_cost'])?" shiping_loaded":""?>' data-id="<?php echo $product['import_id']; ?>" data-country_from_list="<?php echo empty($product['shipping_from_country_list'])?'':implode(";",$product['shipping_from_country_list']); ?>" data-country_from="<?php echo isset($product['shipping_from_country'])?$product['shipping_from_country']:''; ?>" data-country_to="<?php echo isset($product['shipping_to_country'])?$product['shipping_to_country']:''; ?>">
                            <?php if (!isset($product['sku_products']['variations'])) $product['sku_products']['variations'] = [];  ?>
                            <?php if (!isset($product['sku_products']['attributes'])) $product['sku_products']['attributes'] = [];  ?>
                            <div class="a2wl-row">
                                    <ul class="nav nav-tabs">
                                        <li class="select darker-background"><span class="for-checkbox"><input type="checkbox" class="form-control" value="<?php echo $product['import_id']; ?>"></span></li>
                                        <li class="active"><a href="#" rel="product"><?php  esc_html_e('Product', 'ali2woo'); ?></a></li>
                                        <li> <a href="#" rel="description"><?php  esc_html_e('Description', 'ali2woo'); ?></a></li>
                                        <li> <a href="#" rel="specs"><?php  esc_html_e('Specs', 'ali2woo'); ?></a></li>
                                        <li <?php if (a2wl_check_defined('A2WL_DO_NOT_IMPORT_VARIATIONS')): ?>style="display:none;"<?php endif; ?>> <a href="#" rel="variants"><?php  esc_html_e('Variants', 'ali2woo'); ?> <span class="badge badge-tab margin-small-left variants-count"><?php echo count($product['sku_products']['variations']) ?></span></a></li>
                                        <li> <a href="#" rel="images"><?php  esc_html_e('Images', 'ali2woo'); ?></a></li> 
                                    </ul>
                                    <div class="actions">
                                        <span class="margin-small-right"><?php  esc_html_e('External Id', 'ali2woo'); ?>: <b><?php echo $product['id']; ?></b></span>
                                        <div class="btn-group margin-small-right">
                                            <button type="button" class="btn btn-default dropdown-toggle btn-icon-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?php  esc_html_e('Action', 'ali2woo'); ?> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php if(empty($product['override_product_id'])):?>
                                                    <li><a href="#" class="product-card-override-product"><?php  esc_html_e('Select Product to Override', 'ali2woo'); ?></a></li>
                                                <?php endif;?>
                                                <?php if(count($product['sku_products']['variations'])>1):?>
                                                    <li><a href="#" class="product-card-split-product"><?php  esc_html_e('Split Product', 'ali2woo'); ?></a></li>
                                                <?php endif;?>
                                                <li><a href="<?php echo $product_links[$ind]['remove_product_link']; ?>"><?php  esc_html_e('Remove Product', 'ali2woo'); ?></a></li>
                                                <?php if (!empty($product_links[$ind]['find_all_products_in_store_link'])) : ?>
                                                    <li>
                                                        <a href="<?php echo $product_links[$ind]['find_all_products_in_store_link']; ?>">
                                                            <?php  esc_html_e('Find All Products of Store', 'ali2woo'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>

                                        <button type="button" class="btn btn-success no-outline btn-icon-left margin-right post_import">
                                            <div class="btn-loader-wrap"><div class="a2wl-loader"></div></div>
                                            <span class="btn-icon-wrap add"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-add"></use></svg></span>
                                            <span class="btn-title"><?php echo isset($product['override_product_id'])?esc_html__('Override', 'ali2woo'):esc_html__('Push to Shop', 'ali2woo');?></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="tabs-content active" rel="product">
                                    <div class="product-info-container">
                                        <?php if(isset($product['override_product_id'])):?>
                                            <div class="a2wl-warning">
                                                <?php echo $override_model->override_message($product['override_product_id'],$product['override_product_title']);?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="grid grid_default grid_product-info">
                                        <div class="grid__col grid__col_image">
                                            <div class="product-img">
                                                <img class="border-img lazy" src="<?php echo A2WL()->plugin_url() . '/assets/img/blank_image.png'; ?>" data-id="<?php echo Utils::buildImageIdFromPath($product['thumb']);?>" data-original="<?php echo $product['tmp_edit_images'][Utils::buildImageIdFromPath($product['thumb'])]['attachment_url'] ?? a2wl_image_url($product['thumb']); ?>" alt="<?php echo $product['title']; ?>">
                                                <?php if (isset($product['is_affiliate']) && $product['is_affiliate']): ?><div class="affiliate-icon"></div><?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="grid__col">
                                            <div class="container-flex">
                                                <div class="mr10 no-shrink ali-supplier"></div>
                                                <h3>
                                                    <a class="blue-color" href="<?php echo $product['url']; ?>" target="_blank">
                                                        <?php echo  isset($product['original_title'])?$product['original_title']:$product['title']; ?>
                                                    </a>
                                                    <span class="red-color"></span>
                                                </h3>
                                            </div>
                                            <?php if (!empty($product['seller_url'])):?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        by <a class="blue-color" href="<?php echo $product['seller_url']; ?>" target="_blank"><?php echo $product['seller_name']; ?></a>
                                                    </div>
                                                </div>
                                            <?php endif;?>
                                            <div class="product-edit">
                                                <div class="grid grid_form grid_3">
                                                    <div class="grid__col grid__col_fill">
                                                        <div class="field field_default">
                                                            <label for="" class="field__label">
                                                                <?php  esc_html_e('Change name', 'ali2woo'); ?>:
                                                            </label>
                                                            <div class="field__input-wrap">
                                                                <input type="text" class="field__input form-control title" maxlength="255" value="<?php echo $product['title']; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid__col">
                                                        <div class="field field_default">
                                                            <label for="" class="field__label">
                                                                <?php  esc_html_e('SKU', 'ali2woo'); ?>:
                                                            </label>
                                                            <div class="field__input-wrap">
                                                                <input type="text" class="field__input form-control sku" maxlength="255" value="<?php echo empty($product['sku'])?$product['id']:$product['sku']; ?>"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid__col">
                                                        <div class="field field_default">
                                                            <label for="" class="field__label">
                                                                <?php  esc_html_e('Status', 'ali2woo'); ?>:
                                                            </label>
                                                            <div class="field__input-wrap">
                                                                <select class="field__input form-control select2 status" data-placeholder="<?php  esc_html_e('Choose Status', 'ali2woo'); ?>">
                                                                    <option value="publish" <?php if ($product['product_status'] == "publish"): ?>selected="selected"<?php endif; ?>><?php  esc_html_e('Publish'); ?></option>
                                                                    <option value="draft" <?php if ($product['product_status'] == "draft"): ?>selected="selected"<?php endif; ?>><?php  esc_html_e('Draft'); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid__col">
                                                        <div class="field field_default">
                                                            <label for="" class="field__label">
                                                                <?php  esc_html_e('Type', 'ali2woo'); ?>:
                                                            </label>
                                                            <div class="field__input-wrap">
                                                                <select class="field__input form-control select2 type" data-placeholder="<?php  esc_html_e('Choose Type', 'ali2woo'); ?>">
                                                                    <option value="simple" <?php if ($product['product_type'] == "simple"): ?>selected="selected"<?php endif; ?>><?php _ex('Simple/Variable Product', 'Setting option', 'ali2woo'); ?></option>
                                                                    <option value="external" <?php if ($product['product_type'] == "external"): ?>selected="selected"<?php endif; ?>><?php _ex('External/Affiliate Product', 'Setting option', 'ali2woo'); ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid__col">
                                                        <div class="field field_default">
                                                            <label for="" class="field__label">
                                                                <?php  esc_html_e('Categories', 'ali2woo'); ?>:
                                                            </label>
                                                            <div class="field__input-wrap">
                                                                <select class="field__input form-control select2 categories" data-placeholder="<?php  esc_html_e('Choose Categories', 'ali2woo'); ?>" multiple="multiple">
                                                                    <option></option>
                                                                    <?php foreach ($categories as $c): ?>
                                                                        <option value="<?php echo $c['term_id']; ?>"<?php if (in_array($c['term_id'], $product['categories'])): ?> selected="selected"<?php endif; ?>><?php echo str_repeat('- ', $c['level'] - 1) . $c['name']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid__col">
                                                        <div class="field field_default">
                                                            <label for="" class="field__label">
                                                                <?php  esc_html_e('Tags', 'ali2woo'); ?>:
                                                            </label>
                                                            <div class="field__input-wrap">
                                                                <select name="tags" class="field__input form-control select2-tags tags" data-placeholder="<?php  esc_html_e('Enter Tags', 'ali2woo'); ?>" multiple="multiple">
                                                                    <?php foreach ($product['tags'] as $tag): ?>
                                                                        <option value="<?php echo $tag; ?>" selected="selected"><?php echo $tag; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tabs-content" rel="description">
                                    <?php
                                    wp_editor($product['description'], $product['import_id'], array('editor_class' => 'a2wl_description_editor', 'media_buttons' => false, 'editor_height' => 360/* , 'default_editor'=>'html' */));
                                    ?>
                                </div>
                                <div class="tabs-content" rel="specs">
                                    <?php $attribute_values_separator = AliNext_Lite\get_setting('attribute_values_separator'); ?>
                                    <div class="specs-wrap">
                                        <table class="specs-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th><?php  esc_html_e('Name', 'ali2woo'); ?></th>
                                                    <th><?php  esc_html_e('Value', 'ali2woo'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="specs-sortable">
                                            <?php foreach ($product['attribute'] as $i => $attr): ?>
                                                <tr class="checked-spec">
                                                    <td class="align-right"><input type="checkbox" value="1" class="check-spec-delete form-control" checked="checked"></td>
                                                    <td class="column-handle ui-sortable-handle"></td>
                                                    <td class="spec-name"><input type="text" class="form-control" value="<?php echo $attr['name']; ?>"></td>
                                                    <td class="spec-value"><input type="text" class="form-control" value="<?php echo implode($attribute_values_separator." ", $attr['value']); ?>"></td>
                                                    <td class="spec-actions"><a href="#" class="del-spec close-icon"></a></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <div class="specs-actions">
                                            <a href="#" class="add-spec">+ Add spec</a>
                                            <span class="btn btn-danger margin-small-left btn-delete-specs" data-product-id="<?php echo $product['import_id']; ?>">DELETE</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tabs-content" rel="variants" <?php if (a2wl_check_defined('A2WL_DO_NOT_IMPORT_VARIATIONS')): ?>style="display:none;"<?php endif; ?>>
                                    <div id="variants-images-container-<?php echo $product['import_id']; ?>" class="variants-wrap">
                                        <div class="variants-actions">
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td>
                                                        <label for="disable-price-change-<?php echo $product['import_id']; ?>"><?php  esc_html_e('Prevent product price from auto-updating', 'ali2woo'); ?></label>
                                                        <input class="form-control disable-var-price-change" type="checkbox" id="disable-price-change-<?php echo $product['import_id']; ?>" <?php if (isset($product['disable_var_price_change']) && $product['disable_var_price_change']): ?> checked="checked"<?php endif; ?>>
                                                        <div class="info-box" data-toggle="tooltip" title="If you choose to prevent product price from auto-updating, it will not be changed regardless of the auto-updating settings in your account, or price changes made by the supplier. You will only be able to change your price manually."></div>
                                                    </td>
                                                    <td>
                                                        <label for="disable-quantity-change-<?php echo $product['import_id']; ?>"><?php  esc_html_e('Prevent product quantity from auto-updating', 'ali2woo'); ?></label>
                                                        <input class="form-control disable-var-quantity-change" type="checkbox" id="disable-quantity-change-<?php echo $product['import_id']; ?>" <?php if (isset($product['disable_var_quantity_change']) && $product['disable_var_quantity_change']): ?> checked="checked"<?php endif; ?>>
                                                        <div class="info-box" data-toggle="tooltip" title="If you choose to prevent product quantity from auto-updating, it will not be changed regardless of the auto-updating settings in your account, or quantity changes made by the supplier. You will only be able to change your quantity manually."></div>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>        

                                        <table class="variants-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" class="nowrap"><input type="checkbox" class="check-all-var form-control" <?php if (!$product['skip_vars']): ?> checked="checked"<?php endif; ?>><?php  esc_html_e('Use all', 'ali2woo'); ?></th>
                                                    <th><?php  esc_html_e('SKU', 'ali2woo'); ?></th>
                                                    <?php foreach ($product['sku_products']['attributes'] as $attr): ?>
                                                        <th>
                                                            <input type="text" class="form-control attr-name" data-id="<?php echo $attr['id']; ?>" value="<?php echo $attr['name']; ?>">
                                                        </th>
                                                    <?php endforeach; ?>
                                                    <th><?php  esc_html_e('Cost', 'ali2woo'); ?></th>                                                    
                                                    <th><?php  esc_html_e('Shipping', 'ali2woo'); ?></th>                                                    
                                                    <th><?php  esc_html_e('Price', 'ali2woo'); ?></th>
                                                    <th><?php  esc_html_e('Regular Price', 'ali2woo'); ?></th>
                                                    <th><?php  esc_html_e('Profit', 'ali2woo'); ?></th>
                                                    <th><?php  esc_html_e('Inventory', 'ali2woo'); ?></th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <?php foreach ($product['sku_products']['attributes'] as $attr): ?>
                                                        <td data-attr-id="<?php echo $attr['id']; ?>">
                                                            <div class="price-edit-selector edit-price large rename-attr">
                                                                <button type="button" class="btn btn-sm btn-default dropdown-toggle btn-icon-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <?php  esc_html_e('Change Attr', 'ali2woo'); ?> <span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-sm">
                                                                    <li><a href="javascript:void(0)" class="rename-attr-value"><?php  esc_html_e('Rename attributes', 'ali2woo'); ?></a></li>
                                                                </ul>
                                                            </div>  
                                                        </td>
                                                    <?php endforeach; ?>
                                                    <td></td>
                                                    <!-- <td colspan="<?php echo (4 + count($product['sku_products']['attributes'])); ?>"></td> -->
                                                    <td>
                                                        <a href="#" class="shipping-country"><?php echo isset($product['shipping_to_country'])? (isset($product['shipping_to_country_name'])?$product['shipping_to_country_name']:$product['shipping_to_country']):esc_html__('Select country', 'ali2woo'); ?></a>
                                                    </td>
                                                    <td>
                                                        <div class="price-edit-selector edit-price">
                                                            <div class="price-box-top">
                                                                <div class="container-flex">
                                                                    <div>
                                                                        <input type="text" class="form-control" placeholder="Enter Value">
                                                                    </div>
                                                                    <div>
                                                                        <button class="apply btn btn-default margin-small-left">Apply</button>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="close btn btn-default"><span class="btn-icon-wrap remove"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-cross"></use></svg></span></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-default dropdown-toggle btn-icon-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <?php  esc_html_e('Change Prices', 'ali2woo'); ?> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-sm">
                                                                <li><a href="javascript:void(0)" class="set-new-value"><?php  esc_html_e('Set New Value', 'ali2woo'); ?></a></li>
                                                                <li><a href="javascript:void(0)" class="multiply-by-value"><?php  esc_html_e('Multiply by', 'ali2woo'); ?></a></li>
                                                            </ul>
                                                        </div>  
                                                    </td>
                                                    <td>
                                                        <div class="price-edit-selector edit-regular-price">
                                                            <div class="price-box-top">
                                                                <div class="container-flex">
                                                                    <div>
                                                                        <input type="text" class="form-control" placeholder="Enter Value">
                                                                    </div>
                                                                    <div>
                                                                        <button class="apply btn btn-default margin-small-left">Apply</button>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="close btn btn-default"><span class="btn-icon-wrap remove"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-cross"></use></svg></span></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-default dropdown-toggle btn-icon-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <?php  esc_html_e('Change Prices', 'ali2woo'); ?> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-sm">
                                                                <li><a href="javascript:void(0)" class="set-new-value"><?php  esc_html_e('Set New Value', 'ali2woo'); ?></a></li>
                                                                <li><a href="javascript:void(0)" class="multiply-by-value"><?php  esc_html_e('Multiply by', 'ali2woo'); ?></a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <div class="price-edit-selector edit-quantity">
                                                            <div class="price-box-top price-box-right">
                                                                <div class="container-flex">
                                                                    <div>
                                                                        <input type="text" class="form-control simple-value" placeholder="<?php  esc_html_e('Enter Value', 'ali2woo'); ?>">
                                                                        <input type="text" class="form-control random-from" placeholder="<?php  esc_html_e('From', 'ali2woo'); ?>">
                                                                        <input type="text" class="form-control random-to" placeholder="<?php  esc_html_e('To', 'ali2woo'); ?>">
                                                                    </div>
                                                                    <div>
                                                                        <button class="apply btn btn-default margin-small-left">Apply</button>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="close btn btn-default"><span class="btn-icon-wrap remove"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-cross"></use></svg></span></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-default dropdown-toggle btn-icon-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <?php  esc_html_e('Change Inv.', 'ali2woo'); ?> <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-sm dropdown-right">
                                                                <li><a href="javascript:void(0)" class="set-new-quantity"><?php  esc_html_e('Set New Value', 'ali2woo'); ?></a></li>
                                                                <li><a href="javascript:void(0)" class="random-value"><?php  esc_html_e('Random Value', 'ali2woo'); ?></a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($product['sku_products']['variations'] as $i => $var): ?>
                                                    <tr data-id="<?php echo $var['id']; ?>" class="var_data">
                                                        <td>
                                                            <input type="checkbox" value="1" class="check-var form-control" <?php if (!in_array($var['id'], $product['skip_vars'])): ?> checked="checked"<?php endif; ?>>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($var['image'])): ?><img class="border-img lazy-in-container"style="max-width: 100px; max-height: 100px; margin: 5px"src="<?php echo A2WL()->plugin_url() . '/assets/img/blank_image.png'; ?>"data-id="<?php echo Utils::buildImageIdFromPath($var['image']);?>"data-original="<?php echo $product['tmp_edit_images'][Utils::buildImageIdFromPath($var['image'])]['attachment_url'] ?? a2wl_image_url($var['image']); ?>"data-img="<?php echo $product['tmp_edit_images'][Utils::buildImageIdFromPath($var['image'])]['attachment_url'] ?? a2wl_image_url($var['image']); ?>"data-toggle="popover-hover"><?php endif; ?>

                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control sku" value="<?php echo $var['sku']; ?>">
                                                        </td>
                                                        <?php foreach ($var['attributes'] as $j => $av): ?>
                                                            <td data-attr-id="<?php echo explode(":", $av)[0]; ?>"><input type="text" class="form-control attr" data-id="<?php echo $av; ?>" value="<?php echo isset($var['attributes_names'][$j]) ? $var['attributes_names'][$j] : ''; ?>"></td>
                                                        <?php endforeach; ?>
                                                        <td style="white-space: nowrap;" class="external-price" data-value="<?php echo $var['price']; ?>"><?php echo $localizator->getLocaleCurr($var['currency']); ?><?php echo $var['price']; ?></td>
                                                        <td style="white-space: nowrap;" class="external-shipping" data-value="<?php echo isset($product['shipping_cost'])?floatval($product['shipping_cost']):"0"?>" data-currency="<?php echo $localizator->getLocaleCurr($var['currency']); ?>"><?php echo isset($product['shipping_cost'])?$localizator->getLocaleCurr($var['currency']).floatval($product['shipping_cost']):"N/A"?></td>
                                                        <td>
                                                            <input type="text" class="form-control price" value="<?php echo $var['calc_price']; ?>">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control regular_price" value="<?php echo $var['calc_regular_price']; ?>">
                                                        </td>
                                                        <td class="profit"><?php echo $localizator->getLocaleCurr($var['currency']); ?><span class="value"><?php echo round($var['calc_price'] - $var['price'], 2); ?></span></td>
                                                        <td><input type="text" class="form-control quantity" value="<?php echo $var['quantity']; ?>"></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <script>
                                        (function ($) {
                                            $(".variants-wrap img.lazy-in-container").lazyload({effect: "fadeIn", skip_invisible: true, container: $("#variants-images-container-<?php echo $product['import_id']; ?>")});
                                        })(jQuery);
                                    </script>

                                </div>
                                <div class="tabs-content" rel="images">
                                    <div id="images-container-<?php echo $product['import_id']; ?>" class="images-wrap">
                                        <?php if (!empty($product['gallery_images'])): ?>
                                            <div class="images-blog-title">
                                                <input type="checkbox" id="check-all-gallery-image-id-<?php echo $product['import_id'];?>" class="check-all-block-image form-control" checked="checked">
                                                <label for="check-all-gallery-image-id-<?php echo $product['import_id'];?>"><?php  esc_html_e('Gallery images', 'ali2woo'); ?></label>
                                            </div>
                                            <div class="grid grid_default grid_4 gallery_images">
                                                <?php foreach ($product['gallery_images'] as $img_id => $image): ?>
                                                    <div class="grid__col">
                                                        <div id="<?php echo $img_id; ?>" class="image<?php if (!in_array($img_id, $product['skip_images'])): ?> selected<?php endif; ?>">
                                                            <img class="lazy-in-container" src="<?php echo A2WL()->plugin_url() . '/assets/img/blank_image.png'; ?>" data-id="<?php echo $img_id;?>" data-original="<?php echo isset($product['tmp_edit_images'][$img_id]['attachment_url']) ? $product['tmp_edit_images'][$img_id]['attachment_url'] : a2wl_image_url($image); ?>"/>
                                                            <div class="icon-selected-box align-center"><svg class="icon-selected"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-selected"></use></svg></div>
                                                            <div class="icon-gallery-box align-center<?php if ($product['thumb_id'] == $img_id): ?> selected<?php endif; ?>"><svg class="icon-star"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-star"></use></svg></div>
                                                            <?php if (isset($product['tmp_move_images'][$img_id])): ?>
                                                                <div class="cancel-image-action"><a href="#" data-action="move#<?php echo $product['tmp_move_images'][$img_id]; ?>"><?php  esc_html_e('Cancel move', 'ali2woo'); ?></a></div>
                                                            <?php elseif (isset($product['tmp_copy_images'][$img_id])): ?>
                                                                <div class="cancel-image-action"><a href="#" data-action="copy#<?php echo $product['tmp_copy_images'][$img_id]; ?>"><?php  esc_html_e('Cancel copy', 'ali2woo'); ?></a></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($product['variant_images'])): ?>
                                            <div class="images-blog-title">
                                                <input type="checkbox" id="check-all-variant-image-id-<?php echo $product['import_id'];?>" class="check-all-block-image form-control" checked="checked">
                                                <label for="check-all-variant-image-id-<?php echo $product['import_id'];?>"><?php  esc_html_e('Variations images', 'ali2woo'); ?></label>
                                            </div>
                                            <div class="grid grid_default grid_4 variant_images">
                                                <?php foreach ($product['variant_images'] as $img_id => $image): ?>
                                                    <div class="grid__col">
                                                        <div id="<?php echo $img_id; ?>" class="image<?php if (!in_array($img_id, $product['skip_images'])): ?> selected<?php endif; ?>">
                                                            <img class="lazy-in-container" src="<?php echo A2WL()->plugin_url() . '/assets/img/blank_image.png'; ?>" data-id="<?php echo $img_id;?>" data-original="<?php echo isset($product['tmp_edit_images'][$img_id]['attachment_url']) ? $product['tmp_edit_images'][$img_id]['attachment_url'] : a2wl_image_url($image); ?>"/>
                                                            <div class="icon-selected-box align-center"><svg class="icon-selected"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-selected"></use></svg></div>
                                                            <div class="icon-gallery-box align-center<?php if ($product['thumb_id'] == $img_id): ?> selected<?php endif; ?>"><svg class="icon-star"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-star"></use></svg></div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($product['description_images'])): ?>
                                            <div class="images-blog-title">
                                                <input type="checkbox" id="check-all-description-image-id-<?php echo $product['import_id'];?>" class="check-all-block-image form-control" checked="checked">
                                                <label for="check-all-description-image-id-<?php echo $product['import_id'];?>"><?php  esc_html_e('Description images', 'ali2woo'); ?></label>
                                                <div class="images-action">
                                                    <select class="form-control description-images-action">
                                                        <option value="" disabled="disabled" selected="selected"><?php  esc_html_e('Action', 'ali2woo'); ?></option>
                                                        <option value="move"><?php  esc_html_e('Move checked images to gallery', 'ali2woo'); ?></option>
                                                        <option value="copy"><?php  esc_html_e('Copy checked images to gallery', 'ali2woo'); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="grid grid_default grid_4 description_images">
                                                <?php foreach ($product['description_images'] as $img_id => $image): ?>
                                                    <?php if (!isset($product['tmp_move_images'][$img_id])): ?>
                                                        <div class="grid__col">
                                                            <div id="<?php echo $img_id; ?>" class="image<?php if (!in_array($img_id, $product['skip_images'])): ?> selected<?php endif; ?>">
                                                                <img class="lazy-in-container" src="<?php echo A2WL()->plugin_url() . '/assets/img/blank_image.png'; ?>" data-id="<?php echo $img_id;?>" data-original="<?php echo isset($product['tmp_edit_images'][$img_id]['attachment_url']) ? $product['tmp_edit_images'][$img_id]['attachment_url'] : a2wl_image_url($image); ?>"/>
                                                                <div class="icon-selected-box align-center"><svg class="icon-selected"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-selected"></use></svg></div>
                                                                <div class="icon-gallery-box align-center<?php if ($product['thumb_id'] == $img_id): ?> selected<?php endif; ?>"><svg class="icon-star"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-star"></use></svg></div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <script>
                                        (function ($) {
                                            $(".images-wrap img.lazy-in-container").lazyload({effect: "fadeIn", skip_invisible: true, container: $("#images-container-<?php echo $product['import_id']; ?>")});
                                        })(jQuery);
                                    </script>
                                </div>
                            </div>                            
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php include_once 'includes/rename_attributes_modal.php'; ?>
            <?php include_once 'includes/confirm_modal.php'; ?>
            <?php include_once 'includes/shipping_modal.php'; ?>
            <?php include_once 'includes/category_modal.php'; ?>
            <?php include_once 'includes/progress_modal.php'; ?>
            <?php include_once 'includes/split_modal.php'; ?>
            <?php include_once 'includes/override_modal.php'; ?>
        </div>


        <?php if ($paginator['total_pages'] > 1): ?>
            <div class="pagination">
                <div class="pagination__wrapper">
                    <ul class="pagination__list">
                        <li <?php if (1 == $paginator['cur_page']): ?>class="disabled"<?php endif; ?>><a href="<?php echo admin_url('admin.php?page=a2wl_import&cur_page=' . ($paginator['cur_page'] - 1)) ?>">«</a></li>
                        <?php foreach ($paginator['pages_list'] as $p): ?>
                            <?php if ($p): ?>
                                <?php if ($p == $paginator['cur_page']): ?>
                                    <li class="active"><span><?php echo $p; ?></span></li>
                                <?php else: ?>
                                    <li><a href="<?php echo admin_url('admin.php?page=a2wl_import&cur_page=' . $p) ?>"><?php echo $p; ?></a></li>
                                <?php endif; ?>
                            <?php else: ?>
                                <li class="disabled"><span>...</span></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <li <?php if ($paginator['total_pages'] <= $paginator['cur_page']): ?>class="disabled"<?php endif; ?>><a href="<?php echo admin_url('admin.php?page=a2wl_import&cur_page=' . ($paginator['cur_page'] + 1)) ?>">»</a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        (function ($) {
            let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            let nonce_action = '<?php echo wp_create_nonce(AbstractController::AJAX_NONCE_ACTION); ?>';
            if ($.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip({"placement": "top"});
            }

            $(function () {
                $( ".specs-sortable" ).sortable({handle: ".column-handle"});
                $( ".specs-sortable" ).disableSelection();
            });
            
            $(".product .select2").select2();
            $('.product .select2-tags').select2({tags: true, tokenSeparators: [','], ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    data: function (params) {
                        let query = {
                            action: 'a2wl_search_tags',
                            search: params.term,
                            page: params.page || 1,
                            ali2woo_nonce: nonce_action,
                        }

                        return query;
                    }
                }});
            //$('.dropdown-toggle').dropdown();
            $(".set-category-dialog .select2").select2({width: '100%'});

            $('.product .nav-tabs a').click(function () {
                $(this).parents('.product').children("div.tabs-content").removeClass("active");
                $(this).parents('.product').children('div.tabs-content[rel="' + $(this).attr("rel") + '"]').addClass("active");

                $(this).parents('.product').find(".nav-tabs li").removeClass("active");
                $(this).parents('.product').find('.nav-tabs li a[rel="' + $(this).attr("rel") + '"]').closest('li').addClass("active");

                if ($(this).attr("rel") === 'images') {
                    $(this).parents('.product').children('div.tabs-content[rel="' + $(this).attr("rel") + '"]').find('img.lazy-in-container').lazyload();
                }
                
                if ($(this).attr("rel") === 'variants') {
                    $(this).parents('.product').children('div.tabs-content[rel="' + $(this).attr("rel") + '"]').find('img.lazy-in-container').lazyload();
                    if(!$(this).parents(".product").hasClass('shiping_loaded')){
                        a2wl_update_product_shipping_info($(this).parents(".product").attr('data-id'));
                        $(this).parents(".product").addClass('shiping_loaded');
                    }
                }
                return false;
            });
        })(jQuery);
    </script>
    
</div>