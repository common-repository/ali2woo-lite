<?php
/**
 * @var array $order_data
 * @var array $countries
 */
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div  class="order-info">
    <div class="order-name"><strong><?php echo esc_html__('Order', 'ali2woo'); ?>: </strong>
        <?php if ($order_data['order']->get_status() === 'trash') : ?>
            <strong>#<?php echo esc_attr($order_data['order_number']) . ' ' . esc_html__($order_data['buyer']); ?></strong>
        <?php else: ?>
            <a target="_blank"
               href="<?php echo esc_url(admin_url('post.php?post=' . absint($order_data['order_id'])) . '&action=edit'); ?>"
               class="order-view"><strong>#<?php echo esc_attr($order_data['order_number']) . ' ' . esc_html__($order_data['buyer']); ?></strong>
            </a>
        <?php endif; ?>
    </div>
    <div class="order-ship-to">
        <strong><?php echo esc_html__('Ship to', 'ali2woo'); ?>: </strong>
        <span title="<?php esc_attr($order_data['formatted_address']); ?>">
                <?php echo ($countries[$order_data['shipping_address']['country']] ?? $order_data['formatted_address']); ?></span>
        <a href="#" class="edit"><?php echo esc_html__('Edit'); ?></a>
    </div>
    <div class="order-total">
        <strong><?php echo esc_html__('Total cost', 'ali2woo'); ?>: </strong>
        <span class="total"><?php echo wc_price($order_data['total_cost'], ['currency' => $order_data['currency']]); ?></span>
    </div>
    <div class="order-message"></div>
</div>