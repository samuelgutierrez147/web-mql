<?php

if ( ! function_exists( 'etchy_core_add_working_hours_list_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_working_hours_list_widget( $widgets ) {
		$widgets[] = 'EtchyCoreWorkingHoursListWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_working_hours_list_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreWorkingHoursListWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$this->set_widget_option(
				array(
					'field_type' => 'text',
					'name'       => 'widget_title',
					'title'      => esc_html__( 'Title', 'etchy-core' )
				)
			);
			$widget_mapped = $this->import_shortcode_options( array(
				'shortcode_base' => 'etchy_core_working_hours_list'
			) );
			if ( $widget_mapped ) {
				$this->set_base( 'etchy_core_working_hours_list' );
				$this->set_name( esc_html__( 'Etchy Working Hours List', 'etchy-core' ) );
				$this->set_description( esc_html__( 'Add a working hours list element into widget areas', 'etchy-core' ) );
			}
		}
		
		public function render( $atts ) {
			$params = $this->generate_string_params( $atts );
			
			echo do_shortcode( "[etchy_core_working_hours_list $params]" ); // XSS OK
		}
	}
}