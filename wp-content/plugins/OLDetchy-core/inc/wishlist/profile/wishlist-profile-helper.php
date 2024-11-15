<?php

if ( ! function_exists( 'etchy_core_add_wishlist_profile_navigation_item' ) ) {
	function etchy_core_add_wishlist_profile_navigation_item( $items, $dashboard_url ) {
		$user = wp_get_current_user();
		
		if ( etchy_core_listing_approved_user_roles( $user->roles ) ) {
			$items['my-wishlist'] = array(
				'url'         => esc_url( add_query_arg( array( 'user-action' => 'my-wishlist' ), $dashboard_url ) ),
				'text'        => esc_html__( 'My Wishlist', 'etchy-core' ),
				'user_action' => 'my-wishlist',
				'icon'        => '<svg viewBox="0 0 26 26" enable-background="new 0 0 26 26" xml:space="preserve"><path fill="#A88657" d="M20.875,5.979v4.063h-0.813v10.563H5.438V10.042H4.625V5.979H20.875z M20.063,9.229V6.792H5.438v2.438
		H20.063z M19.25,19.792v-9.75h-13v9.75H19.25z M14.375,11.667c0.474,0,0.863,0.152,1.168,0.457S16,12.818,16,13.292
		c0,0.475-0.152,0.863-0.457,1.168s-0.694,0.457-1.168,0.457h-3.25c-0.475,0-0.863-0.152-1.168-0.457S9.5,13.767,9.5,13.292
		c0-0.474,0.152-0.863,0.457-1.168s0.693-0.457,1.168-0.457H14.375z M14.375,12.479h-0.051h-3.199c-0.542,0-0.813,0.271-0.813,0.813
		c0,0.542,0.271,0.813,0.813,0.813h3.25c0.541,0,0.813-0.271,0.813-0.813C15.188,12.751,14.916,12.479,14.375,12.479z"/></svg>'
			);
		}
		
		return $items;
	}
	
	add_filter( 'etchy_membership_filter_dashboard_navigation_pages', 'etchy_core_add_wishlist_profile_navigation_item', 10, 2 );
}

if ( ! function_exists( 'etchy_core_add_wishlist_profile_navigation_pages' ) ) {
	function etchy_core_add_wishlist_profile_navigation_pages( $html, $action ) {
		
		if ( $action == 'my-wishlist' ) {
			$atts                = array();
			
			$html = etchy_core_get_template_part( 'wishlist', '/profile/templates/my-wishlist', '', $atts );
		}
		
		return $html;
	}
	
	add_filter( 'etchy_membership_filter_dashboard_page', 'etchy_core_add_wishlist_profile_navigation_pages', 10, 2 );
}
