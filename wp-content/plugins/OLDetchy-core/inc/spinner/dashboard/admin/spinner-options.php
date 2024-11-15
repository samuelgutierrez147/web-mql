<?php

if ( ! function_exists( 'etchy_core_add_page_spinner_options' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function etchy_core_add_page_spinner_options( $page ) {
		
		if ( $page ) {
			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qodef_enable_page_spinner',
					'title'         => esc_html__( 'Enable Page Spinner', 'etchy-core' ),
					'description'   => esc_html__( 'Enable Page Spinner Effect', 'etchy-core' ),
					'default_value' => 'no'
				)
			);
			
			$spinner_section = $page->add_section_element(
				array(
					'name'       => 'qodef_page_spinner_section',
					'title'      => esc_html__( 'Page Spinner Section', 'etchy-core' ),
					'dependency' => array(
						'show' => array(
							'qodef_enable_page_spinner' => array(
								'values'        => 'yes',
								'default_value' => 'no'
							)
						)
					)
				)
			);
			
			$spinner_section->add_field_element(
				array(
					'field_type'    => 'select',
					'name'          => 'qodef_page_spinner_type',
					'title'         => esc_html__( 'Select Page Spinner Type', 'etchy-core' ),
					'description'   => esc_html__( 'Choose a page spinner animation style', 'etchy-core' ),
					'options'       => apply_filters( 'etchy_core_filter_page_spinner_layout_options', array() ),
					'default_value' => apply_filters( 'etchy_core_filter_page_spinner_default_layout_option', '' ),
				)
			);
			
			$spinner_section->add_field_element(
				array(
					'field_type'  => 'color',
					'name'        => 'qodef_page_spinner_background_color',
					'title'       => esc_html__( 'Spinner Background Color', 'etchy-core' ),
					'description' => esc_html__( 'Choose the spinner background color', 'etchy-core' )
				)
			);
			
			$spinner_section->add_field_element(
				array(
					'field_type'  => 'color',
					'name'        => 'qodef_page_spinner_color',
					'title'       => esc_html__( 'Spinner Color', 'etchy-core' ),
					'description' => esc_html__( 'Choose the spinner color', 'etchy-core' )
				)
			);
			
			$spinner_section->add_field_element(
				array(
					'field_type'    => 'image',
					'name'          => 'qodef_page_spinner_background_image',
					'title'         => esc_html__( 'Background Image', 'etchy-core' ),
					'description'   => esc_html__( 'Choose the spinner background image', 'etchy-core' ),
					'default_value' => '',
				)
			);
		}
	}
	
	add_action( 'etchy_core_action_after_general_options_map', 'etchy_core_add_page_spinner_options' );
}