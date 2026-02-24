<?php
if ( ! defined('ABSPATH') ) exit;

$order_id = absint( get_query_var('order-received') );
$order    = wc_get_order($order_id);

$status_class = '';
$status_label = '';

if ( $order ) {
    $status = $order->get_status();

    $status_class = match ($status) {
        'processing', 'completed' => 'status-compra',
        'pending', 'on-hold'      => 'status-pending',
        'failed', 'cancelled'     => 'status-failed',
        default                  => 'status-neutral',
    };

    $status = 'completed';

    $status_label = match ($status) {
        'processing' => 'En proceso',
        'completed'  => 'Completado',
        'pending'    => 'Pendiente de pago',
        'on-hold'    => 'En espera',
        'failed'     => 'Fallido',
        'cancelled'  => 'Cancelado',
        default      => ucfirst($status),
    };
}
?>

<div class="thankyou-wrap">

    <header class="thankyou-hero <?php echo esc_attr($status_class); ?>">
        <?php if ( $order ) : ?>
            <div class="thankyou-hero__row">
                <div class="thankyou-hero__title">
                    <h1>¡Gracias por tu compra!</h1>
                    <p>Tu pedido ha sido recibido correctamente.</p>
                </div>

                <div class="thankyou-hero__badge" aria-label="Estado del pedido">
                    <span class="thankyou-badge__text"><?php echo esc_html($status_label); ?></span>
                </div>
            </div>
        <?php else : ?>
            <div class="thankyou-hero__row">
                <div class="thankyou-hero__title">
                    <h1>Error en el pedido</h1>
                    <p>No se encontró la información del pedido.</p>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <?php if ( $order ) : ?>

        <?php
        $cod_pedido_optimus = get_post_meta($order->get_id(), '_optimus_cod_pedido', true);
        $numero_pedido      = ! empty($cod_pedido_optimus) ? $cod_pedido_optimus : $order->get_order_number();

        $date_created = $order->get_date_created();
        $date_str     = $date_created ? wc_format_datetime($date_created) : '';
        $payment      = $order->get_payment_method_title();
        ?>

        <section class="thankyou-grid">

            <!-- Resumen -->
            <div class="thankyou-card">
                <div class="thankyou-card__head">
                    <h2>Resumen del pedido</h2>
                </div>

                <div class="thankyou-kv">
                    <div class="thankyou-kv__row">
                        <span class="thankyou-kv__k">Número</span>
                        <span class="thankyou-kv__v"><?php echo esc_html($numero_pedido); ?></span>
                    </div>

                    <div class="thankyou-kv__row">
                        <span class="thankyou-kv__k">Fecha</span>
                        <span class="thankyou-kv__v"><?php echo esc_html($date_str); ?></span>
                    </div>

                    <div class="thankyou-kv__row">
                        <span class="thankyou-kv__k">Total</span>
                        <span class="thankyou-kv__v"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></span>
                    </div>

                    <div class="thankyou-kv__row">
                        <span class="thankyou-kv__k">Pago</span>
                        <span class="thankyou-kv__v"><?php echo wp_kses_post($payment); ?></span>
                    </div>
                </div>
            </div>

            <!-- Productos -->
            <div class="thankyou-card">
                <div class="thankyou-card__head thankyou-card__head--row">
                    <h2>Productos</h2>
                    <span class="thankyou-card__sub">
            <?php echo esc_html( sprintf('%d artículo(s)', count($order->get_items())) ); ?>
          </span>
                </div>

                <div class="thankyou-table-wrap">
                    <table class="thankyou-table">
                        <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="th-right">Cantidad</th>
                            <th class="th-right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $order->get_items() as $item_id => $item ) : ?>
                            <tr>
                                <td class="td-product">
                                    <?php echo esc_html( $item->get_name() ); ?>
                                </td>
                                <td class="th-right">
                                    x<?php echo esc_html( $item->get_quantity() ); ?>
                                </td>
                                <td class="th-right">
                                    <?php echo wp_kses_post( wc_price( $item->get_total() ) ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Totales (opcional pero útil) -->
                <div class="thankyou-totals">
                    <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                        <div class="thankyou-totals__row">
                            <span class="thankyou-totals__k"><?php echo esc_html( $total['label'] ); ?></span>
                            <span class="thankyou-totals__v"><?php echo wp_kses_post( $total['value'] ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

        </section>

        <div class="thankyou-actions">
            <a class="thankyou-btn thankyou-btn--ghost" href="<?php echo esc_url( home_url('/productos') ); ?>">
                Seguir comprando
            </a>

            <a class="thankyou-btn thankyou-btn--primary" href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                Ver pedido
            </a>
        </div>

    <?php else : ?>
        <p class="thankyou-error">Hubo un error al procesar tu pedido.</p>
    <?php endif; ?>

</div>