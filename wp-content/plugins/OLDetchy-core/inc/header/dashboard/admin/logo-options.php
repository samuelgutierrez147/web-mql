<?php

if ( ! function_exists( 'etchy_core_add_logo_options' ) ) {
	function etchy_core_add_logo_options() {
		$qode_framework = qode_framework_get_framework_root();

		$page = $qode_framework->add_options_page(
			array(
				'scope'       => ETCHY_CORE_OPTIONS_NAME,
				'type'        => 'admin',
				'slug'        => 'logo',
				'icon'        => 'fa fa-cog',
				'title'       => esc_html__( 'Logo', 'etchy-core' ),
				'description' => esc_html__( 'Global Logo Options', 'etchy-core' ),
				'layout'      => 'tabbed'
			)
		);

		if ( $page ) {

			$header_tab = $page->add_tab_element(
				array(
					'name'        => 'tab-header',
					'icon'        => 'fa fa-cog',
					'title'       => esc_html__( 'Header Logo Options', 'etchy-core' ),
					'description' => esc_html__( 'Set options for initial headers', 'etchy-core' )
				)
			);

			$header_tab->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qodef_logo_height',
					'title'       => esc_html__( 'Logo Height', 'etchy-core' ),
					'description' => esc_html__( 'Enter logo height', 'etchy-core' ),
					'args'        => array(
						'suffix' => esc_html__( 'px', 'etchy-core' )
					)
				)
			);

			$header_tab->add_field_element(
				array(
					'field_type'    => 'image',
					'name'          => 'qodef_logo_main',
					'title'         => esc_html__( 'Logo - Main', 'etchy-core' ),
					'description'   => esc_html__( 'Choose main logo image', 'etchy-core' ),
					'default_value' => defined( 'ETCHY_ASSETS_ROOT' ) ? ETCHY_ASSETS_ROOT . '/img/logo.png' : '',
					'multiple'      => 'no'
				)
			);

			$header_tab->add_field_element(
				array(
					'field_type'  => 'image',
					'name'        => 'qodef_logo_dark',
					'title'       => esc_html__( 'Logo - Dark', 'etchy-core' ),
					'description' => esc_html__( 'Choose dark logo image', 'etchy-core' ),
					'multiple'    => 'no'
				)
			);

			$header_tab->add_field_element(
				array(
					'field_type'  => 'image',
					'name'        => 'qodef_logo_light',
					'title'       => esc_html__( 'Logo - Light', 'etchy-core' ),
					'description' => esc_html__( 'Choose light logo image', 'etchy-core' ),
					'multiple'    => 'no'
				)
			);

			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_header_logo_options_map', $page, $header_tab );
		}
	}

	add_action( 'etchy_core_action_default_options_init', 'etchy_core_add_logo_options', etchy_core_get_admin_options_map_position( 'logo' ) );
}