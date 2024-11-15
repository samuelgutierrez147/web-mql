<?php

if ( ! function_exists( 'etchy_core_add_blog_list_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_blog_list_widget( $widgets ) {
		$widgets[] = 'EtchyCoreBlogListWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_blog_list_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreBlogListWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$this->set_widget_option(
				array(
					'field_type' => 'text',
					'name'       => 'widget_title',
					'title'      => esc_html__( 'Title', 'etchy-core' )
				)
			);
			$widget_mapped = $this->import_shortcode_options( array(
				'shortcode_base' => 'etchy_core_blog_list'
			) );
			
			if ( $widget_mapped ) {
				$this->set_base( 'etchy_core_blog_list' );
				$this->set_name( esc_html__( 'Etchy Blog List', 'etchy-core' ) );
				$this->set_description( esc_html__( 'Display a list of blog posts', 'etchy-core' ) );
			}
		}
		
		public function render( $atts ) {
			$params = $this->generate_string_params( $atts );
			
			echo do_shortcode( "[etchy_core_blog_list $params]" ); // XSS OK
		}
	}
}
