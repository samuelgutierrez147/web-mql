<?php

if ( ! function_exists( 'etchy_core_add_instagram_list_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_instagram_list_widget( $widgets ) {
		$widgets[] = 'EtchyCoreInstagramListWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_instagram_list_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreInstagramListWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$this->set_widget_option(
				array(
					'field_type' => 'text',
					'name'       => 'widget_title',
					'title'      => esc_html__( 'Title', 'etchy-core' )
				)
			);
			$widget_mapped = $this->import_shortcode_options( array(
				'shortcode_base' => 'etchy_core_instagram_list'
			) );
			if( $widget_mapped ) {
				$this->set_base( 'etchy_core_instagram_list' );
				$this->set_name( esc_html__( 'Etchy Instagram List', 'etchy-core' ) );
				$this->set_description( esc_html__( 'Add a instagram list element into widget areas', 'etchy-core' ) );
			}
		}
		
		public function render( $atts ) {
			$params = $this->generate_string_params( $atts );
			
			echo do_shortcode( "[etchy_core_instagram_list $params]" ); // XSS OK
		}
	}
}