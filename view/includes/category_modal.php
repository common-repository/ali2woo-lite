<?php
use function AliNext_Lite\get_setting;
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div class="modal-overlay set-category-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><?php  esc_html_e('Link to category', 'ali2woo');?></h3>
            <a class="modal-btn-close" href="#"></a>
        </div>
        <div class="modal-body">
            <?php $remember_categories = get_setting('remember_categories', []);?>
            <div class="panel">
                <div class="panel-body">
                    <div class="field field_inline">
                        <div class="field__label">
                            <label>
                                <strong>
                                    <?php _ex('Choose category', 'Setting title', 'ali2woo');?>
                                </strong>
                            </label>
                            <div class="info-box" data-toggle="tooltip"
                                 data-title='<?php _ex('Choose category for your products', 'setting description', 'ali2woo');?>'>
                            </div>
                        </div>
                        <div class="field__input-wrap">
                            <select class="form-control select2 categories" data-placeholder="<?php  esc_html_e('Choose Categories', 'ali2woo');?>" multiple="multiple">
                                <option></option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['term_id']; ?>"<?php if (in_array($category['term_id'], $remember_categories)): ?> selected="selected"<?php endif;?>>
                                        <?php echo str_repeat('- ', $category['level'] - 1) . $category['name']; ?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <?php if (str_contains(A2WL()->plugin_name, 'alinext-lite')): ?>
                    <div class="field field_inline">
                        <div class="field__label">
                            <label>
                                <strong>
                                    <?php _ex('Use Aliexpress categories', 'Setting title', 'ali2woo');?>
                                </strong>
                            </label>
                            <div class="info-box" data-toggle="tooltip"
                                 data-title='<?php _ex('The plugin attempts to load the category from Aliexpress. If unsuccessful, it will use the category specified in the field above.', 'setting description', 'ali2woo');?>'>
                            </div>
                        </div>
                        <div class="field__input-wrap">
                            <input id="a2wl-use-aliexpress-category-checkbox" type="checkbox" class="field__input form-control"  value="yes" <?php if (get_setting('load_aliexpress_category')): ?>checked<?php endif;?>/>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default no-btn" type="button"><?php  esc_html_e('Cancel');?></button>
            <button class="btn btn-success yes-btn" type="button"><?php  esc_html_e('Ok');?></button>
        </div>
    </div>
</div>
<script>
    (function ($) {
        $(".set-category-dialog .select2").select2({width: '100%'});
    })(jQuery);
</script>
