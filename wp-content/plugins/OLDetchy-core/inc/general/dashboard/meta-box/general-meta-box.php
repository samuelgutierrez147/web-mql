<?php

if ( ! function_exists( 'etchy_core_add_general_meta_box' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function etchy_core_add_general_meta_box() {
		$qode_framework = qode_framework_get_framework_root();
		
		$page = $qode_framework->add_options_page(
			array(
				'scope'  => apply_filters( 'etchy_core_filter_general_meta_box_scope', array( 'post', 'page' ) ),
				'type'   => 'meta',
				'slug'   => 'general',
				'title'  => esc_html__( 'Etchy Settings', 'etchy-core' ),
				'layout' => 'tabbed'
			)
		);
		
		if ( $page ) {
			
			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_general_meta_box_map', $page );
		}
	}
	
	add_action( 'etchy_core_action_default_meta_boxes_init', 'etchy_core_add_general_meta_box' );
}