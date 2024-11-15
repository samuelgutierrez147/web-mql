<article <?php qode_framework_class_attribute( $item_classes ); ?>>
	<div class="qodef-e-inner">
		<?php etchy_core_template_part( 'post-types/portfolio/shortcodes/portfolio-category-list', 'templates/parts/image', '', $params ); ?>
		<div class="qodef-e-content">
			<?php etchy_core_template_part( 'post-types/portfolio/shortcodes/portfolio-category-list', 'templates/parts/title', '', $params ); ?>
			<?php etchy_core_template_part( 'post-types/portfolio/shortcodes/portfolio-category-list', 'templates/parts/description', '', $params ); ?>
		</div>
		<?php etchy_core_template_part( 'post-types/portfolio/shortcodes/portfolio-category-list', 'templates/parts/link', '', $params ); ?>
	</div>
</article>