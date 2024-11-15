<div <?php qode_framework_class_attribute( $item_classes ); ?>>
	<div class="qodef-e-inner">
		<div class="qodef-e-content">
			<div class="qodef-e-content-top">
				<?php etchy_core_list_sc_template_part( 'post-types/testimonials/shortcodes/testimonials-list', 'post-info/image', '', $params ); ?>
				<?php etchy_core_list_sc_template_part( 'post-types/testimonials/shortcodes/testimonials-list', 'post-info/author', '', $params ); ?>
			</div>
			<?php etchy_core_list_sc_template_part( 'post-types/testimonials/shortcodes/testimonials-list', 'post-info/text', '', $params ); ?>
		</div>
	</div>
</div>