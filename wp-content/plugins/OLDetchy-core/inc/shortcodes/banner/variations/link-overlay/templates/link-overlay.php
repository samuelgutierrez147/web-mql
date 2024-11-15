<div <?php qode_framework_class_attribute( $holder_classes ); ?>>
	<?php etchy_core_template_part( 'shortcodes/banner', 'templates/parts/image', '', $params ) ?>
	<?php etchy_core_template_part( 'shortcodes/banner', 'templates/parts/additional-image', '', $params ) ?>
	<div class="qodef-m-content">
		<div class="qodef-m-content-inner">
			<?php etchy_core_template_part( 'shortcodes/banner', 'templates/parts/subtitle', '', $params ) ?>
			<?php etchy_core_template_part( 'shortcodes/banner', 'templates/parts/title', '', $params ) ?>
			<?php etchy_core_template_part( 'shortcodes/banner', 'templates/parts/text', '', $params ) ?>
		</div>
	</div>
	<?php etchy_core_template_part( 'shortcodes/banner', 'templates/parts/link', '', $params ) ?>
</div>