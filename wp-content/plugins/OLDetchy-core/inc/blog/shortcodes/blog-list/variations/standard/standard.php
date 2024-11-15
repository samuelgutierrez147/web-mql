<?php

if ( ! function_exists( 'etchy_core_add_blog_list_variation_standard' ) ) {
	function etchy_core_add_blog_list_variation_standard( $variations ) {
		$variations['standard'] = esc_html__( 'Standard', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_blog_list_layouts', 'etchy_core_add_blog_list_variation_standard' );
}

if ( ! function_exists( 'etchy_core_load_blog_list_variation_standard_assets' ) ) {
	function etchy_core_load_blog_list_variation_standard_assets( $is_enabled, $params ) {
		
		if ( $params['layout'] === 'standard' ) {
			$is_enabled = true;
		}
		
		return $is_enabled;
	}
	
	add_filter( 'etchy_core_filter_load_blog_list_assets', 'etchy_core_load_blog_list_variation_standard_assets', 10, 2 );
}

if ( ! function_exists( 'etchy_core_register_blog_list_standard_scripts' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function etchy_core_register_blog_list_standard_scripts( $scripts ) {

		$scripts['wp-mediaelement'] = array(
			'registered'	=> true
		);
		$scripts['mediaelement-vimeo'] = array(
			'registered'	=> true
		);

		return $scripts;
	}

	add_filter( 'etchy_core_filter_blog_list_register_scripts', 'etchy_core_register_blog_list_standard_scripts' );
}

if ( ! function_exists( 'etchy_core_register_blog_list_standard_styles' ) ) {
	/**
	 * Function that register modules 3rd party scripts
	 *
	 * @param array $styles
	 *
	 * @return array
	 */
	function etchy_core_register_blog_list_standard_styles( $styles ) {

		$styles['wp-mediaelement'] = array(
			'registered'	=> true
		);

		return $styles;
	}

	add_filter( 'etchy_core_filter_blog_list_register_styles', 'etchy_core_register_blog_list_standard_styles' );
}

if ( ! function_exists( 'etchy_core_add_blog_list_options_enable_author' ) ) {
	function etchy_core_add_blog_list_options_enable_author( $options ) {
		$blog_list_options   = array();
		
		$blog_list_options[] = array(
			'field_type' => 'select',
			'name'       => 'enable_category',
			'title'      => esc_html__( 'Enable Category', 'etchy-core' ),
			'options'       => array(
				'yes' => esc_html__( 'Yes', 'etchy-core' ),
				'no'  => esc_html__( 'No', 'etchy-core' )
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values' => 'standard'
					)
				)
			),
			'group'      => esc_html__( 'Additional Features', 'etchy-core' )
		);
		
		$blog_list_options[] = array(
			'field_type' => 'select',
			'name'       => 'enable_excerpt',
			'title'      => esc_html__( 'Enable Excerpt', 'etchy-core' ),
			'options'       => array(
				'yes' => esc_html__( 'Yes', 'etchy-core' ),
				'no'  => esc_html__( 'No', 'etchy-core' )
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values' => 'standard'
					)
				)
			),
			'group'      => esc_html__( 'Additional Features', 'etchy-core' )
		);
		
		$blog_list_options[] = array(
			'field_type' => 'select',
			'name'       => 'enable_button',
			'title'      => esc_html__( 'Enable Button', 'etchy-core' ),
			'options'       => array(
				'yes' => esc_html__( 'Yes', 'etchy-core' ),
				'no'  => esc_html__( 'No', 'etchy-core' )
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values' => 'standard'
					)
				)
			),
			'group'      => esc_html__( 'Additional Features', 'etchy-core' )
		);
		
		$blog_list_options[] = array(
			'field_type' => 'select',
			'name'       => 'enable_share',
			'title'      => esc_html__( 'Enable Social Share', 'etchy-core' ),
			'options'       => array(
				'yes' => esc_html__( 'Yes', 'etchy-core' ),
				'no'  => esc_html__( 'No', 'etchy-core' )
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values' => 'standard'
					)
				)
			),
			'group'      => esc_html__( 'Additional Features', 'etchy-core' )
		);
		
		$blog_list_options[] = array(
			'field_type' => 'select',
			'name'       => 'enable_content',
			'title'      => esc_html__( 'Enable Content', 'etchy-core' ),
			'options'       => array(
				'yes' => esc_html__( 'Yes', 'etchy-core' ),
				'no'  => esc_html__( 'No', 'etchy-core' )
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values' => 'standard'
					)
				)
			),
			'group'      => esc_html__( 'Additional Features', 'etchy-core' )
		);
		
		$blog_list_options[] = array(
			'field_type' => 'select',
			'name'       => 'enable_date',
			'title'      => esc_html__( 'Enable Date', 'etchy-core' ),
			'options'       => array(
				'yes' => esc_html__( 'Yes', 'etchy-core' ),
				'no'  => esc_html__( 'No', 'etchy-core' )
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values' => 'standard'
					)
				)
			),
			'group'      => esc_html__( 'Additional Features', 'etchy-core' )
		);
		
		return array_merge( $options, $blog_list_options );
	}
	
	add_filter( 'etchy_core_filter_blog_list_extra_options', 'etchy_core_add_blog_list_options_enable_author', 10, 1 );
}