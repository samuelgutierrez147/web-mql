<?php

if ( ! function_exists( 'etchy_core_is_page_footer_enabled' ) ) {
	/**
	 * Function that check is module enabled
	 * 
	 * @param bool $is_enabled
	 * 
	 * @return bool
	 */
	function etchy_core_is_page_footer_enabled( $is_enabled ) {
		$option = etchy_core_get_post_value_through_levels( 'qodef_enable_page_footer' ) !== 'no';
		
		if ( ! $option ) {
			$is_enabled = false;
		}
		
		return $is_enabled;
	}
	
	add_filter( 'etchy_filter_enable_page_footer', 'etchy_core_is_page_footer_enabled' );
}

if ( ! function_exists( 'etchy_core_set_footer_holder_classes' ) ) {
	/**
	 * Function that return classes for page footer area
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	function etchy_core_set_footer_holder_classes( $classes ) {
		$uncovering_footer = etchy_core_get_post_value_through_levels( 'qodef_enable_uncovering_footer' ) === 'yes';
		
		if ( $uncovering_footer ) {
			$classes[] = 'qodef--uncover';
		}
		
		return $classes;
	}
	
	add_filter( 'etchy_filter_footer_holder_classes', 'etchy_core_set_footer_holder_classes' );
}

if ( ! function_exists( 'etchy_core_is_footer_top_area_enabled' ) ) {
	/**
	 * Function that check if page footer top area widgets are empty
	 * 
	 * @param bool $is_enabled
	 *
	 * @return bool
	 */
	function etchy_core_is_footer_top_area_enabled( $is_enabled ) {
		$option = etchy_core_get_post_value_through_levels( 'qodef_enable_top_footer_area' ) !== 'no';
		
		if ( ! $option ) {
			$is_enabled = false;
		}
		
		return $is_enabled;
	}
	
	add_filter( 'etchy_filter_enable_footer_top_area', 'etchy_core_is_footer_top_area_enabled' );
}

if ( ! function_exists( 'etchy_core_is_footer_bottom_area_enabled' ) ) {
	/**
	 * Function that check if page footer bottom area widgets are empty
	 * 
	 * @param bool $is_enabled
	 *
	 * @return bool
	 */
	function etchy_core_is_footer_bottom_area_enabled( $is_enabled ) {
		$option = etchy_core_get_post_value_through_levels( 'qodef_enable_bottom_footer_area' ) !== 'no';
		
		if ( ! $option ) {
			$is_enabled = false;
		}
		
		return $is_enabled;
	}
	
	add_filter( 'etchy_filter_enable_footer_bottom_area', 'etchy_core_is_footer_bottom_area_enabled' );
}

if ( ! function_exists( 'etchy_core_set_footer_top_area_classes' ) ) {
	/**
	 * Function that return classes for page footer top area
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	function etchy_core_set_footer_top_area_classes( $classes ) {
		$is_grid_enabled = etchy_core_get_post_value_through_levels( 'qodef_set_footer_top_area_in_grid' ) !== 'no';
		
		if ( ! $is_grid_enabled ) {
			$classes = 'qodef-content-full-width';
		}
		
		return $classes;
	}
	
	add_filter( 'etchy_filter_footer_top_area_classes', 'etchy_core_set_footer_top_area_classes' );
}

if ( ! function_exists( 'etchy_core_set_footer_bottom_area_classes' ) ) {
	/**
	 * Function that return classes for page footer bottom area
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	function etchy_core_set_footer_bottom_area_classes( $classes ) {
		$is_grid_enabled = etchy_core_get_post_value_through_levels( 'qodef_set_footer_bottom_area_in_grid' ) !== 'no';
		
		if ( ! $is_grid_enabled ) {
			$classes = 'qodef-content-full-width';
		}
		
		return $classes;
	}
	
	add_filter( 'etchy_filter_footer_bottom_area_classes', 'etchy_core_set_footer_bottom_area_classes' );
}

if ( ! function_exists( 'etchy_core_set_footer_sidebars_config' ) ) {
	/**
	 * Function that override default page footer sidebars config
	 *
	 * @param array $config
	 *
	 * @return array
	 */
	function etchy_core_set_footer_sidebars_config( $config ) {
		$top_area_columns    = etchy_core_get_post_value_through_levels( 'qodef_set_footer_top_area_columns' );
		$bottom_area_columns = etchy_core_get_post_value_through_levels( 'qodef_set_footer_bottom_area_columns' );
		
		if ( ! empty( $top_area_columns ) ) {
			$config['footer_top_sidebars_number'] = $top_area_columns;
		}
		
		if ( ! empty( $bottom_area_columns ) ) {
			$config['footer_bottom_sidebars_number'] = $bottom_area_columns;
		}
		
		return $config;
	}
	
	add_filter( 'etchy_filter_page_footer_sidebars_config', 'etchy_core_set_footer_sidebars_config' );
}

