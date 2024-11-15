<?php

if ( ! function_exists( 'etchy_core_include_blog_single_related_posts_template' ) ) {
	/**
	 * Function which includes additional module on single posts page
	 */
	function etchy_core_include_blog_single_related_posts_template() {
		if ( is_single() ) {
			include_once ETCHY_CORE_INC_PATH . '/blog/templates/single/related-posts/templates/related-posts.php';
		}
	}
	
	add_action( 'etchy_action_after_blog_post_item', 'etchy_core_include_blog_single_related_posts_template', 20 );  // permission 25 is set to define template position
}