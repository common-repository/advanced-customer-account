<?php

/**
 *  Woocommerce account page edit
 * 
 */



function advanced_customer_account_account_dashboard()
{
    // Set limit
    $limit = 3;

    // Get customer $limit last orders
    $customer_orders = wc_get_orders(array(
        'customer'  => get_current_user_id(),
        'limit'     => $limit
    ));

    // Count customers orders
    $count = count($customer_orders);

    // Greater than or equal to
    if ($count >= 1) {
        // Message
        echo wp_kses_post('<p class="advc-order-list-title">' . sprintf(_n('Your last order', 'Your last %s orders', $count, 'advanced-customer-account'), $count) . '</p>');
?>
        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
            <thead>
                <tr>
                    <?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) : ?>
                        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr($column_id); ?>"><span class="nobr"><?php echo esc_html($column_name); ?></span></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($customer_orders as $customer_order) {
                    $order      = wc_get_order($customer_order); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                    $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                ?>
                    <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr($order->get_status()); ?> order">
                        <?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) : ?>
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>">
                                <?php if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) : ?>
                                    <?php do_action('woocommerce_my_account_my_orders_column_' . $column_id, $order); ?>

                                <?php elseif ('order-number' === $column_id) : ?>
                                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                        <?php echo esc_html(_x('#', 'hash before order number', 'advanced-customer-account') . $order->get_order_number()); ?>
                                    </a>

                                <?php elseif ('order-date' === $column_id) : ?>
                                    <time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></time>

                                <?php elseif ('order-status' === $column_id) : ?>
                                    <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>

                                <?php elseif ('order-total' === $column_id) : ?>
                                    <?php
                                    /* translators: 1: formatted order total 2: total order items */
                                    echo wp_kses_post(sprintf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'advanced-customer-account'), $order->get_formatted_order_total(), $item_count));
                                    ?>

                                <?php elseif ('order-actions' === $column_id) : ?>
                                    <?php
                                    $actions = wc_get_account_orders_actions($order);

                                    if (!empty($actions)) {
                                        foreach ($actions as $key => $action) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                            echo '<a href="' . esc_url($action['url']) . '" class="woocommerce-button button ' . sanitize_html_class($key) . '">' . esc_html($action['name']) . '</a>';
                                        }
                                    }
                                    ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

    <?php
    } else {
    ?>
        <div class="my-5 border-bottom">
            <h3 class="fw-bold advc-miss-title"><?php esc_html_e('No order has been made yet.', 'advanced-customer-account'); ?></h3>
            <div class="text-left">
                <p class="lead mb-4"><?php esc_html_e('Visit our shop page and buy your favorite products.', 'advanced-customer-account'); ?></p>
                <div class="d-grid gap-2 d-sm-flex mb-5">
                    <a class="advc-miss-btn btn btn-primary btn-lg px-4 me-sm-3" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>"><?php esc_html_e('Browse products', 'advanced-customer-account'); ?></a>
                </div>
            </div>
        </div>
<?php
    }
}
add_action('woocommerce_account_dashboard', 'advanced_customer_account_account_dashboard');
