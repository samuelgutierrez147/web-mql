<?php

if ( ! function_exists( 'etchy_core_add_admin_user_options' ) ) {
	function etchy_core_add_admin_user_options() {
		$qode_framework = qode_framework_get_framework_root();
		
		$page = $qode_framework->add_options_page(
			array(
				'scope' => array( 'administrator', 'author' ),
				'type'  => 'user',
				'title' => esc_html__( 'Social Networks', 'etchy-core' ),
				'slug'  => 'admin-options',
			)
		);
		
		if ( $page ) {
			$page->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_user_facebook',
					'title'       => esc_html__( 'Facebook', 'etchy-core' ),
					'description' => esc_html__( 'Enter user Facebook profile URL', 'etchy-core' ),
				)
			);
			
			$page->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_user_instagram',
					'title'       => esc_html__( 'Instagram', 'etchy-core' ),
					'description' => esc_html__( 'Enter user Instagram profile URL', 'etchy-core' ),
				)
			);
			
			$page->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_user_twitter',
					'title'       => esc_html__( 'Twitter', 'etchy-core' ),
					'description' => esc_html__( 'Enter user Twitter profile URL', 'etchy-core' ),
				)
			);
			
			$page->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_user_linkedin',
					'title'       => esc_html__( 'LinkedIn', 'etchy-core' ),
					'description' => esc_html__( 'Enter user LinkedIn profile URL', 'etchy-core' ),
				)
			);
			
			$page->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_user_pinterest',
					'title'       => esc_html__( 'Pinterest', 'etchy-core' ),
					'description' => esc_html__( 'Enter user Pinterest profile URL', 'etchy-core' ),
				)
			);
			
			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_admin_user_options_map', $page );
		}
	}
	
	add_action( 'etchy_core_action_register_role_custom_fields', 'etchy_core_add_admin_user_options' );
}