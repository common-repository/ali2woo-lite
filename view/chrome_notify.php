<?php
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div id="chrome-notify" class="panel panel-chrome panel-default small-padding margin-top">
    <div class="panel-body">
        <div class="container-flex flex-between"> 
            <div class="container-flex">
                <img class="display-block margin-right" width="16" src="<?php echo A2WL()->plugin_url() . '/assets/img/logo_chrome.png'; ?>" alt="chrome extension">
                <span class="display-block"><strong><?php  esc_html_e('Save time searching best products using free chrome extension!', 'ali2woo'); ?></strong></span>
            </div>
            <div class="container-flex">
                <a class="btn btn-primary btn-sm chrome-install mr10" target="_blank" href="<?php echo A2WL()->chrome_url; ?>"><?php  esc_html_e('Get Chrome Extension', 'ali2woo'); ?></a>
                <a href="#" class="btn-link small chrome-notify-close" alt="<?php  esc_html_e('Close'); ?>">
                    <svg class="icon-small-cross"> 
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-small-cross"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <script>(function ($) {
            $('.chrome-notify-close').on('click', function () {
                $(this).closest('.panel').remove();
                return false;
            });
        })(jQuery);</script>
</div>
