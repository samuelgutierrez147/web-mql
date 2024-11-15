<?php
class CoversHeaderSearch extends EtchyCoreSearch {
	private static $instance;

	public function __construct() {
		parent::__construct();

		add_action( 'wp', array( $this, 'load_template' ), 111 );
	}
	
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	public function return_template(){
		return etchy_core_template_part( 'search/layouts/' . $this->search_layout, 'templates/' . $this->search_layout );
	}
	
	public function load_template() {
		if ( is_active_widget( false, false, 'etchy_core_search_opener' ) ) {
			
			$actions         = array();
			$sidebars        = qode_framework_get_widget_sidebars( 'etchy_core_search_opener' );
			$custom_sidebars = etchy_core_get_custom_sidebars();
			$page_id         = qode_framework_get_page_id();
			
			foreach ( $sidebars as $sidebar ) {
				if ( $sidebar == 'qodef-header-widget-area-one' || $sidebar == 'qodef-header-widget-area-two' ) {
					$actions[] = 'etchy_action_after_page_header_inner';
					$actions[] = 'etchy_core_action_after_sticky_header';
				} elseif ( $sidebar == 'qodef-mobile-header-widget-area' ) {
					$actions[] = 'etchy_action_after_page_mobile_header_inner';
				} elseif ( $sidebar == 'qodef-top-area-left' || $sidebar == 'qodef-top-area-right' ) {
					$actions[] = 'etchy_core_action_after_top_area';
				} elseif ( array_key_exists( $sidebar, $custom_sidebars ) ) {
					$custom_menu_widget_area_one = get_post_meta( $page_id, 'qodef_header_custom_widget_area_one', true );
					$custom_menu_widget_area_two = get_post_meta( $page_id, 'qodef_header_custom_widget_area_two', true );
					if ( $sidebar == $custom_menu_widget_area_one || $sidebar == $custom_menu_widget_area_two ) {
						$actions[] = 'etchy_action_after_page_header_inner';
						$actions[] = 'etchy_core_action_after_sticky_header';
					}
				}
			}
			
			foreach ( $actions as $action ) {
				add_action( $action, array( $this, 'return_template' ) );
			}
		}
	}
}