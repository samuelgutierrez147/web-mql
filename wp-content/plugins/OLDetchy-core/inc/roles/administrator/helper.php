<?php

if ( ! function_exists( 'etchy_core_add_administrator_role_caps' ) ) {
	function etchy_core_add_administrator_role_caps() {
		
		// Add the roles you'd like to administer the custom post types
		$roles = apply_filters( 'etchy_core_filter_administrator_roles', array( 'editor', 'administrator' ) );
		
		// Allowed custom post types
		$cpt_items = apply_filters( 'etchy_core_filter_administrator_cpts', array() );
		
		// Loop through each role and assign capabilities
		foreach ( $roles as $the_role ) {
			$role = get_role( $the_role );
			
			if ( count( $cpt_items ) ) {
				
				foreach ( $cpt_items as $cpt ) {
					$role->add_cap( 'read_' . $cpt );
					$role->add_cap( 'read_private_' . $cpt . 's' );
					$role->add_cap( 'edit_' . $cpt );
					$role->add_cap( 'edit_' . $cpt . 's' );
					$role->add_cap( 'edit_others_' . $cpt . 's' );
					$role->add_cap( 'edit_published_' . $cpt . 's' );
					$role->add_cap( 'publish_' . $cpt . 's' );
					$role->add_cap( 'delete_' . $cpt . 's' );
					$role->add_cap( 'delete_others_' . $cpt . 's' );
					$role->add_cap( 'delete_private_' . $cpt . 's' );
					$role->add_cap( 'delete_published_' . $cpt . 's' );
				}
			}
		}
	}
	
	add_action( 'etchy_core_action_plugin_loaded', 'etchy_core_add_administrator_role_caps' );
}