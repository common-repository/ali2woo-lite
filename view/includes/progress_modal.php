<?php
use function AliNext_Lite\get_setting;
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div class="modal-overlay progress-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><?php  esc_html_e('Your task in progress', 'ali2woo');?></h3>
            <a class="modal-btn-close" href="#"></a>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
            <button class="btn btn-success yes-btn" type="button"><?php  esc_html_e('Ok');?></button>
        </div>
    </div>
</div>
<script id="a2wl_template_category_progress_step1" type="text/x-jsrender">
     <div class="loader a2wl-load-container" style="padding:20px 0;"><div class="a2wl-load-speeding-wheel"></div></div>
     <?php _ex('Build a list of products to load AliExpress categories for...', 'status', 'ali2woo');?>
</script>
<script id="a2wl_template_category_progress_step2" type="text/x-jsrender">
     <div class="loader a2wl-load-container" style="padding:20px 0;"><div class="a2wl-load-speeding-wheel"></div></div>
     <?php _ex(sprintf(
        'Loading and assigning categories: %s/%s products processed, %s errors...',
        '{{:current}}',
        '{{:total}}',
         '{{:errors_count}}'
    ),
        'status',
        'ali2woo');
    ?>
</script>
<script id="a2wl_template_category_progress_step3" type="text/x-jsrender">
     <?php _ex(sprintf(
        'Process completed: %s/%s products processed, %s errors.',
        '{{:current}}',
        '{{:total}}',
        '{{:errors_count}}'
    ),
        'status',
        'ali2woo');
    ?>
</script>
<script id="a2wl_template_modal_progress_notice" type="text/x-jsrender">
     <div class="mt20"><strong>
    <?php _ex('Please don`t close the page until the process is finished.', 'status', 'ali2woo');?>
    </strong></div>
</script>
<script id="a2wl_template_modal_progress_unhandled_error" type="text/x-jsrender">
    <?php _ex(
            sprintf(
                    'Unhandled error occurred during the request. Please contact support: %s',
                    '<a target="_blank" href="https://support.ali2woo.com">https://support.ali2woo.com</a>'
            ),
            'status',
            'ali2woo');
    ?>
</script>