<?php

if ( ! function_exists( 'etchy_core_add_portfolio_options' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function etchy_core_add_portfolio_options() {
		$qode_framework = qode_framework_get_framework_root();

		$page = $qode_framework->add_options_page(
			array(
				'scope'       => ETCHY_CORE_OPTIONS_NAME,
				'type'        => 'admin',
				'slug'        => 'portfolio-item',
				'layout'      => 'tabbed',
				'icon'        => 'fa fa-cog',
				'title'       => esc_html__( 'Portfolio', 'etchy-core' ),
				'description' => esc_html__( 'Global settings related to portfolio', 'etchy-core' )
			)
		);

		if ( $page ) {
			$archive_tab = $page->add_tab_element(
				array(
					'name'        => 'tab-archive',
					'icon'        => 'fa fa-cog',
					'title'       => esc_html__( 'Portfolio List', 'etchy-core' ),
					'description' => esc_html__( 'Settings related to portfolio archive pages', 'etchy-core' )
				)
			);

			// Hook to include additional options after archive module options
			do_action( 'etchy_core_action_after_portfolio_options_archive', $archive_tab );

			$single_tab = $page->add_tab_element(
				array(
					'name'        => 'tab-single',
					'icon'        => 'fa fa-cog',
					'title'       => esc_html__( 'Portfolio Single', 'etchy-core' ),
					'description' => esc_html__( 'Settings related to portfolio single pages', 'etchy-core' )
				)
			);
			$single_tab->add_field_element(
				array(
					'field_type'    => 'select',
					'name'          => 'qodef_portfolio_single_layout',
					'title'         => esc_html__( 'Single Layout', 'etchy-core' ),
					'description'   => esc_html__( 'Choose default layout for portfolio single', 'etchy-core' ),
					'default_value' => apply_filters( 'etchy_core_filter_portfolio_single_layout_default_value', '' ),
					'options'       => apply_filters( 'etchy_core_filter_portfolio_single_layout_options', array() )
				)
			);

			// Hook to include additional options after single module options
			do_action( 'etchy_core_action_after_portfolio_options_single', $single_tab );

			// Hook to include additional options after module options
			do_action( 'etchy_core_action_after_portfolio_options_map', $page );
		}
	}

	add_action( 'etchy_core_action_default_options_init', 'etchy_core_add_portfolio_options', etchy_core_get_admin_options_map_position( 'portfolio' ) );
}