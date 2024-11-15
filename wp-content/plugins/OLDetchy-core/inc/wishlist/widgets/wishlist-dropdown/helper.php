<?php

if ( ! function_exists( 'etchy_core_add_rest_api_wishlist_dropdown_global_variables' ) ) {
	/**
	 * Extend main rest api variables with new case
	 *
	 * @param array $global - list of variables
	 * @param string $namespace - rest namespace url
	 *
	 * @return array
	 */
	function etchy_core_add_rest_api_wishlist_dropdown_global_variables( $global, $namespace ) {
		$global['wishlistDropdownRestRoute'] = $namespace . '/wishlistdropdown';
		
		return $global;
	}
	
	add_filter( 'etchy_filter_rest_api_global_variables', 'etchy_core_add_rest_api_wishlist_dropdown_global_variables', 10, 2 );
}

if ( ! function_exists( 'etchy_core_add_rest_api_wishlist_remove_route' ) ) {
	/**
	 * Extend main rest api routes with new case
	 *
	 * @param array $routes - list of rest routes
	 *
	 * @return array
	 */
	function etchy_core_add_rest_api_wishlist_remove_route( $routes ) {
		$routes['wishlistdropdown'] = array(
			'route'    => 'wishlistDropdown',
			'methods'  => WP_REST_Server::CREATABLE,
			'callback' => 'etchy_core_update_wishlist_dropdown_content',
            'permission_callback' => function () {
                return is_user_logged_in();
            },
			'args'     => array(
				'options' => array(
					'required'          => true,
					'validate_callback' => function ( $param, $request, $key ) {
						// Simple solution for validation can be 'is_array' value instead of callback function
						return is_array( $param ) ? $param : (array) $param;
					},
					'description'       => esc_html__( 'Options data is array with reaction and id values', 'etchy-core' )
				)
			)
		);
		
		return $routes;
	}
	
	add_filter( 'etchy_filter_rest_api_routes', 'etchy_core_add_rest_api_wishlist_remove_route' );
}

if ( ! function_exists( 'etchy_core_update_wishlist_dropdown_content' ) ) {
	/**
	 * Function that update wishlist items content on ajax call
	 */
	function etchy_core_update_wishlist_dropdown_content() {
		
		if ( isset( $_POST['options'] ) && is_user_logged_in() ) {
			$data    = $_POST['options'];
			$item_id = intval( $data['itemID'] );
			
			if ( ! empty( $item_id ) ) {
				$count = etchy_core_get_number_of_wishlist_items( intval( $data['userID'] ) );
				
				ob_clean();
				
				$item_params          = array();
				$item_params['id']    = $item_id;
				$item_params['title'] = get_the_title( $item_id );
				
				etchy_core_template_part( 'wishlist', 'widgets/wishlist-dropdown/templates/item', '', $item_params );
				
				$new_html = ob_get_clean();
				
				qode_framework_get_ajax_status( 'success', esc_html__( 'Updated', 'etchy-core' ), array(
					'count'    => $count,
					'new_html' => $new_html
				) );
				
				unset( $_POST['options'] ); // Remove data from global post variable after submission
			} else {
				qode_framework_get_ajax_status( 'error', esc_html__( 'Item ID is invalid.', 'etchy-core' ) );
			}
		} else {
			qode_framework_get_ajax_status( 'error', esc_html__( 'You are not authorized.', 'etchy-core' ) );
		}
	}
}