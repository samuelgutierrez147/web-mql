<?php

if ( ! function_exists( 'etchy_core_add_portfolio_single_related_posts_options' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function etchy_core_add_portfolio_single_related_posts_options( $page ) {
		
		if ( $page ) {
			
			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qodef_portfolio_single_enable_related_posts',
					'title'         => esc_html__( 'Related Posts', 'etchy-core' ),
					'description'   => esc_html__( 'Enabling this option will show related posts section below post content on portfolio single', 'etchy-core' ),
					'default_value' => 'no'
				)
			);
		}
	}
	
	add_action( 'etchy_core_action_after_portfolio_options_single', 'etchy_core_add_portfolio_single_related_posts_options' );
}