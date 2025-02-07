<?php
if (!defined('ABSPATH')) {
    exit;
}

$order_id = absint(get_query_var('order-received'));
$order = wc_get_order($order_id);
$status_class = '';

if ($order) {
    $status = $order->get_status();

    // Colores para diferentes estados de pedido
    $status_class = match ($status) {
        'processing', 'completed' => 'status-compra',
        'pending', 'on-hold' => 'status-pending',
        'failed', 'cancelled' => 'status-failed',
        default => '',
    };
}
?>

<div class="thankyou-container">
    <div class="thankyou-header <?= esc_attr($status_class); ?>">
        <?php if ($order): ?>
            <h1>🎉 ¡Gracias por tu compra!</h1>
            <p>Tu pedido ha sido recibido correctamente.</p>
        <?php else: ?>
            <h1>⚠️ Error en el pedido</h1>
            <p>No se encontró la información del pedido.</p>
        <?php endif; ?>
    </div>

    <?php if ($order): ?>
        <div class="thankyou-details">
            <div class="order-summary">
                <h2>📦 Resumen del Pedido</h2>
                <?php
                $cod_pedido_optimus = get_post_meta($order->get_id(), '_optimus_cod_pedido', true);
                $numero_pedido = !empty($cod_pedido_optimus) ? $cod_pedido_optimus : $order->get_order_number();
                ?>
                <p><strong>Número de Pedido:</strong> <?= $numero_pedido; ?></p>
                <p><strong>Fecha:</strong> <?= wc_format_datetime($order->get_date_created()); ?></p>
                <p><strong>Total:</strong> <?= $order->get_formatted_order_total(); ?></p>
                <p><strong>Método de Pago:</strong> <?= wp_kses_post($order->get_payment_method_title()); ?></p>
            </div>

            <div class="order-items">
                <h2>🛍 Productos</h2>
                <table class="order-table">
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($order->get_items() as $item_id => $item): ?>
                        <tr>
                            <td><?= $item->get_name(); ?></td>
                            <td>x<?= $item->get_quantity(); ?></td>
                            <td><?= wc_price($item->get_total()); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="thankyou-actions">
            <a href="<?= esc_url(home_url('/productos')); ?>" class="button primary"><i class="fas fa-shopping-cart"></i> Seguir Comprando</a>
            <a href="<?= esc_url($order->get_view_order_url()); ?>" class="button secondary"><i class="fas fa-file"></i> Ver Pedido</a>
        </div>
    <?php else: ?>
        <p class="error-message">⚠️ Hubo un error al procesar tu pedido.</p>
    <?php endif; ?>
</div>