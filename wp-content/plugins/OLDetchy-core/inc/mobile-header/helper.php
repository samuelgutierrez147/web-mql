<?php

if ( ! function_exists( 'etchy_core_dependency_for_mobile_menu_typography_options' ) ) {
	function etchy_core_dependency_for_mobile_menu_typography_options() {
		return apply_filters( 'etchy_core_filter_mobile_menu_typography_hide_option', $hide_dep_options = array() );
	}
}