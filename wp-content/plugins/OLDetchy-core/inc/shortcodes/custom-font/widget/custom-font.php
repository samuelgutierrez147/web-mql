<?php

if ( ! function_exists( 'etchy_core_add_custom_font_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_custom_font_widget( $widgets ) {
		$widgets[] = 'EtchyCoreCustomFontWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_custom_font_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreCustomFontWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$widget_mapped = $this->import_shortcode_options( array(
				'shortcode_base' => 'etchy_core_custom_font'
			) );
			if( $widget_mapped ) {
				$this->set_base( 'etchy_core_custom_font' );
				$this->set_name( esc_html__( 'Etchy Custom Font', 'etchy-core' ) );
				$this->set_description( esc_html__( 'Add a custom font element into widget areas', 'etchy-core' ) );
			}
		}
		
		public function render( $atts ) {
			$params = $this->generate_string_params( $atts );
			
			echo do_shortcode( "[etchy_core_custom_font $params]" ); // XSS OK
		}
	}
}