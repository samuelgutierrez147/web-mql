<?php
if ( ! function_exists( 'etchy_core_add_top_area_meta_options' ) ) {
	function etchy_core_add_top_area_meta_options( $page ) {
		$top_area_section = $page->add_section_element(
			array(
				'name'       => 'qodef_top_area_section',
				'title'      => esc_html__( 'Top Area', 'etchy-core' ),
				'dependency' => array(
					'hide' => array(
						'qodef_header_layout' => array(
							'values'        => etchy_core_dependency_for_top_area_options(),
							'default_value' => ''
						)
					)
				)
			)
		);

		$top_area_section->add_field_element(
			array(
				'field_type'  => 'select',
				'name'        => 'qodef_top_area_header',
				'title'       => esc_html__( 'Top Area', 'etchy-core' ),
				'description' => esc_html__( 'Enable top area', 'etchy-core' ),
				'options'     => etchy_core_get_select_type_options_pool( 'yes_no' )
			)
		);

		$top_area_options_section = $top_area_section->add_section_element(
			array(
				'name'        => 'qodef_top_area_options_section',
				'title'       => esc_html__( 'Top Area Options', 'etchy-core' ),
				'description' => esc_html__( 'Set desired values for top area', 'etchy-core' ),
				'dependency'  => array(
					'show' => array(
						'qodef_top_area_header' => array(
							'values'        => 'yes',
							'default_value' => 'no'
						)
					)
				)
			)
		);

		$top_area_options_section->add_field_element(
			array(
				'field_type'  => 'color',
				'name'        => 'qodef_top_area_header_background_color',
				'title'       => esc_html__( 'Top Area Background Color', 'etchy-core' ),
				'description' => esc_html__( 'Choose top area background color', 'etchy-core' )
			)
		);

		$top_area_options_section->add_field_element(
			array(
				'field_type'  => 'text',
				'name'        => 'qodef_top_area_header_height',
				'title'       => esc_html__( 'Top Area Height', 'etchy-core' ),
				'description' => esc_html__( 'Enter top area height (default is 30px)', 'etchy-core' ),
				'args'        => array(
					'suffix' => esc_html__( 'px', 'etchy-core' )
				)
			)
		);

		$top_area_options_section->add_field_element(
			array(
				'field_type' => 'text',
				'name'       => 'qodef_top_area_header_side_padding',
				'title'      => esc_html__( 'Top Area Side Padding', 'etchy-core' ),
				'args'       => array(
					'suffix' => esc_html__( 'px or %', 'etchy-core' )
				)
			)
		);
	}

	add_action( 'etchy_core_action_after_page_header_meta_map', 'etchy_core_add_top_area_meta_options', 20 );
}