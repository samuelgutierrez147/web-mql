<?php

class StandardHeader extends EtchyCoreHeader {
	private static $instance;

	public function __construct() {
		$this->set_slug( 'standard' );

		$header_menu_position = $this->get_menu_position();
		if ( $header_menu_position === 'center' ) {
			$this->set_layout_slug( 'centered' );
		}

		$this->search_layout         = 'covers-header';
		$this->default_header_height = 100;

		add_filter( 'body_class', array( $this, 'add_body_classes' ) );

		parent::__construct();
	}
	
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	function get_menu_position() {
		return etchy_core_get_post_value_through_levels( 'qodef_standard_header_menu_position' );
	}

	function add_body_classes( $classes ) {
		$header_menu_position = $this->get_menu_position();

		$classes[] = ! empty( $header_menu_position ) ? 'qodef-header-standard--' . $header_menu_position : '';

		return $classes;
	}
}