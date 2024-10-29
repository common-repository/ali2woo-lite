<?php

use AliNext_Lite\AbstractController;
use function AliNext_Lite\get_setting;
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
$load_review = get_setting('load_review');
?>
<form method="post" enctype='multipart/form-data'>
    <?php wp_nonce_field(AbstractController::PAGE_NONCE_ACTION, AbstractController::NONCE); ?>
    <input type="hidden" name="setting_form" value="1"/>
    <div class="panel panel-primary mt20">
        <div class="panel-heading">
            <h3 class="display-inline"><?php echo esc_html_x('Reviews settings', 'Setting title', 'ali2woo'); ?></h3>
        </div>

        <div class="panel-body">
            <div class="field field_inline">
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Import product reviews', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Allow to import reviews when you publish a product from the Import List to your store.', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox" class="field__input form-control" id="a2wl_load_review" name="a2wl_load_review" value="yes" <?php if ($load_review): ?>checked<?php endif; ?>/>
                </div>
            </div>

            

            <div class="field field_inline review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Import translated reviews', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('It tries to import translated version of reviews from AliExpress using the language you choose in the plugin settings.', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox" class="field__input form-control" id="a2wl_review_translated" name="a2wl_review_translated" value="yes" <?php if (get_setting('review_translated')): ?>checked<?php endif; ?>/>
                </div>

            </div>

        
            
            <div class="field field_inline review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Import review avatar', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('It tries to import the buyer profile photo from AliExpress.', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox" class="field__input form-control" id="a2wl_review_avatar_import" name="a2wl_review_avatar_import" value="yes" <?php if (get_setting('review_avatar_import')): ?>checked<?php endif; ?>/>
                </div>

            </div>

            <div class="field field_inline field_inline-2 review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Reviews per product', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Set min. and max. number of reviews (per product) that should be loaded from AliExpress. It allows you to have random number of reviews per product.', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <div class="form-group input-block no-margin">
                        <div class="input-group">
                            <span class="input-group__input input-group__input_addon" id="basic-addon3"><?php esc_html_e('From', 'ali2woo'); ?></span>
                            <input type="text" class="input-group__input form-control small-input" aria-describedby="basic-addon3" id="a2wl_review_min_per_product" name="a2wl_review_min_per_product" value="<?php echo get_setting('review_min_per_product', get_setting('review_max_per_product') ); ?>">
                        </div>

                    </div>
                </div>

                <div class="field__input-wrap">
                    <div class="form-group input-block no-margin">
                        <div class="input-group">
                            <span class="input-group__input input-group__input_addon" id="basic-addon4"><?php esc_html_e('To', 'ali2woo'); ?></span>
                            <input type="text" class="input-group__input form-control small-input" aria-describedby="basic-addon4" id="a2wl_review_max_per_product" name="a2wl_review_max_per_product" value="<?php echo get_setting('review_max_per_product'); ?>" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="field field_inline field_inline-2 review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Reviews Rating', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Filter imported reviews by the rating', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <div class="form-group input-block no-margin">
                        <div class="input-group">
                            <span class="input-group__input input-group__input_addon" id="basic-addon1"><?php esc_html_e('From', 'ali2woo'); ?></span>
                            <input type="text" class="input-group__input form-control small-input" aria-describedby="basic-addon1" id="a2wl_review_raiting_from" name="a2wl_review_raiting_from" value="<?php echo get_setting('review_raiting_from'); ?>">
                        </div>

                    </div>
                </div>

                <div class="field__input-wrap">
                    <div class="form-group input-block no-margin">
                        <div class="input-group">
                            <span class="input-group__input input-group__input_addon" id="basic-addon2"><?php esc_html_e('To', 'ali2woo'); ?></span>
                            <input type="text" class="input-group__input form-control small-input" aria-describedby="basic-addon2" id="a2wl_review_raiting_to" name="a2wl_review_raiting_to" value="<?php echo get_setting('review_raiting_to'); ?>" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="field field_inline field_inline-2 review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Default review avatar', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Defalut review`s Avatar photo used for displaying near review`s text', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <?php
                    $cur_a2wl_review_noavatar_photo = get_setting('review_noavatar_photo', A2WL()->plugin_url() . '/assets/img/noavatar.png');
                    ?>
                    <?php /* <div href="#" class="thumbnail"> */ ?>
                    <img style="height: 80px; width: 80px; display: block;" src="<?php echo $cur_a2wl_review_noavatar_photo ?>"/>
                    <?php /* </div>  */ ?>
                </div>
                <div class="field__input-wrap">
                    <label class="btn btn-default btn-file">
                    <?php esc_html_e('Browse', 'ali2woo'); ?> <input class="form-control" type="file" hidden id="a2wl_review_noavatar_photo" name="a2wl_review_noavatar_photo">
                    </label>
                </div>
            </div>

            <div class="field field_inline review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Import review attributes', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Import Review Attributes from Aliexpress', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox" class="field__input form-control small-input" id="a2wl_review_load_attributes" name="a2wl_review_load_attributes" <?php if (get_setting('review_load_attributes')): ?>value="yes" checked<?php endif; ?> />
                </div>

            </div>

            <?php $import_review_images = get_setting('review_show_image_list'); ?>
            <div class="field field_inline review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Import review images', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Some AliExpress buyers attach images to their product reviews. Use this option if you want to show these pictures on your website frontend.', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox" class="field__input form-control small-input" id="a2wl_review_show_image_list" name="a2wl_review_show_image_list" <?php if ($import_review_images): ?>value="yes" checked<?php endif; ?>  />
                </div>
            </div>

            <div id="a2wl_review_thumb_width_block" class="field field_inline review_option" <?php if (!$load_review || !$import_review_images): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Set image width', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Set image thumbnail width (in pixels), height is calculated proportionally.', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="text" class="field__input form-control small-input" id="a2wl_review_thumb_width" name="a2wl_review_thumb_width" value="<?php echo esc_attr(get_setting('review_thumb_width')); ?>"/>
                </div>

            </div>

            <div class="field field_inline review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Skip reviews with keywords', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Input keywords separated by comma. The plugin will not import reviews which contain such keywords. Please note: the keywords search is not case sensitive!', 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                        <textarea placeholder="<?php  esc_html_e('comma separated keywords', 'ali2woo'); ?>" maxlength="1000" rows="5" class="field__input form-control" id="a2wl_review_skip_keywords" name="a2wl_review_skip_keywords" cols="50"><?php echo esc_attr(get_setting('review_skip_keywords')); ?></textarea>
                </div>

            </div>

            <div class="field field_inline review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php echo esc_html_x('Skip empty reviews', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x("Some users don't leave any text in their reviews. The plugin will not import such reviews.", 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox" class="field__input form-control small-input" id="a2wl_review_skip_empty" name="a2wl_review_skip_empty" <?php if (get_setting('review_skip_empty')): ?>value="1" checked<?php endif; ?>  />
                </div>
            </div>

            <div class="field field_inline review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>>
                <div class="field__label">
                    <label>
                        <strong><?php  echo esc_html_x('Select country', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php  echo esc_html_x("You can import reviews from all or particular countries. Before importing reviews choose necessary countries or keep the field empty to allow all countries.", 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                        <?php $cur_country_array =  get_setting('review_country'); ?>
                        <select name="a2wl_review_country[]" id="a2wl_review_country" class="field__input form-control large-input" multiple="multiple">
                            <?php foreach ($reviews_countries as $code => $country): ?>
                                <option value="<?php echo $code; ?>"<?php if (in_array($code, $cur_country_array )): ?> selected<?php endif;?>>
                                    <?php echo $country; ?>
                                </option>
                            <?php endforeach;?>
                        </select>
                </div>
            </div>

            <div class="field field_inline review_option" <?php if (!$load_review): ?>style="display: none;"<?php endif; ?>
                >
                <div class="field__label">
                    <label for="a2wl_moderation_reviews">
                        <strong><?php echo esc_html_x('Send reviews to draft', 'Setting title', 'ali2woo'); ?></strong>
                    </label>
                    <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x("Use this option if you want to edit reviews before publishing. They will be saved in 'Comments' after import.", 'setting description', 'ali2woo'); ?>"></div>
                </div>
                <div class="field__input-wrap">
                    <input type="checkbox" class="field__input form-control small-input" id="a2wl_moderation_reviews" name="a2wl_moderation_reviews" <?php if (get_setting('moderation_reviews')): ?>value="1" checked<?php endif; ?>  />
                </div>
            </div>

        </div> 
    </div>

    <div class="container-fluid">
        <div class="row pt20 border-top">
            <div class="col-sm-12">
                <input id="a2wl_remove_all_reviews" class="btn btn-default" type="button" value="<?php esc_html_e('Remove all reviews', 'ali2woo'); ?>"/>
                <input class="btn btn-success" type="submit" value="<?php esc_html_e('Save settings', 'ali2woo'); ?>"/>
            </div>
        </div>
    </div>

</form>

<script>
    function a2wl_isInt(value) {
        return !isNaN(value) &&
                parseInt(Number(value)) == value &&
                !isNaN(parseInt(value, 10));
    }

    (function ($) {
        let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        let nonce_action = '<?php echo wp_create_nonce(AbstractController::AJAX_NONCE_ACTION); ?>';

        $("#a2wl_review_show_image_list").on('change', function () {
            $("#a2wl_review_thumb_width_block").toggle();

            return true;
        });

        $("#a2wl_review_country").select2({
            placeholder: a2wl_common_data.lang.leave_blank_to_allow_all_countries   
        });

        if ($.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip({"placement": "top"});
        }
        
        $("#a2wl_load_review").on('change', function () {
            if ($(this).is(':checked')){
                $('.review_option').show();
            } else {
                $('.review_option').hide();
            }

            return true;
        });


        let a2wl_review_min_per_product_keyup_timer = false;
        $('#a2wl_review_min_per_product').on('keyup', function () {
            if (a2wl_review_min_per_product_keyup_timer) {
                clearTimeout(a2wl_review_min_per_product_keyup_timer);
            }

            $('#a2wl_review_max_per_product').trigger('keyup');

            let this_el = $(this);

            this_el.parents('.form-group').removeClass('has-error');
            if (this_el.parents('.form-group').children('span').length > 0)
                this_el.parents('.form-group').children('span').remove();

            a2wl_review_min_per_product_keyup_timer = setTimeout(function () {
                let min_val = parseInt(this_el.val(), 10);
                if (!a2wl_isInt(this_el.val()) || min_val < 1) {
                    this_el.parents(".input-group").after("<span class='help-block'><?php esc_html_e('The value should be an integer greater than 0', 'ali2woo'); ?></span>");
                    this_el.parents('.form-group').addClass('has-error');
                }

            }, 1000);
        });
        
        let a2wl_review_max_per_product_keyup_timer = false;
        $('#a2wl_review_max_per_product').on('keyup', function () {
            if (a2wl_review_max_per_product_keyup_timer) {
                clearTimeout(a2wl_review_max_per_product_keyup_timer);
            }

            let this_el = $(this);

            this_el.parents('.form-group').removeClass('has-error');
            if (this_el.parents('.form-group').children('span').length > 0)
                this_el.parents('.form-group').children('span').remove();

            a2wl_review_max_per_product_keyup_timer = setTimeout(function () {
                let min_val = parseInt($('#a2wl_review_min_per_product').val(), 10);
                let max_val = parseInt(this_el.val(), 10);
                if (!a2wl_isInt(this_el.val()) || max_val < 1 || max_val < min_val) {
                    this_el.parents(".input-group").after("<span class='help-block'><?php esc_html_e('The value should be an integer greater than 0. Also it can`t be less than "from" value.', 'ali2woo'); ?></span>");
                    this_el.parents('.form-group').addClass('has-error');
                }

            }, 1000);
        });

        let a2wl_review_raiting_from_keyup_timer = false;

        $('#a2wl_review_raiting_from').on('keyup', function () {
            if (a2wl_review_raiting_from_keyup_timer) {
                clearTimeout(a2wl_review_raiting_from_keyup_timer);
            }

            $('#a2wl_review_raiting_to').trigger('keyup');

            let this_el = $(this);

            this_el.parents('.form-group').removeClass('has-error');
            if (this_el.parents('.form-group').children('span').length > 0)
                this_el.parents('.form-group').children('span').remove();

            a2wl_review_raiting_from_keyup_timer = setTimeout(function () {
                let min_val = parseInt(this_el.val(), 10);
                if (!a2wl_isInt(this_el.val()) || min_val < 1 || min_val > 5) {
                    this_el.parents('.input-group').after("<span class='help-block'><?php  esc_html_e('The value should be an integer between 1 and 5', 'ali2woo'); ?></span>");
                    this_el.parents('.form-group').addClass('has-error');
                }

            }, 1000);
        });

        let a2wl_review_raiting_to_keyup_timer = false;

        $('#a2wl_review_raiting_to').on('keyup', function () {
            if (a2wl_review_raiting_to_keyup_timer) {
                clearTimeout(a2wl_review_raiting_to_keyup_timer);
            }

            let this_el = $(this);

            this_el.parents('.form-group').removeClass('has-error');
            if (this_el.parents('.form-group').children('span').length > 0)
                this_el.parents('.form-group').children('span').remove();

            a2wl_review_raiting_to_keyup_timer = setTimeout(function () {
                let min_val = parseInt($('#a2wl_review_raiting_from').val(), 10);
                let max_val = parseInt(this_el.val(), 10);
                if (!a2wl_isInt(this_el.val()) || max_val < 1 || max_val > 5 || max_val < min_val) {
                    this_el.parents('.input-group').after("<span class='help-block'><?php esc_html_e('The value should be an integer between 1 and 5. Also it can`t be less than "from" value.', 'ali2woo'); ?></span>");
                    this_el.parents('.form-group').addClass('has-error');
                }

            }, 1000);
        });

        //form buttons  
        $('#a2wl_remove_all_reviews').click(function () {
            if (
                confirm('<?php esc_html_e('Are you sure you want to delete all reviews?', 'ali2woo'); ?>')
            ) {
                let e = $(this);
                e.val('<?php  esc_html_e('Processing', 'ali2woo'); ?>...');
                let data = {
                    'action': 'a2wl_arvi_remove_reviews',
                    'ali2woo_nonce': nonce_action,
                };
                $.post(ajaxurl, data, function (response) {
                    let json = JSON.parse(response);

                    if (json.state === 'error') {
                        console.log(json);
                        e.val('<?php esc_html_e('Error', 'ali2woo'); ?>');
                    } else {
                        e.val('<?php esc_html_e('Done', 'ali2woo'); ?>!');
                    }
                });
            }
        });


        $('.a2wl-content form').on('submit', function () {
            if ($(this).find('.has-error').length > 0)
                return false;
        });

    })(jQuery);
</script>
