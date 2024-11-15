<?php

if ( ! function_exists( 'etchy_core_add_minimal_mobile_header_options' ) ) {
	function etchy_core_add_minimal_mobile_header_options( $page, $general_tab ) {
		
		$section = $general_tab->add_section_element(
			array(
				'name'       => 'qodef_minimal_mobile_header_section',
				'title'      => esc_html__( 'Minimal Mobile Header', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'qodef_mobile_header_layout' => array(
							'values' => 'minimal',
							'default_value' => ''
						)
					)
				)
			)
		);
		
		$section->add_field_element(
			array(
				'field_type'  => 'text',
				'name'        => 'qodef_minimal_mobile_header_height',
				'title'       => esc_html__( 'Minimal Height', 'etchy-core' ),
				'description' => esc_html__( 'Enter header height', 'etchy-core' ),
				'args'        => array(
					'suffix' => esc_html__( 'px', 'etchy-core' )
				)
			)
		);
		
		$section->add_field_element(
			array(
				'field_type'  => 'color',
				'name'        => 'qodef_minimal_mobile_header_background_color',
				'title'       => esc_html__( 'Header Background Color', 'etchy-core' ),
				'description' => esc_html__( 'Enter header background color', 'etchy-core' ),
				'args'        => array(
					'suffix' => esc_html__( 'px', 'etchy-core' )
				)
			)
		);
	}
	
	add_action( 'etchy_core_action_after_mobile_header_options_map', 'etchy_core_add_minimal_mobile_header_options', 10, 2 );
}