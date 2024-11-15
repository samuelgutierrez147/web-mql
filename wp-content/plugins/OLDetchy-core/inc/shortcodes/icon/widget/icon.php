<?php

if ( ! function_exists( 'etchy_core_add_icon_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_icon_widget( $widgets ) {
		$widgets[] = 'EtchyCoreIconWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_icon_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreIconWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$widget_mapped = $this->import_shortcode_options( array(
				'shortcode_base' => 'etchy_core_icon'
			) );
			if( $widget_mapped ) {
				$this->set_base( 'etchy_core_icon' );
				$this->set_name( esc_html__( 'Etchy Icon', 'etchy-core' ) );
				$this->set_description( esc_html__( 'Add a icon element into widget areas', 'etchy-core' ) );
			}
		}
		
		public function render( $atts ) {
			
			$params = $this->generate_string_params( $atts );
			
			echo do_shortcode( "[etchy_core_icon $params]" ); // XSS OK
		}
	}
}
