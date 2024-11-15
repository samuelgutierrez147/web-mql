<?php

if ( ! function_exists( 'etchy_core_add_social_share_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_social_share_widget( $widgets ) {
		$widgets[] = 'EtchyCoreSocialShareWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_social_share_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreSocialShareWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$widget_mapped = $this->import_shortcode_options( array(
				'shortcode_base' => 'etchy_core_social_share'
			) );
			if( $widget_mapped ) {
				$this->set_base( 'etchy_core_social_share' );
				$this->set_name( esc_html__( 'Etchy Social Share', 'etchy-core' ) );
				$this->set_description( esc_html__( 'Add a social share element into widget areas', 'etchy-core' ) );
			}
		}
		
		public function render( $atts ) {
			$params = $this->generate_string_params( $atts );
			
			echo do_shortcode( "[etchy_core_social_share $params]" ); // XSS OK
		}
	}
}