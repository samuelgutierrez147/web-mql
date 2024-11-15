<?php

if ( ! function_exists( 'etchy_core_add_pricing_table_variation_standard' ) ) {
	function etchy_core_add_pricing_table_variation_standard( $variations ) {
		
		$variations['standard'] = esc_html__( 'Standard', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_pricing_table_layouts', 'etchy_core_add_pricing_table_variation_standard' );
}
