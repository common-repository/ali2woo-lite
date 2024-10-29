<?php
/**
 * @var string $hash
 */
use AliNext_Lite\AbstractAdminPage;
?>
<div class="a2wl-content">
    <h1><?php
         esc_html_e('Transfer settings', 'ali2woo'); ?></h1>
    <form class="a2wl-transfer" method="post">
        <?php wp_nonce_field(AbstractAdminPage::PAGE_NONCE_ACTION, AbstractAdminPage::NONCE); ?>
        <input type="hidden" name="transfer_form" value="1">
        <div class="a2wl-transfer__row">
            <div class="field field_default field_inline">
                <div class="field__label"><?php echo esc_html__('Paste a hash from another site to update settings', 'ali2woo'); ?></div>
                <div class="field__input-wrap">
                    <textarea class="field__input" name="hash" placeholder="<?php echo esc_html__('Paste your hash', 'ali2woo'); ?>" rows="10">
                        <?php echo esc_html__($hash); ?>
                    </textarea>
                </div>
            </div>
        </div>
        <div class="a2wl-transfer__buttons">
            <button class="btn btn-success"><?php echo esc_html__('Update Settings', 'ali2woo'); ?></button>
        </div>
    </form>
</div>