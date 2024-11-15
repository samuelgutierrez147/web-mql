<?php

if ( ! function_exists( 'etchy_core_register_standard_with_breadcrumbs_title_layout' ) ) {
	function etchy_core_register_standard_with_breadcrumbs_title_layout( $layouts ) {
		$layouts['standard-with-breadcrumbs'] = 'EtchyCoreStandardWithBreadcrumbsTitle';

		return $layouts;
	}

	add_filter( 'etchy_core_filter_register_title_layouts', 'etchy_core_register_standard_with_breadcrumbs_title_layout' );
}

if ( ! function_exists( 'etchy_core_add_standard_with_breadcrumbs_title_layout_option' ) ) {
	/**
	 * Function that set new value into title layout options map
	 *
	 * @param array $layouts  - module layouts
	 *
	 * @return array
	 */
	function etchy_core_add_standard_with_breadcrumbs_title_layout_option( $layouts ) {
		$layouts['standard-with-breadcrumbs'] = esc_html__( 'Standard with breadcrumbs', 'etchy-core' );

		return $layouts;
	}

	add_filter( 'etchy_core_filter_title_layout_options', 'etchy_core_add_standard_with_breadcrumbs_title_layout_option' );
}

