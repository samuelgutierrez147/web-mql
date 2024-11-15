<?php

if ( ! function_exists( 'etchy_core_filter_portfolio_list_info_on_hover_animation_options' ) ) {
	function etchy_core_filter_portfolio_list_info_on_hover_animation_options( $options ) {
		$hover_option  = array();
		$option_filter = apply_filters( 'etchy_core_filter_portfolio_list_info_on_hover_animation_options', array() );
		$options_map   = etchy_core_get_variations_options_map( $option_filter );
		
		$option = array(
			'field_type'    => 'select',
			'name'          => 'hover_animation_info-on-hover',
			'title'         => esc_html__( 'Hover Animation', 'etchy-core' ),
			'options'       => $option_filter,
			'default_value' => $options_map['default_value'],
			'dependency'    => array(
				'show' => array(
					'layout' => array(
						'values'        => 'info-on-hover',
						'default_value' => ''
					)
				)
			),
			'group'         => esc_html__( 'Layout', 'etchy-core' ),
			'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] )
		);
		
		$hover_option[] = $option;
		
		return array_merge( $options, $hover_option );
	}
	
	add_filter( 'etchy_core_filter_portfolio_list_hover_animation_options', 'etchy_core_filter_portfolio_list_info_on_hover_animation_options' );
}