<div <?php qode_framework_class_attribute( $holder_classes ); ?>>
	<?php etchy_core_template_part( 'shortcodes/image-with-text', 'templates/parts/image', '', $params ) ?>
	<div class="qodef-m-content">
		<?php etchy_core_template_part( 'shortcodes/image-with-text', 'templates/parts/title', '', $params ) ?>
		<?php etchy_core_template_part( 'shortcodes/image-with-text', 'templates/parts/text', '', $params ) ?>
	</div>
</div>