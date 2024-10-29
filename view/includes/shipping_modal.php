<?php
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div class="modal-overlay modal-shipping a2wl-content">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><?php  esc_html_e('Available shipping methods', 'ali2woo');?></h3>
            <a class="modal-btn-close" href="#"></a>
        </div>
        <div class="modal-body">
            <div class="container-flex">
                <span><?php  esc_html_e('Calculate your shipping price:', 'ali2woo');?></span>
                <div class="country-select country-select-from">
                    <span><?php  esc_html_e('From:', 'ali2woo');?></span>
                    <select id="a2wl-modal-country-from-select" class="modal-country-select form-control country_list" style="width: 100%;">
                        <?php foreach ($countries as $code => $country_name): ?>
                            <option value="<?php echo $code; ?>"<?php if (isset($filter['country']) && $filter['country'] == $code): ?> selected="selected"<?php endif;?>><?php echo $country_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="country-select country-select-to">
                    <span><?php  esc_html_e('To:', 'ali2woo');?></span>
                    <select id="a2wl-modal-country-select" class="modal-country-select form-control country_list" style="width: 100%;">
                        <?php foreach ($countries as $code => $country_name): ?>
                            <option value="<?php echo $code; ?>"<?php if (isset($filter['country']) && $filter['country'] == $code): ?> selected="selected"<?php endif;?>><?php echo $country_name; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="message-container">
                <div class="shipping-method"> <span class="shipping-method-title"><?php  esc_html_e('These are the shipping methods you will be able to select when processing orders:', 'ali2woo');?></span>
                    <div class="shipping-method">
                        <table class="shipping-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><strong><?php  esc_html_e('Shipping Method', 'ali2woo');?></strong></th>
                                    <th><strong><?php  esc_html_e('Estimated Delivery Time', 'ali2woo');?></strong></th>
                                    <th><strong><?php  esc_html_e('Shipping Cost', 'ali2woo');?></strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default modal-close" type="button"><?php  esc_html_e('Ok', 'ali2woo');?></button>
        </div>
    </div>
</div>