if ( ! function_exists( 'etchy_core_set_footer_top_area_columns_classes' ) ) {
	/**
	 * Function that set classes for page footer top area columns
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	function etchy_core_set_footer_top_area_columns_classes( $classes ) {
		$gutter_size = etchy_core_get_post_value_through_levels( 'qodef_set_footer_top_area_grid_gutter' );
		
		if ( ! empty( $gutter_size ) ) {
			$classes[] = 'qodef-gutter--' . esc_attr( $gutter_size );
		}
		
		return $classes;
	}
	
	add_filter( 'etchy_filter_footer_top_area_columns_classes', 'etchy_core_set_footer_top_area_columns_classes' );
}

if ( ! function_exists( 'etchy_core_set_footer_bottom_area_columns_classes' ) ) {
	/**
	 * Function that set classes for page footer bottom area columns
	 *
	 * @param array $classes
	 *
	 * @return array
	 */
	function etchy_core_set_footer_bottom_area_columns_classes( $classes ) {
		$gutter_size = etchy_core_get_post_value_through_levels( 'qodef_set_footer_bottom_area_grid_gutter' );
		
		if ( ! empty( $gutter_size ) ) {
			$classes[] = 'qodef-gutter--' . esc_attr( $gutter_size );
		}
		
		return $classes;
	}
	
	add_filter( 'etchy_filter_footer_bottom_area_columns_classes', 'etchy_core_set_footer_bottom_area_columns_classes' );
}

if ( ! function_exists( 'etchy_core_set_page_footer_area_styles' ) ) {
	/**
	 * Function that generates module inline styles
	 *
	 * @param string $style
	 *
	 * @return string
	 */
	function etchy_core_set_page_footer_area_styles( $style ) {
		$footer_area = array( 'top', 'bottom' );
		
		foreach ( $footer_area as $area ) {
			$styles           = array();
			$background_color = etchy_core_get_post_value_through_levels( 'qodef_' . $area . '_footer_area_background_color' );
			$background_image = etchy_core_get_post_value_through_levels( 'qodef_' . $area . '_footer_area_background_image' );
			$top_border_color = etchy_core_get_post_value_through_levels( 'qodef_' . $area . '_footer_area_top_border_color' );
			$top_border_width = etchy_core_get_post_value_through_levels( 'qodef_' . $area . '_footer_area_top_border_width' );
			$top_decoration   = etchy_core_get_post_value_through_levels( 'qodef_enable_' . $area . '_footer_area_top_decoration' ) === 'yes';

			if ( ! empty( $background_color ) ) {
				$styles['background-color'] = $background_color;
			}
			
			if ( ! empty( $background_image ) ) {
				$styles['background-image'] = 'url(' . esc_url( wp_get_attachment_image_url( $background_image, 'full' ) ) . ')';

				if ( ! empty( $top_decoration ) ) {
					$styles['background-size']   = 'auto';
					$styles['background-position'] = 'top left';
				}
			}
			
			if ( ! empty( $top_border_color ) ) {
				$styles['border-top-color'] = $top_border_color;
				
				if ( $top_border_width === '' ) {
					$styles['border-top-width'] = '1px';
				}
			}
			
			if ( $top_border_width !== '' ) {
				$styles['border-top-width'] = intval( $top_border_width ) . 'px';
			}

			if ( ! empty( $styles ) ) {
				$style .= qode_framework_dynamic_style( '#qodef-page-footer-' . $area . '-area', $styles );
			}
		}
		
		return $style;
	}
	
	add_filter( 'etchy_filter_add_inline_style', 'etchy_core_set_page_footer_area_styles' );
}