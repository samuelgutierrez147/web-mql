<?php

abstract class EtchyCoreHeader {
	public $overriding_whole_header = false;
	public $slug;
	private $layout_slug = '';
	public $search_layout;
	protected $default_header_height;
	protected $header_height;

	public function __construct() {
		$this->set_header_height();
		
		add_filter( 'etchy_core_action_before_main_css', array( $this, 'enqueue_assets' ) );
		add_filter( 'etchy_core_action_before_main_css', array( $this, 'enqueue_additional_assets' ) );
		add_filter( 'etchy_filter_localize_main_js', array( $this, 'set_global_javascript_variables' ) );
		add_filter( 'etchy_core_filter_content_margin', array( $this, 'get_content_margin' ) );
		add_filter( 'etchy_core_filter_title_padding', array( $this, 'get_title_padding' ) );
		add_filter( 'etchy_filter_add_inline_style', array( $this, 'set_inline_header_styles' ) );
		add_filter( 'etchy_filter_header_inner_class', array( $this, 'set_grid_class' ) );
		add_filter( 'etchy_core_filter_nav_menu_header_selector', array( $this, 'set_nav_menu_header_selector' ) );
		add_filter( 'etchy_core_filter_nav_menu_narrow_header_selector', array( $this, 'set_nav_menu_narrow_header_selector' ) );
	}

	public function set_slug( $slug ) {
		$this->slug = $slug;
	}

	public function get_layout_slug() {
		return $this->layout_slug;
	}

	public function set_layout_slug( $layout_slug ) {
		$this->layout_slug = $layout_slug;
	}

	/**
	 * swap dash with underscore
	 *
	 * header slug must be same as folder name, we're using dash as delimiter
	 * options and metaboxes, we're using underscore as delimiter
	 *
	 * @return mixed
	 */
	public function get_formatted_slug() {
		return str_replace( '-', '_', $this->slug );
	}
	
	public function load_template( $parameters = array() ) {
		$parameters = apply_filters( 'etchy_core_filter_header_template', $parameters );

		return etchy_core_get_template_part( 'header/layouts/' . $this->slug, 'templates/' . $this->slug, $this->get_layout_slug(), $parameters );
	}
	
	public function enqueue_assets() {
		wp_enqueue_script( 'hoverIntent' );
	}
	
	public function enqueue_additional_assets() {
		return false;
	}
	
	public function set_global_javascript_variables( $global_vars ) {
		$global_vars['headerHeight'] = $this->header_height;
		
		return $global_vars;
	}
	
	public function get_header_transparency() {
		$background_color = etchy_core_get_post_value_through_levels( 'qodef_' . $this->get_formatted_slug() . '_header_background_color' );
		
		if ( ! empty( $background_color ) ) {
			return ! preg_match( '/^#[a-f0-9]{6}$/i', $background_color ); // hex color is valid
		}
		
		return false;
	}

	public function content_behind_header() {
		$content_behind_header = etchy_core_get_post_value_through_levels( 'qodef_content_behind_header' );

		if ( is_404() ) {
			return true;
		}

		return $content_behind_header == 'yes' ? true : false;
	}

	public function get_content_margin( $margin ) {
		
		if ( $this->get_header_transparency() || $this->content_behind_header() ) {
			$margin += $this->header_height;
		}
		
		return $margin;
	}
	
	public function get_title_padding( $padding ) {
		
		if ( $this->get_header_transparency() || $this->content_behind_header() ) {
			$padding += $this->header_height;
		}
		
		return $padding;
	}
	
	function set_header_height() {
		$header_height = etchy_core_get_post_value_through_levels( 'qodef_' . $this->get_formatted_slug() . '_header_height' );
		$header_height = ! empty( $header_height ) ? intval( $header_height ) : $this->default_header_height;
		
		$this->header_height = apply_filters( 'etchy_core_filter_set_header_height', $header_height );
	}
	
	function set_grid_class( $class ) {
		$class .= etchy_core_get_post_value_through_levels( 'qodef_' . $this->get_formatted_slug() . '_header_in_grid' ) == 'yes' ? 'qodef-content-grid' : '';
		
		return $class;
	}
	
	public function set_inline_header_styles( $style ) {
		$styles = array();

		$height           = etchy_core_get_post_value_through_levels( 'qodef_' . $this->get_formatted_slug() . '_header_height' );
		$background_color = etchy_core_get_post_value_through_levels( 'qodef_' . $this->get_formatted_slug() . '_header_background_color' );
		$top_decoration   = etchy_core_get_post_value_through_levels( 'qodef_' . $this->get_formatted_slug() . '_header_top_decoration' );

		if ( ! empty( $height ) ) {
			$styles['height'] = intval( $height ) . 'px';
		}

		if ( ! empty( $background_color ) ) {
			$styles['background-color'] = $background_color;
		}

		if ( ! empty( $top_decoration ) ) {
			$styles['background-image']    = 'url(' . esc_url( wp_get_attachment_image_url( $top_decoration, 'full' ) ) . ')';
			$styles['background-repeat']   = 'no-repeat';
			$styles['background-position'] = 'top left';
		}
		
		if ( ! empty( $styles ) ) {
			$style .= qode_framework_dynamic_style( '.qodef-header--' . $this->slug . ' #qodef-page-header', $styles );
		}
		
		$inner_styles = array();
		
		$side_padding = etchy_core_get_post_value_through_levels( 'qodef_' . $this->get_formatted_slug() . '_header_side_padding' );
		
		if ( ! empty( $side_padding ) ) {
			if ( qode_framework_string_ends_with_space_units( $side_padding ) ) {
				$inner_styles['padding-left']  = $side_padding;
				$inner_styles['padding-right'] = $side_padding;
			} else {
				$inner_styles['padding-left']  = intval( $side_padding ) . 'px';
				$inner_styles['padding-right'] = intval( $side_padding ) . 'px';
			}
		}
		
		if ( ! empty( $inner_styles ) ) {
			$style .= qode_framework_dynamic_style( '.qodef-header--' . $this->slug . ' #qodef-page-header-inner', $inner_styles );
		}
		
		return $style;
	}
	
	public function set_nav_menu_header_selector( $selector ) {
		return $selector;
	}
	
	public function set_nav_menu_narrow_header_selector( $selector ) {
		return $selector;
	}
}