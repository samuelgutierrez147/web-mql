<?php

if ( ! function_exists( 'etchy_include_justified_gallery_scripts' ) ) {
	/**
	 * Function that enqueue modules 3rd party scripts
	 *
	 * @param array $atts
	 */
	function etchy_include_justified_gallery_scripts( $atts ) {
		
		if ( isset( $atts['behavior'] ) && $atts['behavior'] == 'justified-gallery' ) {
			wp_enqueue_script( 'justified-gallery', ETCHY_INC_ROOT . '/justified-gallery/assets/js/plugins/jquery.justifiedGallery.min.js', array( 'jquery' ), true );
		}
	}
	
	add_action( 'etchy_core_action_list_shortcodes_load_assets', 'etchy_include_justified_gallery_scripts' );
}

if ( ! function_exists( 'etchy_register_justified_gallery_scripts_for_list_shortcodes' ) ) {
	/**
	 * Function that set module 3rd party scripts for list shortcodes
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function etchy_register_justified_gallery_scripts_for_list_shortcodes( $scripts ) {

		$scripts['justified-gallery'] = array(
			'registered' => true
		);

		return $scripts;
	}

	add_filter( 'etchy_core_filter_register_list_shortcode_scripts', 'etchy_register_justified_gallery_scripts_for_list_shortcodes' );
}
