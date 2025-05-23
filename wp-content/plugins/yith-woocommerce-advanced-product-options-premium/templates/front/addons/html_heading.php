<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * WAPO Template
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 *
 * @var object $addon
 * @var array  $settings
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

//Settings configuration.
extract($settings );

// Obtener el valor del campo personalizado "ID"
$custom_id = $addon->get_setting('addon_identificador', '', false); // Reemplaza 'id' por el nombre exacto que usaste en tu configuración

?>

<<?php echo esc_attr( $heading_type ); ?> style="color: <?php echo esc_attr( $heading_color ); ?>;">

	<?php echo wp_kses_post( $heading_text ); ?>

</<?php echo esc_attr( $heading_type ); ?>>
