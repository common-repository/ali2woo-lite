<?php
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<h1><?php echo esc_html_x('AliNext (Lite version)', 'Setting title', 'ali2woo'); ?></h1>
<div class="a2wl-content">
    <?php include_once A2WL()->plugin_path() . '/view/chrome_notify.php'; ?>
    <?php include_once A2WL()->plugin_path() . '/view/permanent_alert.php'; ?>
    <ul class="tabs">
      <?php foreach($modules as $module):?>
      <li class="tabs__tab <?php echo $current_module == $module['id'] ? 'active' : ""; ?>" role="presentation" ><a class="tabs__link" href="<?php echo admin_url('admin.php?page=a2wl_setting&subpage='.$module['id']); ?>"><?php echo $module['name'] ?></a></li>
      <?php endforeach; ?>
    </ul>
