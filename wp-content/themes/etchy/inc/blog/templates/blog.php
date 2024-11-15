<div class="qodef-grid-item <?php echo esc_attr( etchy_get_page_content_sidebar_classes() ); ?>">
	<?php
	// Hook to include additional content before blog loop
	do_action( 'etchy_action_before_blog_loop' );
	?>
	<div class="qodef-blog qodef-m <?php echo esc_attr( etchy_get_blog_holder_classes() ); ?>">
		<?php
		// Include posts loop
		etchy_template_part( 'blog', 'templates/parts/loop' );
		
		if ( ! is_single() ) {
			// Include pagination
			etchy_template_part( 'pagination', 'templates/pagination-wp' );
		}
		?>
	</div>
</div>