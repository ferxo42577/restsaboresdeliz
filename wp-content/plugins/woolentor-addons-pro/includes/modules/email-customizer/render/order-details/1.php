<?php
/**
 * Order Details - Style 1
 */

$customer_note = isset( $settings['order_details_customer_note'] ) ? rest_sanitize_boolean( $settings['order_details_customer_note'] ) : true;

$items = $order->get_items();
$show_sku = $sent_to_admin;
$show_purchase_note = ( ( $order->is_paid() && ! $sent_to_admin ) ? true : false );
$show_image = false;
$image_size = array( 32, 32 );

if ( is_array( $items ) && ! empty( $items ) ) {
    ?>
    <table class="order-details-table" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?php esc_html_e( 'Product', 'woolentor-pro' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Quantity', 'woolentor-pro' ); ?></th>
                <th scope="col"><?php esc_html_e( 'Price', 'woolentor-pro' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ( $items as $item_id => $item ) {
                $product       = $item->get_product();
                $sku           = '';
                $purchase_note = '';
                $image         = '';

                if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
                    continue;
                }

                if ( is_object( $product ) ) {
                    $sku           = $product->get_sku();
                    $purchase_note = $product->get_purchase_note();
                    $image         = $product->get_image( $image_size );
                }
                ?>
                <tr class="order-details-item">
                    <td class="item">
                    <?php
                    if ( $show_image ) {
                        echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
                    }

                    echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

                    if ( $show_sku && $sku ) {
                        echo wp_kses_post( ' (#' . $sku . ')' );
                    }

                    do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

                    wc_display_item_meta(
                        $item,
                        array(
                            'label_before' => '<strong class="wc-item-meta-label">',
                        )
                    );

                    do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );
                    ?>
                    </td>
                    <td class="qty">
                        <?php
                        $qty          = $item->get_quantity();
                        $refunded_qty = $order->get_qty_refunded_for_item( $item_id );

                        if ( $refunded_qty ) {
                            $qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
                        } else {
                            $qty_display = esc_html( $qty );
                        }
                        echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $qty_display, $item ) );
                        ?>
                    </td>
                    <td class="price">
                        <?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
                    </td>
                </tr>
                <?php
                if ( $show_purchase_note && $purchase_note ) {
                    ?>
                    <tr>
                        <td colspan="3">
                            <?php
                            echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <?php
            $item_totals = $order->get_order_item_totals();

            if ( $item_totals ) {
                $i = 0;
                foreach ( $item_totals as $total ) {
                    $i++;
                    ?>
                    <tr>
                        <th scope="row" colspan="2"><?php echo wp_kses_post( $total['label'] ); ?></th>
                        <td><?php echo wp_kses_post( $total['value'] ); ?></td>
                    </tr>
                    <?php
                }
            }
            if ( true === $customer_note && $order->get_customer_note() ) {
                ?>
                <tr>
                    <th scope="row" colspan="2"><?php esc_html_e( 'Note:', 'woolentor-pro' ); ?></th>
                    <td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
                </tr>
                <?php
            }
            ?>
        </tfoot>
    </table>
    <?php
}