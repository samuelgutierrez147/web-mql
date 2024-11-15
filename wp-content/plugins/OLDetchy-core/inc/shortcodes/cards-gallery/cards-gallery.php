<?php

if ( ! function_exists( 'etchy_core_add_cards_gallery_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_cards_gallery_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreCardsGalleryShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_cards_gallery_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreCardsGalleryShortcode extends EtchyCoreShortcode {
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/cards-gallery' );
			$this->set_base( 'etchy_core_cards_gallery' );
			$this->set_name( esc_html__( 'Cards Gallery', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds cards gallery holder', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'custom_class',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' ),
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'link_target',
				'title'         => esc_html__( 'Link Target', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'link_target' ),
				'default_value' => '_self'
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'orientation',
				'title'         => esc_html__( 'Info Position', 'etchy-core' ),
				'options'       => array(
					''       => esc_html__( 'Default', 'etchy-core' ),
					'right'  => esc_html__( 'Shuffled Right', 'etchy-core' ),
					'left'   => esc_html__( 'Shuffled Left', 'etchy-core' )
				),
				'default_value' => 'right'
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'bundle_animation',
				'title'         => esc_html__( 'Bundle Animation', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'no_yes' ),
				'default_value' => 'no'
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'enable_appear',
				'title'         => esc_html__( 'Enable Appear Animation', 'etchy-core' ),
				'default_value' => 'yes',
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no', false ),
			) );
			$this->set_option( array(
				'field_type' => 'repeater',
				'name'       => 'children',
				'title'      => esc_html__( 'Image Items', 'etchy-core' ),
				'items'   => array(
					array(
						'field_type'    => 'text',
						'name'          => 'item_link',
						'title'         => esc_html__( 'Link', 'etchy-core' ),
						'default_value' => ''
					),
					array(
						'field_type' => 'image',
						'name'       => 'item_image',
						'title'      => esc_html__( 'Item Image', 'etchy-core' )
					),
				)
			) );
			$this->map_extra_options();
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			
			return etchy_core_get_template_part( 'shortcodes/cards-gallery', 'templates/cards-gallery', '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-cards-gallery';
			$holder_classes[]  = ! empty( $atts['orientation'] ) ? 'qodef-orientation--' . $atts['orientation'] : 'qodef-orientation--right';
			$holder_classes[]  = isset( $atts['bundle_animation'] ) && $atts['bundle_animation'] === 'yes' ? 'qodef-animation--bundle' : 'qodef-animation--no';
			$holder_classes[] = ( $atts['enable_appear'] == 'yes' ) ? 'qodef--has-appear': '';
			
			return implode( ' ', $holder_classes );
		}
	}
}