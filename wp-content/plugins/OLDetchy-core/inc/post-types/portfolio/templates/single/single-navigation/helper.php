<?php

if ( ! function_exists( 'etchy_core_include_portfolio_single_post_navigation_template' ) ) {
	/**
	 * Function which includes additional module on single portfolio page
	 */
	function etchy_core_include_portfolio_single_post_navigation_template() {
		etchy_core_template_part( 'post-types/portfolio', 'templates/single/single-navigation/templates/single-navigation' );
	}
	
	add_action( 'etchy_core_action_after_portfolio_single_item', 'etchy_core_include_portfolio_single_post_navigation_template' );
}