<?php

if ( ! function_exists( 'etchy_core_add_twitter_list_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_twitter_list_widget( $widgets ) {
		$widgets[] = 'EtchyCoreTwitterListWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_twitter_list_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreTwitterListWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$this->set_widget_option(
				array(
					'name'       => 'widget_title',
					'field_type' => 'text',
					'title'      => esc_html__( 'Title', 'etchy-core' )
				)
			);
			$widget_mapped = $this->import_shortcode_options( array(
				'shortcode_base' => 'etchy_core_twitter_list'
			) );
			if( $widget_mapped ) {
				$this->set_base( 'etchy_core_twitter_list' );
				$this->set_name( esc_html__( 'Etchy Twitter List', 'etchy-core' ) );
				$this->set_description( esc_html__( 'Add a twitter list element into widget areas', 'etchy-core' ) );
			}
		}
		
		public function render( $atts ) {
			$params = $this->generate_string_params( $atts );
			
			echo do_shortcode( "[etchy_core_twitter_list $params]" ); // XSS OK
		}
	}
}