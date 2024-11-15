<?php

if ( ! function_exists( 'etchy_core_include_twitter_shortcodes' ) ) {
	/**
	 * Function that includes shortcodes
	 */
	function etchy_core_include_twitter_shortcodes() {
		foreach ( glob( ETCHY_CORE_PLUGINS_PATH . '/twitter/shortcodes/*/include.php' ) as $shortcode ) {
			include_once $shortcode;
		}
	}
	
	add_action( 'qode_framework_action_before_shortcodes_register', 'etchy_core_include_twitter_shortcodes' );
}

if ( ! function_exists( 'etchy_core_include_twitter_widgets' ) ) {
	/**
	 * Function that includes widgets
	 */
	function etchy_core_include_twitter_widgets() {
		foreach ( glob( ETCHY_CORE_PLUGINS_PATH . '/twitter/shortcodes/*/widget/include.php' ) as $widget ) {
			include_once $widget;
		}
	}
	
	add_action( 'qode_framework_action_before_widgets_register', 'etchy_core_include_twitter_widgets' );
}

if ( ! function_exists( 'etchy_core_include_twitter_plugin_is_installed' ) ) {
	/**
	 * Function that set case is installed element for framework functionality
	 *
	 * @param bool $installed
	 * @param string $plugin - plugin name
	 *
	 * @return bool
	 */
	function etchy_core_include_twitter_plugin_is_installed( $installed, $plugin ) {
		if( $plugin === 'twitter' ) {
			return defined( 'CTF_VERSION' );
		}
		
		return $installed;
	}
	
	add_filter( 'qode_framework_filter_is_plugin_installed', 'etchy_core_include_twitter_plugin_is_installed', 10, 2 );
}