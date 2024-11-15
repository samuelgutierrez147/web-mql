<?php

if ( ! function_exists( 'etchy_core_add_standard_title_layout_meta_box' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function etchy_core_add_standard_title_layout_meta_box( $page ) {
		
		if ( $page ) {
			$section = $page->add_section_element(
				array(
					'name'       => 'qodef_standard_title_section',
					'title'      => esc_html__( 'Standard Title', 'etchy-core' ),
					'dependency' => array(
						'show' => array(
							'qodef_title_layout' => array(
								'values'        => 'standard',
								'default_value' => ''
							)
						)
					)
				)
			);
			
			$section->add_field_element(
				array(
					'field_type' => 'text',
					'name'       => 'qodef_page_title_subtitle',
					'title'      => esc_html__( 'Subtitle', 'etchy-core' )
				)
			);
			
			$section->add_field_element(
				array(
					'field_type' => 'color',
					'name'       => 'qodef_page_title_subtitle_color',
					'title'      => esc_html__( 'Subtitle Color', 'etchy-core' )
				)
			);
			
			$section->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_page_title_subtitle_top_margin',
					'title'       => esc_html__( 'Subtitle Top Margin', 'etchy-core' ),
					'args'        => array(
						'suffix' => esc_html__( 'px', 'etchy-core' )
					)
				)
			);
			
			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_standard_title_layout_meta_box_map', $section );
		}
	}
	
	add_action( 'etchy_core_action_after_page_title_meta_box_map', 'etchy_core_add_standard_title_layout_meta_box' );
}