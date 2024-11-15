<?php

if ( ! function_exists( 'etchy_core_add_frame_slider_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_frame_slider_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreFrameSliderShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_frame_slider_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreFrameSliderShortcode extends EtchyCoreShortcode {
		
		public function __construct() {
			$this->set_layouts( apply_filters( 'etchy_core_filter_frame_slider_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'etchy_core_filter_frame_slider_extra_options', array() ) );
			
			parent::__construct();
		}
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/frame-slider' );
			$this->set_base( 'etchy_core_frame_slider' );
			$this->set_name( esc_html__( 'Frame Slider', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds frame slider element', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_scripts(
				array(
					'swiper' => array(
						'registered'	=> true
					),
					'etchy-main-js' => array(
						'registered'	=> true
					)
				)
			);
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
				'field_type' => 'repeater',
				'name'       => 'children',
				'title'      => esc_html__( 'Child elements', 'etchy-core' ),
				'items'      => array(
					array(
						'field_type'    => 'text',
						'name'          => 'item_link',
						'title'         => esc_html__( 'Link', 'etchy-core' ),
						'default_value' => ''
					),
					array(
						'field_type' => 'image',
						'name'       => 'item_image',
						'title'      => esc_html__( 'Image', 'etchy-core' )
					)
				)
			) );
			$this->map_extra_options();
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );
			$atts['this_shortcode'] = $this;
			
			return etchy_core_get_template_part( 'shortcodes/frame-slider', 'templates/frame-slider', '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-frame-slider';
			
			return implode( ' ', $holder_classes );
		}
	}
}