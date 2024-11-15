<div <?php qode_framework_class_attribute( $holder_classes ); ?>>
	<?php if( $info_position == 'left' ) { ?>
		<?php etchy_core_template_part( 'shortcodes/swapping-image-gallery', 'templates/parts/info', '', $params ) ?>
	<?php } ?>
	<?php etchy_core_template_part( 'shortcodes/swapping-image-gallery', 'templates/parts/slider', '', $params ) ?>
	<?php if( empty( $info_position ) || $info_position == 'right' ) { ?>
		<?php etchy_core_template_part( 'shortcodes/swapping-image-gallery', 'templates/parts/info', '', $params ) ?>
	<?php } ?>
</div>