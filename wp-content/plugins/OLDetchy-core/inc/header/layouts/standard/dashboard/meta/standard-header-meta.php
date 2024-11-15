<?php

if ( ! function_exists( 'etchy_core_add_standard_header_meta' ) ) {
	function etchy_core_add_standard_header_meta( $page ) {
		$section = $page->add_section_element(
			array(
				'name'       => 'qodef_standard_header_section',
				'title'      => esc_html__( 'Standard Header', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'qodef_header_layout' => array(
							'values' => 'standard',
							'default_value' => ''
						)
					)
				)
			)
		);

		$section->add_field_element(
			array(
				'field_type'  => 'select',
				'name'        => 'qodef_standard_header_in_grid',
				'title'       => esc_html__( 'Content in Grid', 'etchy-core' ),
				'description' => esc_html__( 'Set content to be in grid', 'etchy-core' ),
				'default_value' => '',
				'options'       => etchy_core_get_select_type_options_pool( 'no_yes' )
			)
		);

		$section->add_field_element(
			array(
				'field_type'  => 'text',
				'name'        => 'qodef_standard_header_height',
				'title'       => esc_html__( 'Header Height', 'etchy-core' ),
				'description' => esc_html__( 'Enter header height', 'etchy-core' ),
				'args'        => array(
					'suffix' => esc_html__( 'px', 'etchy-core' )
				)
			)
		);
		
		$section->add_field_element(
			array(
				'field_type'  => 'text',
				'name'        => 'qodef_standard_header_side_padding',
				'title'       => esc_html__( 'Header Side Padding', 'etchy-core' ),
				'description' => esc_html__( 'Enter side padding for header area', 'etchy-core' ),
				'args'        => array(
					'suffix' => esc_html__( 'px or %', 'etchy-core' )
				)
			)
		);

		$section->add_field_element(
			array(
				'field_type'  => 'color',
				'name'        => 'qodef_standard_header_background_color',
				'title'       => esc_html__( 'Header Background Color', 'etchy-core' ),
				'description' => esc_html__( 'Enter header background color', 'etchy-core' )
			)
		);

		$section->add_field_element(
			array(
				'field_type'  => 'image',
				'name'        => 'qodef_standard_header_top_decoration',
				'title'       => esc_html__( 'Header Top Decoration', 'etchy-core' ),
				'description' => esc_html__( 'Set header top decoration', 'etchy-core' )
			)
		);

		$section->add_field_element(
			array(
				'field_type'    => 'select',
				'name'          => 'qodef_standard_header_menu_position',
				'title'         => esc_html__( 'Menu position', 'etchy-core' ),
				'default_value' => '',
				'options'       => array(
					''       => esc_html__( 'Default', 'etchy-core' ),
					'left'   => esc_html__( 'Left', 'etchy-core' ),
					'center' => esc_html__( 'Center', 'etchy-core' ),
					'right'  => esc_html__( 'Right', 'etchy-core' ),
				)
			)
		);

	}
	
	add_action( 'etchy_core_action_after_page_header_meta_map', 'etchy_core_add_standard_header_meta' );
}