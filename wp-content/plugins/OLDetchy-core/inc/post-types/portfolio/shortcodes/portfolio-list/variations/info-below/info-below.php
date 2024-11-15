<?php

if ( ! function_exists( 'etchy_core_add_portfolio_list_variation_info_below' ) ) {
	function etchy_core_add_portfolio_list_variation_info_below( $variations ) {
		
		$variations['info-below'] = esc_html__( 'Info Below', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_portfolio_list_layouts', 'etchy_core_add_portfolio_list_variation_info_below' );
}

if ( ! function_exists( 'etchy_core_add_portfolio_list_options_info_below' ) ) {
	function etchy_core_add_portfolio_list_options_info_below( $options ) {
		$info_below_options   = array(
			array(
				'field_type' => 'text',
				'name'       => 'info_below_content_margin_top',
				'title'      => esc_html__( 'Content Top Margin', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'layout' => array(
							'values'        => 'info-below',
							'default_value' => ''
						)
					)
				),
				'group'      => esc_html__( 'Layout', 'etchy-core' )
			),
			array(
				'field_type' => 'text',
				'name'       => 'info_below_content_margin_bottom',
				'title'      => esc_html__( 'Content Bottom Margin', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'layout' => array(
							'values'        => 'info-below',
							'default_value' => ''
						)
					)
				),
				'group'      => esc_html__( 'Layout', 'etchy-core' )
			)
		);

		return array_merge( $options, $info_below_options );
	}
	
	add_filter( 'etchy_core_filter_portfolio_list_extra_options', 'etchy_core_add_portfolio_list_options_info_below' );
}