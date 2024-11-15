<?php

if ( ! function_exists( 'etchy_core_add_fonts_options' ) ) {
	/**
	 * Function that add options for this module
	 */
	function etchy_core_add_fonts_options() {
		$qode_framework = qode_framework_get_framework_root();

		$page = $qode_framework->add_options_page(
			array(
				'scope'       => ETCHY_CORE_OPTIONS_NAME,
				'type'        => 'admin',
				'slug'        => 'fonts',
				'title'       => esc_html__( 'Fonts', 'etchy-core' ),
				'description' => esc_html__( 'Global Fonts Options', 'etchy-core' ),
				'icon'        => 'fa fa-cog'
			)
		);

		if ( $page ) {
			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qodef_enable_google_fonts',
					'title'         => esc_html__( 'Enable Google Fonts', 'etchy-core' ),
					'default_value' => 'yes',
					'args'          => array(
						'custom_class' => 'qodef-enable-google-fonts'
					)
				)
			);

			$google_fonts_section = $page->add_section_element(
				array(
					'name'       => 'qodef_google_fonts_section',
					'title'      => esc_html__( 'Google Fonts Options', 'etchy-core' ),
					'dependency' => array(
						'show' => array(
							'qodef_enable_google_fonts' => array(
								'values'        => 'yes',
								'default_value' => ''
							)
						)
					)
				)
			);

			$page_repeater = $google_fonts_section->add_repeater_element(
				array(
					'name'        => 'qodef_choose_google_fonts',
					'title'       => esc_html__( 'Google Fonts to Include', 'etchy-core' ),
					'description' => esc_html__( 'Choose Google Fonts which you want to use on your website', 'etchy-core' ),
					'button_text' => esc_html__( 'Add New Google Font', 'etchy-core' )
				)
			);

			$page_repeater->add_field_element( array(
				'field_type'  => 'googlefont',
				'name'        => 'qodef_choose_google_font',
				'title'       => esc_html__( 'Google Font', 'etchy-core' ),
				'description' => esc_html__( 'Choose Google Font', 'etchy-core' ),
				'args'        => array(
					'include' => 'google-fonts'
				)
			) );

			$google_fonts_section->add_field_element(
				array(
					'field_type'  => 'checkbox',
					'name'        => 'qodef_google_fonts_weight',
					'title'       => esc_html__( 'Google Fonts Weight', 'etchy-core' ),
					'description' => esc_html__( 'Choose a default Google Fonts weights for your website. Impact on page load time', 'etchy-core' ),
					'options'     => array(
						'100'  => esc_html__( '100 Thin', 'etchy-core' ),
						'100i' => esc_html__( '100 Thin Italic', 'etchy-core' ),
						'200'  => esc_html__( '200 Extra-Light', 'etchy-core' ),
						'200i' => esc_html__( '200 Extra-Light Italic', 'etchy-core' ),
						'300'  => esc_html__( '300 Light', 'etchy-core' ),
						'300i' => esc_html__( '300 Light Italic', 'etchy-core' ),
						'400'  => esc_html__( '400 Regular', 'etchy-core' ),
						'400i' => esc_html__( '400 Regular Italic', 'etchy-core' ),
						'500'  => esc_html__( '500 Medium', 'etchy-core' ),
						'500i' => esc_html__( '500 Medium Italic', 'etchy-core' ),
						'600'  => esc_html__( '600 Semi-Bold', 'etchy-core' ),
						'600i' => esc_html__( '600 Semi-Bold Italic', 'etchy-core' ),
						'700'  => esc_html__( '700 Bold', 'etchy-core' ),
						'700i' => esc_html__( '700 Bold Italic', 'etchy-core' ),
						'800'  => esc_html__( '800 Extra-Bold', 'etchy-core' ),
						'800i' => esc_html__( '800 Extra-Bold Italic', 'etchy-core' ),
						'900'  => esc_html__( '900 Ultra-Bold', 'etchy-core' ),
						'900i' => esc_html__( '900 Ultra-Bold Italic', 'etchy-core' )
					)
				)
			);

			$google_fonts_section->add_field_element(
				array(
					'field_type'  => 'checkbox',
					'name'        => 'qodef_google_fonts_subset',
					'title'       => esc_html__( 'Google Fonts Style', 'etchy-core' ),
					'description' => esc_html__( 'Choose a default Google Fonts style for your website. Impact on page load time', 'etchy-core' ),
					'options'     => array(
						'latin'        => esc_html__( 'Latin', 'etchy-core' ),
						'latin-ext'    => esc_html__( 'Latin Extended', 'etchy-core' ),
						'cyrillic'     => esc_html__( 'Cyrillic', 'etchy-core' ),
						'cyrillic-ext' => esc_html__( 'Cyrillic Extended', 'etchy-core' ),
						'greek'        => esc_html__( 'Greek', 'etchy-core' ),
						'greek-ext'    => esc_html__( 'Greek Extended', 'etchy-core' ),
						'vietnamese'   => esc_html__( 'Vietnamese', 'etchy-core' )
					)
				)
			);

			$page_repeater = $page->add_repeater_element(
				array(
					'name'        => 'qodef_custom_fonts',
					'title'       => esc_html__( 'Custom Fonts', 'etchy-core' ),
					'description' => esc_html__( 'Add custom fonts', 'etchy-core' ),
					'button_text' => esc_html__( 'Add New Custom Font', 'etchy-core' )
				)
			);

			$page_repeater->add_field_element( array(
				'field_type' => 'file',
				'name'       => 'qodef_custom_font_ttf',
				'title'      => esc_html__( 'Custom Font TTF', 'etchy-core' ),
				'args'       => array(
					'allowed_type' => 'application/octet-stream'
				)
			) );

			$page_repeater->add_field_element( array(
				'field_type' => 'file',
				'name'       => 'qodef_custom_font_otf',
				'title'      => esc_html__( 'Custom Font OTF', 'etchy-core' ),
				'args'       => array(
					'allowed_type' => 'application/octet-stream'
				)
			) );

			$page_repeater->add_field_element( array(
				'field_type' => 'file',
				'name'       => 'qodef_custom_font_woff',
				'title'      => esc_html__( 'Custom Font WOFF', 'etchy-core' ),
				'args'       => array(
					'allowed_type' => 'application/octet-stream'
				)
			) );

			$page_repeater->add_field_element( array(
				'field_type' => 'file',
				'name'       => 'qodef_custom_font_woff2',
				'title'      => esc_html__( 'Custom Font WOFF2', 'etchy-core' ),
				'args'       => array(
					'allowed_type' => 'application/octet-stream'
				)
			) );

			$page_repeater->add_field_element( array(
				'field_type' => 'text',
				'name'       => 'qodef_custom_font_name',
				'title'      => esc_html__( 'Custom Font Name', 'etchy-core' ),
			) );

			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_page_fonts_options_map', $page );
		}
	}

	add_action( 'etchy_core_action_default_options_init', 'etchy_core_add_fonts_options', etchy_core_get_admin_options_map_position( 'fonts' ) );
}