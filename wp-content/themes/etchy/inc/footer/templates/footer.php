<footer id="qodef-page-footer" <?php etchy_class_attribute( implode( ' ', apply_filters( 'etchy_filter_footer_holder_classes', array() ) ) ); ?>>
	<?php
	// Hook to include additional content before page footer content
	do_action( 'etchy_action_before_page_footer_content' );
	
	// Include module content template
	echo apply_filters( 'etchy_filter_footer_content_template', etchy_get_template_part( 'footer', 'templates/footer-content' ) );
	
	// Hook to include additional content after page footer content
	do_action( 'etchy_action_after_page_footer_content' );
	?>
</footer>