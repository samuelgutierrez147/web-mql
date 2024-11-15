<?php

if ( ! function_exists( 'etchy_core_add_info_section_variation_background_text' ) ) {
	function etchy_core_add_info_section_variation_background_text( $variations ) {
		$variations['background-text'] = esc_html__( 'Background Text', 'etchy-core' );
		
		return $variations;
	}
	
	add_filter( 'etchy_core_filter_info_section_layouts', 'etchy_core_add_info_section_variation_background_text' );
}

if ( ! function_exists( 'etchy_core_add_info_section_options_background_text' ) ) {
	function etchy_core_add_info_section_options_background_text( $options, $default_layout ) {
		$background_text_options   = array();
		$background_text_option    = array(
			'field_type' => 'text',
			'name'       => 'background_text_text',
			'title'      => esc_html__( 'Background Text', 'etchy-core' ),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'background-text',
						'default_value' => $default_layout
					)
				)
			),
			'group'  => esc_html__( 'Background Text', 'etchy-core' )
		);
		$background_text_options[] = $background_text_option;
		
		$background_text_position_option = array(
			'field_type' => 'select',
			'name'       => 'background_text_position',
			'title'      => esc_html__( 'Background Text Position', 'etchy-core' ),
			'options'    => array(
				'top-left' => esc_html__( 'Top Left', 'etchy-core'),
				'top-right' => esc_html__( 'Top Right', 'etchy-core'),
				'bottom-right' => esc_html__( 'Bottom Left', 'etchy-core'),
				'bottom-left' => esc_html__( 'Bottom Right', 'etchy-core'),
				'center' => esc_html__( 'Center', 'etchy-core'),
			),
			'dependency' => array(
				'show' => array(
					'layout' => array(
						'values'        => 'background-text',
						'default_value' => $default_layout
					)
				)
			),
			'group'  => esc_html__( 'Background Text', 'etchy-core' )
		);
		
		$background_text_options[] = $background_text_position_option;
		
		$background_text_color_option = array(
			'field_type' => 'color',
			'name'       => 'background_text_color',
			'title'      => esc_html__( 'Background Text Color', 'etchy-core' ),
			'group'  => esc_html__( 'Background Text', 'etchy-core' )
		);
		
		$background_text_options[] = $background_text_color_option;
		
		return array_merge( $options, $background_text_options );
	}
	
	add_filter( 'etchy_core_filter_info_section_extra_options', 'etchy_core_add_info_section_options_background_text', 10, 2 );
}

if ( ! function_exists( 'etchy_core_add_info_section_classes_background_text' ) ) {
	function etchy_core_add_info_section_classes_background_text( $holder_classes, $atts ) {
		
		if ( $atts['layout'] == 'background-text' ) {
			$holder_classes[] = ! empty( $atts['background_text_position'] ) ? 'qodef-background-text-pos--' . $atts['background_text_position'] : 'qodef-background-text-pos--top-left';
		}
		
		return $holder_classes;
	}
	
	add_filter( 'etchy_core_filter_info_section_variation_classes', 'etchy_core_add_info_section_classes_background_text', 10, 2 );
}

if ( ! function_exists( 'etchy_core_add_info_section_atts_background_text' ) ) {
	function etchy_core_add_info_section_atts_background_text( $atts ) {
		
		if ( $atts['layout'] == 'background-text' ) {
			$styles = array();
			
			if ( ! empty( $atts['background_text_color'] ) ) {
				$styles[] = 'color: ' . $atts['background_text_color'];
			}
			
			$atts['background_text_styles'] = $styles;
		}
		
		return $atts;
	}
	
	add_filter( 'etchy_core_filter_info_section_variation_atts', 'etchy_core_add_info_section_atts_background_text' );
}
