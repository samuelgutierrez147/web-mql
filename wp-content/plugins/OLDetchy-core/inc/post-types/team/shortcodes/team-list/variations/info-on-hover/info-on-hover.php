<?php

if ( ! function_exists( 'etchy_core_add_team_list_variation_info_on_hover' ) ) {
	function etchy_core_add_team_list_variation_info_on_hover( $variations ) {
		
		$variations['info-on-hover'] = esc_html__( 'Info on Hover', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_team_list_layouts', 'etchy_core_add_team_list_variation_info_on_hover' );
}