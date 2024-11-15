<?php

if ( ! function_exists( 'etchy_core_add_accordion_child_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_accordion_child_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreAccordionChildShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_accordion_child_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreAccordionChildShortcode extends EtchyCoreShortcode {
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/accordion' );
			$this->set_base( 'etchy_core_accordion_child' );
			$this->set_name( esc_html__( 'Accordion Child', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds accordion child to accordion holder', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_is_child_shortcode( true );
			$this->set_parent_elements( array(
				'etchy_core_accordion'
			) );
			$this->set_is_parent_shortcode( true );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'title',
				'title'      => esc_html__( 'Title', 'etchy-core' ),
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'title_tag',
				'title'         => esc_html__( 'Title Tag', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'title_tag' ),
				'default_value' => 'h3'
			) );
			$this->set_option( array(
				'field_type'    => 'text',
				'name'          => 'layout',
				'title'         => esc_html__( 'Layout', 'etchy-core' ),
				'default_value' => '',
				'visibility'    => array('map_for_page_builder' => false)
			) );
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			$atts['content'] = $content;

			return etchy_core_get_template_part( 'shortcodes/accordion', 'variations/'.$atts['layout'].'/templates/child', '', $atts );
		}
	}
}