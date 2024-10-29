<?php

use AliNext_Lite\AbstractController;
use AliNext_Lite\ImportProcess;
use function AliNext_Lite\get_setting;
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
$write_info_log = get_setting('write_info_log');
$pc_info = AliNext_Lite\SystemInfo::server_ping();
?>

<form method="post">
    <?php wp_nonce_field(AbstractController::PAGE_NONCE_ACTION, AbstractController::NONCE); ?>
    <input type="hidden" name="setting_form" value="1"/>
    <div class="system_info">
        <div class="panel panel-primary mt20">
            <div class="panel-body">
                <div class="field field_inline">
                    <div class="field__label">
                        <label for="a2wl_write_info_log">
                            <strong><?php esc_html_e('Write logs', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php esc_html_e('Write logs', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                            <input type="checkbox" class="form-control" id="a2wl_write_info_log" name="a2wl_write_info_log" value="yes" <?php if ($write_info_log): ?><?php esc_html_e('checked', 'ali2woo'); ?><?php endif; ?>/>
                            <?php if ($write_info_log): ?>
                                <div><?php if (file_exists(AliNext_Lite\Logs::getInstance()->log_path())): ?><a target="_blank" href="<?php echo AliNext_Lite\Logs::getInstance()->log_url();?>"><?php esc_html_e('Open log file', 'ali2woo'); ?></a> | <?php endif; ?>
                                <a class="a2wl-clean-log" href="#"><?php esc_html_e('Delete log file', 'ali2woo'); ?></a></div>
                            <?php endif; ?>
                    </div>
                </div>

                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php esc_html_e('Server address', 'ali2woo'); ?></strong>
                        </label>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin clearfix">
                            <?php echo $server_ip;?>
                        </div>                                                                     
                    </div>
                </div>
                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php esc_html_e('Php version', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Php version', 'setting description', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin clearfix">
                            <?php
                            $result = AliNext_Lite\SystemInfo::php_check();
                            echo ($result['state']!=='ok'?'<span class="error">Error</span>':'<span class="ok">Ok</span>');
                            if($result['state']!=='ok'){
                                echo '<div class="info-box" data-toggle="tooltip" data-title="'.$result['message'].'"></div>';
                            }
                            ?>
                        </div>                                                                     
                    </div>
                </div>
                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php  esc_html_e('Php max execution time', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php _ex('Php max execution time', 'setting description', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin clearfix">
                            <?php $maxExecutionTime = intval(ini_get("max_execution_time")); ?>
                            <?php if ($maxExecutionTime < 30 ): ?>
                            <span class="error">Error (<?php echo $maxExecutionTime; ?> sec)</span>
                            <div class="info-box"
                                 data-toggle="tooltip"
                                 data-title="<?php _ex('You have to increase php max_execution_time to 30 seconds at least', 'setting description', 'ali2woo'); ?>">
                            </div>
                            <?php else: ?>
                            <span class="ok">Ok (<?php echo $maxExecutionTime; ?> sec)</span>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php  esc_html_e('Php max memory limit', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php _ex('Php memory limit', 'setting description', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin clearfix">
                            <?php $memoryLimit = ini_get("memory_limit");
                            $memoryLimitInBytes = $memoryLimit ? wp_convert_hr_to_bytes($memoryLimit) : 0;
                            $minMemoryLimit = 128 * 1000000; //128 mb;
                            $formatMemoryLimit = size_format($memoryLimitInBytes);
                            ?>
                            <?php if ($memoryLimitInBytes < $minMemoryLimit): ?>
                                <span class="error">Error (<?php echo $formatMemoryLimit; ?>)</span>
                                <div class="info-box"
                                     data-toggle="tooltip"
                                     data-title="<?php _ex('You have to increase php memory_limit to 128 MB at least', 'setting description', 'ali2woo'); ?>">
                                </div>
                            <?php else: ?>
                                <span class="ok">Ok (<?php echo $formatMemoryLimit; ?>)</span>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php esc_html_e('Php config', 'ali2woo'); ?></strong>
                        </label>
                    </div>
                    
                    <div class="field__input-wrap">
                        <div class="php_ini_check_row">
                            <span><?php esc_html_e('allow_url_fopen', 'ali2woo'); ?>:</span>
                            <?php if(ini_get('allow_url_fopen')):?>
                                <span class="ok"><?php esc_html_e('On', 'ali2woo'); ?></span>
                            <?php else: ?>
                                <span class="error"><?php esc_html_e('Off', 'ali2woo'); ?></span><div class="info-box" data-toggle="tooltip" data-title="<?php esc_html_e('There may be problems with the image editor', 'ali2woo');?>"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php esc_html_e('Internal AJAX call', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x("If you see Error here, then the background loading feature and the synchronization function don't work on your website. Need analyze php error log and server configutation to resolve the issue.", 'setting description', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin clearfix">
                            <?php
                            $result = AliNext_Lite\SystemInfo::ping();
                            echo ($result['state']!=='ok'?'<span class="error">ERROR</span>':'<span class="ok">OK</span>');
                            if(!empty($result['message'])){
                                echo '<div class="info-box" data-toggle="tooltip" data-title="'.$result['message'].'"></div>';
                            }
                            ?>
                        </div>                                                                     
                    </div>
                </div>
                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php esc_html_e('Server ping', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('Server ping', 'setting description', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin clearfix">
                            <?php
                            echo ($pc_info['state']!=='ok'?'<span class="error">ERROR</span>':'<span class="ok">OK</span>');
                            if(!empty($pc_info['message'])){
                                if ($pc_info['state']!=='ok') {
                                    echo '<div class="row-comments">The error message is: <b>'.$pc_info['message'].'</b>'; 
                                    if(strpos(strtolower($pc_info['message']) , 'curl') !== false) {
                                        echo '<br/>Please contact your server/hosting support and ask why it happens and how to fix the issue';
                                    }
                                    echo '</div>';
                                }else{
                                    echo '<div class="info-box" data-toggle="tooltip" data-title="'.$pc_info['message'].'"></div>';
                                }
                            }
                            ?>
                        </div>                                                                     
                    </div>
                </div>
                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php esc_html_e('DISABLE_WP_CRON', 'ali2woo'); ?></strong>
                        </label>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin clearfix">
                            <?php echo (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON)?"Yes":"No";?>
                            <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('We recommend to disable WP Cron and setup the cron on your server/hosting instead.', 'setting description', 'ali2woo'); ?>"></div>                            
                        </div>                                                                     
                    </div>
                </div>
                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php esc_html_e('PHP DOM', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('is there a DOM library', 'setting description', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin clearfix">
                            <?php
                            $result = AliNext_Lite\SystemInfo::php_dom_check();
                            echo ($result['state']!=='ok'?'<span class="error">ERROR</span>':'<span class="ok">OK</span>');
                            if(!empty($result['message'])){
                                echo '<div class="info-box" data-toggle="tooltip" data-title="'.$result['message'].'"></div>';
                            }
                            ?>
                        </div>                                                                     
                    </div>
                </div>

                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php esc_html_e('Import queue', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php esc_html_e('Import queue', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin">
                            <?php 
                            $import_process = new ImportProcess();
                            $num_in_queue = $import_process->getSize();
                            ?>
                            <span><?php echo $num_in_queue; ?></span> 
                            <?php if($num_in_queue>0):?>
                            <a class="a2wl-run-cron-queue" href="#"><?php esc_html_e('Run', 'ali2woo'); ?></a> | <a class="a2wl-clean-import-queue" href="#"><?php esc_html_e('Clean', 'ali2woo'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="field field_inline">
                    <div class="field__label">
                        <label>
                            <strong><?php echo esc_html_x('Setup Wizard', 'Wizard', 'ali2woo'); ?></strong>
                        </label>
                        <div class="info-box" data-toggle="tooltip" data-title="<?php echo esc_html_x('It will help you to set up settings for the plugin, use it if you`re newbye in dropshipping.', 'Wizard', 'ali2woo'); ?>"></div>
                    </div>
                    <div class="field__input-wrap">
                        <div class="form-group input-block no-margin">
                            <a href="<?php echo admin_url('admin.php?page=a2wl_wizard') ?>"><?php esc_html_e('Start', 'ali2woo'); ?></a>
                        </div>
                    </div>
                </div>

            </div>       
        </div>

        <div class="container-fluid">
            <div class="row pt20 border-top">
                <div class="col-sm-12">
                    <input class="btn btn-success js-main-submit" type="submit" value="<?php esc_html_e('Save settings', 'ali2woo'); ?>"/>
                </div>
            </div>
        </div>

    </div>
</form>

<script>
    (function ($) {
        $(function () {
            if ($.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip({"placement": "top"});
            }
            
            $('.a2wl-clean-log').on('click', function () {
                $.post(ajaxurl, {action: 'a2wl_clear_log_file'}).done(function (response) {
                    let json = JSON.parse(response);
                    if (json.state !== 'ok') { console.log(json); }
                }).fail(function (xhr, status, error) {
                    console.log(error);
                });
                return false;
            });

            $('.a2wl-run-cron-queue').on('click', function () {
                if(confirm('Are you sure?')){
                    $.post(ajaxurl, {action: 'a2wl_run_cron_import_queue'}).done(function (response) {
                        let json = JSON.parse(response);
                        if (json.state !== 'ok') {
                            console.log(json);
                        }
                    }).fail(function (xhr, status, error) {
                        console.log(error);
                    });
                }
                
                return false;
            });

            $('.a2wl-clean-import-queue').click(function () {
                if(confirm('Are you sure?')){
                    $.post(ajaxurl, {action: 'a2wl_clean_import_queue'}).done(function (response) {
                        let json = $.parseJSON(response);
                        if (json.state !== 'ok') { console.log(json); }
                    }).fail(function (xhr, status, error) {
                        console.log(error);
                    });
                }
                
                return false;
            });

        });
    })(jQuery);
</script>



