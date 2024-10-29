<?php
/**
 * @var array $order_data
 * @var string $urls_to_data
 */
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div class="single-order-wrap"
     data-order_id="<?php echo esc_attr($order_data['order_id']); ?>"
     data-shiping_to_country="<?php echo $order_data['shiping_to_country']; ?>"
     data-urls="<?php echo $urls_to_data; ?>">

    <?php include 'partials/order_info.php'; ?>
    <?php include 'partials/order_edit_address_form.php'; ?>
    <?php include 'partials/fulfillment_order_items.php'; ?>
    <div class="additional-fulfillment-service"></div>
</div>
