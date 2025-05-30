<?php
/**
 * Template to display a pricing table in a list
 *
 * @package WooCommerce-One-Page-Checkout/Templates
 * @version 1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="opc-pricing-table-wrapper opc_columns_<?php echo esc_attr( count( $products ) ); ?>">
<?php foreach( $products as $product ) : ?>
	<div class="opc-pricing-table-product product-item cart <?php if ( wcopc_get_products_prop( $product, 'in_cart' ) ) echo 'selected'; ?>">
		<div class="opc-pricing-table-product-header">
			<h3 class="opc-pricing-table-product-title"><?php echo wp_kses_post( $product->get_title() ); ?></h3>
			<div class="opc-pricing-table-product-price">
				<p><?php echo $product->get_price_html(); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</div>
			<div class="product-quantity">
				<?php wc_get_template( 'checkout/add-to-cart/opc.php', array( 'product' => $product ), '', PP_One_Page_Checkout::$template_path ); ?>
			</div>
		</div>

		<?php if ( $product->has_attributes() || $product->is_type( 'variation' ) ) : ?>
			<!-- Product Attributes -->
			<div class="opc-pricing-table-product-attributes">

				<?php if ( $product->is_type( 'variation' ) ) : ?>
					<?php foreach( $product->get_variation_attributes() as $attribute_title => $attribute_value ) : ?>
				<h4 class="attribute_title"><?php echo wc_attribute_label( str_replace( 'attribute_', '', $attribute_title ) ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h4>
				<?php $parent = is_callable( array( $product, 'get_parent_id' ) ) ? wc_get_product( $product->get_parent_id() ): $product->parent; ?>
				<p><?php echo PP_One_Page_Checkout::get_formatted_attribute_value( $attribute_title, $attribute_value, $parent->get_attributes() ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<?php endforeach; ?>
				<?php else : ?>
					<?php foreach( $product->get_attributes() as $attribute ) :
							if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
								continue;
							} ?>
				<h4 class="attribute_title"><?php echo wc_attribute_label( $attribute['name'] ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h4>
				<p><?php
					if ( $attribute['is_taxonomy'] ) {
						$values = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'names' ) );
						foreach ( $values as $attribute_value ) {
							echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( $attribute_value ) ), $attribute, $values );  // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					} else {
						// Convert pipes to commas and display values
						$values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
						foreach ( $values as $attribute_value ) {
							echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( $attribute_value ) ), $attribute, $values );  // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					}
					?>
				</p>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php endif; //$product->has_attributes() ?>

		<?php if ( $product->has_weight() || $product->has_dimensions() ) : ?>
			<div class="opc-pricing-table-product-dimensions">
			<?php if ( $product->has_weight() ) : ?>
				<!-- Product Weight -->
				<h4><?php esc_html_e( 'Weight', 'woocommerce-one-page-checkout' ) ?></h4>
				<p class="product_weight"><?php echo wc_format_weight( $product->get_weight() ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			<?php if ( $product->has_dimensions() ) : ?>
			<!-- Product Dimension -->
				<h4><?php esc_html_e( 'Dimensions', 'woocommerce-one-page-checkout' ) ?></h4>
				<p class="product_dimensions"><?php echo wc_format_dimensions( $product->get_dimensions( false ) ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			</div>
		<?php endif; // $product->enable_dimensions_display() ?>
	</div>
<?php endforeach; // product in product_post?>
</div>
<div class="clear"></div>
