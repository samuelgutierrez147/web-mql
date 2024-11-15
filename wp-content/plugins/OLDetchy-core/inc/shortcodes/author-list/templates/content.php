<div <?php qode_framework_class_attribute( $holder_classes ); ?> <?php qode_framework_inline_attr( $data_attr, 'data-options' ); ?>>
	<div class="qodef-grid-inner clear">
		<?php
		// Include global masonry template from theme
		etchy_core_theme_template_part( 'masonry', 'templates/sizer-gutter', '', $params['behavior'] );
		
		// Include items
		etchy_core_template_part( 'shortcodes/author-list', 'templates/loop', '', $params );
		?>
	</div>
	<?php
	// Include global pagination from theme
	etchy_core_template_part( 'shortcodes/author-list', 'templates/pagination', $params['pagination_type'], $params ); ?>
</div>