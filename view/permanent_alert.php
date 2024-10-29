<?php
use AliNext_Lite\PermanentAlert;
/**
 * @var array|PermanentAlert[] $PermanentAlerts
 * @var PermanentAlert $PermanentAlert
 */
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>

<?php foreach ($PermanentAlerts as $PermanentAlert): ?>
    <div class="permanent-alert permanent-alert-<?php echo $PermanentAlert->getType(); ?>">
        <?php echo $PermanentAlert->getContent(); ?>
    </div>
<?php endforeach; ?>
