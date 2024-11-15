<?php

if ( ! function_exists( 'etchy_core_add_general_page_meta_box' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function etchy_core_add_general_page_meta_box( $page ) {

		$general_tab = $page->add_tab_element(
			array(
				'name'        => 'tab-page',
				'icon'        => 'fa fa-cog',
				'title'       => esc_html__( 'Page Settings', 'etchy-core' ),
				'description' => esc_html__( 'General page layout settings', 'etchy-core' )
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'  => 'color',
				'name'        => 'qodef_page_background_color',
				'title'       => esc_html__( 'Page Background Color', 'etchy-core' ),
				'description' => esc_html__( 'Set background color', 'etchy-core' )
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'  => 'image',
				'name'        => 'qodef_page_background_image',
				'title'       => esc_html__( 'Page Background Image', 'etchy-core' ),
				'description' => esc_html__( 'Set background image', 'etchy-core' )
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'  => 'select',
				'name'        => 'qodef_page_background_repeat',
				'title'       => esc_html__( 'Page Background Image Repeat', 'etchy-core' ),
				'description' => esc_html__( 'Set background image repeat', 'etchy-core' ),
				'options'     => array(
					''          => esc_html__( 'Default', 'etchy-core' ),
					'no-repeat' => esc_html__( 'No Repeat', 'etchy-core' ),
					'repeat'    => esc_html__( 'Repeat', 'etchy-core' ),
					'repeat-x'  => esc_html__( 'Repeat-x', 'etchy-core' ),
					'repeat-y'  => esc_html__( 'Repeat-y', 'etchy-core' )
				)
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'  => 'select',
				'name'        => 'qodef_page_background_size',
				'title'       => esc_html__( 'Page Background Image Size', 'etchy-core' ),
				'description' => esc_html__( 'Set background image size', 'etchy-core' ),
				'options'     => array(
					''        => esc_html__( 'Default', 'etchy-core' ),
					'contain' => esc_html__( 'Contain', 'etchy-core' ),
					'cover'   => esc_html__( 'Cover', 'etchy-core' )
				)
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'  => 'select',
				'name'        => 'qodef_page_background_attachment',
				'title'       => esc_html__( 'Page Background Image Attachment', 'etchy-core' ),
				'description' => esc_html__( 'Set background image attachment', 'etchy-core' ),
				'options'     => array(
					''       => esc_html__( 'Default', 'etchy-core' ),
					'fixed'  => esc_html__( 'Fixed', 'etchy-core' ),
					'scroll' => esc_html__( 'Scroll', 'etchy-core' )
				)
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'  => 'text',
				'name'        => 'qodef_page_content_padding',
				'title'       => esc_html__( 'Page Content Padding', 'etchy-core' ),
				'description' => esc_html__( 'Set padding that will be applied for page content in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'etchy-core' )
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'  => 'text',
				'name'        => 'qodef_page_content_padding_mobile',
				'title'       => esc_html__( 'Page Content Padding Mobile', 'etchy-core' ),
				'description' => esc_html__( 'Set padding that will be applied for page content on mobile screens (1024px and below) in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'etchy-core' )
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'    => 'select',
				'name'          => 'qodef_boxed',
				'title'         => esc_html__( 'Boxed Layout', 'etchy-core' ),
				'description'   => esc_html__( 'Set boxed layout', 'etchy-core' ),
				'default_value' => '',
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no' )
			)
		);

		$boxed_section = $general_tab->add_section_element(
			array(
				'name'       => 'qodef_boxed_section',
				'title'      => esc_html__( 'Boxed Layout Section', 'etchy-core' ),
				'dependency' => array(
					'hide' => array(
						'qodef_boxed' => array(
							'values'        => 'no',
							'default_value' => ''
						)
					)
				)
			)
		);

		$boxed_section->add_field_element(
			array(
				'field_type'  => 'color',
				'name'        => 'qodef_boxed_background_color',
				'title'       => esc_html__( 'Boxed Background Color', 'etchy-core' ),
				'description' => esc_html__( 'Set boxed background color', 'etchy-core' )
			)
		);

        $boxed_section->add_field_element(
            array(
                'field_type'  => 'image',
                'name'        => 'qodef_boxed_background_pattern',
                'title'       => esc_html__( 'Boxed Background Pattern', 'etchy-core' ),
                'description' => esc_html__( 'Set boxed background pattern', 'etchy-core' )
            )
        );

        $boxed_section->add_field_element(
            array(
                'field_type'  => 'select',
                'name'        => 'qodef_boxed_background_pattern_behavior',
                'title'       => esc_html__( 'Boxed Background Pattern Behavior', 'etchy-core' ),
                'description' => esc_html__( 'Set boxed background pattern behavior', 'etchy-core' ),
                'options'     => array(
                    ''       => esc_html__( 'Default', 'etchy-core' ),
                    'fixed'  => esc_html__( 'Fixed', 'etchy-core' ),
                    'scroll' => esc_html__( 'Scroll', 'etchy-core' )
                ),
            )
        );

		$general_tab->add_field_element(
			array(
				'field_type'    => 'select',
				'name'          => 'qodef_passepartout',
				'title'         => esc_html__( 'Passepartout', 'etchy-core' ),
				'description'   => esc_html__( 'Enabling this option will display a passepartout around website content', 'etchy-core' ),
				'default_value' => '',
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no' )
			)
		);

		$passepartout_section = $general_tab->add_section_element(
			array(
				'name'       => 'qodef_passepartout_section',
				'dependency' => array(
					'hide' => array(
						'qodef_passepartout' => array(
							'values'        => 'no',
							'default_value' => ''
						)
					)
				)
			)
		);

		$passepartout_section->add_field_element(
			array(
				'field_type'  => 'color',
				'name'        => 'qodef_passepartout_color',
				'title'       => esc_html__( 'Passepartout Color', 'etchy-core' ),
				'description' => esc_html__( 'Choose background color for passepartout', 'etchy-core' )
			)
		);

		$passepartout_section->add_field_element(
			array(
				'field_type'  => 'image',
				'name'        => 'qodef_passepartout_image',
				'title'       => esc_html__( 'Passepartout Background Image', 'etchy-core' ),
				'description' => esc_html__( 'Set background image for passepartout', 'etchy-core' )
			)
		);

		$passepartout_section->add_field_element(
			array(
				'field_type'  => 'text',
				'name'        => 'qodef_passepartout_size',
				'title'       => esc_html__( 'Passepartout Size', 'etchy-core' ),
				'description' => esc_html__( 'Enter size amount for passepartout', 'etchy-core' ),
				'args'        => array(
					'suffix' => esc_html__( 'px or %', 'etchy-core' )
				)
			)
		);

		$passepartout_section->add_field_element(
			array(
				'field_type'  => 'text',
				'name'        => 'qodef_passepartout_size_responsive',
				'title'       => esc_html__( 'Passepartout Responsive Size', 'etchy-core' ),
				'description' => esc_html__( 'Enter size amount for passepartout for smaller screens (1024px and below)', 'etchy-core' ),
				'args'        => array(
					'suffix' => esc_html__( 'px or %', 'etchy-core' )
				)
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'  => 'select',
				'name'        => 'qodef_content_width',
				'title'       => esc_html__( 'Initial Width of Content', 'etchy-core' ),
				'description' => esc_html__( 'Choose the initial width of content which is in grid (applies to pages set to "Default Template" and rows set to "In Grid")', 'etchy-core' ),
				'options'     => etchy_core_get_select_type_options_pool( 'content_width' )
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type'    => 'yesno',
				'default_value' => 'no',
				'name'          => 'qodef_content_behind_header',
				'title'         => esc_html__( 'Always put content behind header', 'etchy-core' ),
				'description'   => esc_html__( 'Enabling this option will put page content behind page header', 'etchy-core' ),
			)
		);

		$general_tab->add_field_element(
			array(
				'field_type' => 'select',
				'name'       => 'qodef_back_to_top',
				'title'      => esc_html__( 'Enable Back to Top', 'etchy-core' ),
				'options'    => etchy_core_get_select_type_options_pool( 'yes_no' )
			)
		);

		// Hook to include additional options after module options
		do_action( 'etchy_core_action_after_general_page_meta_box_map', $general_tab );
	}

	add_action( 'etchy_core_action_after_general_meta_box_map', 'etchy_core_add_general_page_meta_box', 9 );
}

if ( ! function_exists( 'etchy_core_add_general_page_meta_box_callback' ) ) {
	/**
	 * Function that set current meta box callback as general callback functions
	 *
	 * @param array $callbacks
	 *
	 * @return array
	 */
	function etchy_core_add_general_page_meta_box_callback( $callbacks ) {
		$callbacks['page'] = 'etchy_core_add_general_page_meta_box';
		
		return $callbacks;
	}
	
	add_filter( 'etchy_core_filter_general_meta_box_callbacks', 'etchy_core_add_general_page_meta_box_callback' );
}