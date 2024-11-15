<?php

if ( ! function_exists( 'etchy_core_add_link_list_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param $shortcodes array
	 *
	 * @return array
	 */
	function etchy_core_add_link_list_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreLinkListShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_link_list_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreLinkListShortcode extends EtchyCoreShortcode {
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/link-list' );
			$this->set_base( 'etchy_core_link_list' );
			$this->set_name( esc_html__( 'link List', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds link price list holder', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'custom_class',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' ),
			) );
			
			$this->set_option( array(
				'field_type' => 'select',
				'name'       => 'skin',
				'title'      => esc_html__( 'Link Skin', 'etchy-core' ),
				'options'    => array(
					''      => esc_html__( 'Default', 'etchy-core' ),
					'light' => esc_html__( 'Light', 'etchy-core' )
				)
			) );
			$this->set_option( array(
				'field_type' => 'repeater',
				'name'       => 'children',
				'title'      => esc_html__( 'Child elements', 'etchy-core' ),
				'items'      => array(
					array(
						'field_type' => 'text',
						'name'       => 'item_title',
						'title'      => esc_html__( 'Title', 'etchy-core' )
					),
					array(
						'field_type' => 'text',
						'name'       => 'link_url',
						'title'      => esc_html__( 'Link', 'etchy-core' )
					),
					array(
						'field_type'    => 'select',
						'name'          => 'link_target',
						'title'         => esc_html__( 'Link Target', 'etchy-core' ),
						'options'       => etchy_core_get_select_type_options_pool( 'link_target' ),
						'default_value' => '_self'
					),
					array(
						'field_type'    => 'select',
						'name'          => 'item_active',
						'title'         => esc_html__( 'Item Active', 'etchy-core' ),
						'options'       => etchy_core_get_select_type_options_pool( 'no_yes', false ),
						'default_value' => 'no'
					)
				)
			) );
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			
			return etchy_core_get_template_part( 'shortcodes/link-list', 'templates/link-list', '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-link-list';
			$holder_classes[] = ! empty( $atts['skin'] ) ? 'qodef-skin--' . $atts['skin'] : '';
			
			return implode( ' ', $holder_classes );
		}
	}
}