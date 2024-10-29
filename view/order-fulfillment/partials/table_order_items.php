<?php
/**
 * @var string $table_class
 * @var array $columns
 * @var array $order_data
 */

use AliNext_Lite\Utils;

// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<table class="wp-list-table widefat striped table-view-list fulfillment-order-items <?php echo $table_class; ?>">
    <thead>
    <?php foreach ($columns as $column): ?>
    <th
        <?php if (!empty($column['colspan'])): ?> colspan="<?php echo $column['colspan']; ?>" <?php endif; ?>
        class="<?php echo $column['class']; ?>">
        <?php echo $column['title']; ?>
    </th>
    <?php endforeach; ?>
    </thead>
    <body>
    <?php foreach ($order_data['items'] as $item) : ?>
    <tr data-order_item_id="<?php echo esc_attr($item['order_item_id']); ?>">
        <td class="photo"><?php echo Utils::wp_kses_post($item['image']); ?></td>
        <td class="name">
            <a target="_blank" href="#"><?php echo esc_html__($item['name']); ?></a>
            <?php if ($item['attributes']) : ?>
                <div class="info attributes">
                    <strong><?php echo esc_html__('Attribute', 'ali2woo'); ?>: </strong>
                    <div><?php echo Utils::wp_kses_post($item['attributes']); ?></div>
                </div>
            <?php endif; ?>

            <div class="info sku">
                <strong><?php echo esc_html__('Sku', 'ali2woo'); ?>: </strong>
                <?php echo esc_html__($item['sku']); ?>
            </div>
            <div class="item-message"></div>
        </td>
        <td class="shipping_company">
            <?php echo esc_html__($item['current_shipping']); ?>
        </td>
        <td class="delivery_time">
            <?php echo esc_html__($item['delivery_time'] . ' days'); ?>
        </td>
        <td class="shipping_cost">
            <?php echo $item['shipping_cost'] ?
                wc_price($item['shipping_cost'], ['currency' => $order_data['currency']]) :
                esc_html__('Free Shipping', 'ali2woo'); ?>
        </td>
        <td class="cost">
            <?php echo wc_price($item['cost'], ['currency' => $order_data['currency']]) . ' x ' . esc_html__($item['quantity']); ?> =
            <strong>
                <?php echo wc_price($item['cost'] * $item['quantity'], ['currency' => $order_data['currency']]); ?>
            </strong>
        </td>
        <td class="total_cost">
            <strong> <?php echo wc_price($item['total_cost'], ['currency' => $order_data['currency']]); ?></strong>
        </td>
        <td class="actions">
            <a class="remove-item" href="#"></a>
        </td>
    </tr>
    <?php endforeach; ?>
    </body>
</table>

