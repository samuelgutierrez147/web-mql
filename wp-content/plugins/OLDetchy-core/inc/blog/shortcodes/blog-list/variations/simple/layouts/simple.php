<article <?php post_class( $item_classes ); ?>>
	<div class="qodef-e-inner">
		<?php etchy_core_template_part( 'blog/shortcodes/blog-list', 'templates/post-info/image', '', $params ); ?>
		<div class="qodef-e-content" <?php qode_framework_inline_style( $content_styles ); ?>>
			<?php etchy_core_template_part( 'blog/shortcodes/blog-list', 'templates/post-info/title', '', $params ); ?>
		</div>
	</div>
</article>