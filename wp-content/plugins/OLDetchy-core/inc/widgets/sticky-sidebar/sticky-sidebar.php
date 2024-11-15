<?php

if ( ! function_exists( 'etchy_core_add_sticky_sidebar_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_sticky_sidebar_widget( $widgets ) {
		$widgets[] = 'EtchyCoreStickySidebarWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_sticky_sidebar_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreStickySidebarWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$this->set_base( 'etchy_core_sticky_sidebar' );
			$this->set_name( esc_html__( 'Etchy Sticky Sidebar', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Use this widget to make the sidebar sticky. Drag it into the sidebar above the widget which you want to be the first element in the sticky sidebar', 'etchy-core' ) );
		}
		
		public function render( $atts ) {
		}
	}
}
