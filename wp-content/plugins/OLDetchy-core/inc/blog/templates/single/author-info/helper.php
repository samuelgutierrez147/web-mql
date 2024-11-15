<?php

if ( ! function_exists( 'etchy_core_include_blog_single_author_info_template' ) ) {
	/**
	 * Function which includes additional module on single posts page
	 */
	function etchy_core_include_blog_single_author_info_template() {
		if ( is_single() ) {
			include_once ETCHY_CORE_INC_PATH . '/blog/templates/single/author-info/templates/author-info.php';
		}
	}
	
	add_action( 'etchy_action_after_blog_post_item', 'etchy_core_include_blog_single_author_info_template', 15 );  // permission 15 is set to define template position
}

if ( ! function_exists( 'etchy_core_get_author_social_networks' ) ) {
	/**
	 * Function which includes author info templates on single posts page
	 */
	function etchy_core_get_author_social_networks( $user_id ) {
		$icons           = array();
		$social_networks = array(
			'facebook'  => 'fb',
			'twitter'   => 'tw',
			'linkedin'  => 'lnkd',
			'instagram' => 'inst',
			'pinterest' => 'pin'
		);
		
		foreach ( $social_networks as $network => $net_text ) {
			$network_meta = get_the_author_meta( 'qodef_user_' . $network, $user_id );
			
			if ( ! empty( $network_meta ) ) {
				$$network = array(
					'url'   => $network_meta,
					'icon'  => 'social_' . $network,
					'text'  => $net_text,
					'class' => 'qodef-user-social-' . $network
				);
				
				$icons[ $network ] = $$network;
			}
		}
		
		return $icons;
	}
}