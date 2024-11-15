<?php

if ( ! function_exists( 'etchy_core_add_vertical_split_slider_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_vertical_split_slider_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyVerticalSplitSliderShortcode';

		return $shortcodes;
	}

	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_vertical_split_slider_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyVerticalSplitSliderShortcode extends EtchyCoreShortcode {

		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/vertical-split-slider' );
			$this->set_base( 'etchy_vertical_split_slider' );
			$this->set_name( esc_html__( 'Vertical Split Slider', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds vertical split slider holder', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_scripts(
				array(
					'jquery-effects-core' => array(
						'registered'	=> true
					),
					'multiscroll' => array(
						'registered'	=> false,
						'url'			=> ETCHY_CORE_SHORTCODES_URL_PATH . '/vertical-split-slider/assets/js/plugins/jquery.multiscroll.min.js',
						'dependency'	=> array( 'jquery', 'jquery-effects-core' )
					)
				)
			);

			$this->set_necessary_styles(
				array(
					'multiscroll' => array(
						'registered'	=> false,
						'url'			=> ETCHY_CORE_SHORTCODES_URL_PATH . '/vertical-split-slider/assets/css/plugins/jquery.multiscroll.css'
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
				'name'          => 'disable_breakpoint',
				'title'         => esc_html__( 'Disable on smaller screens', 'etchy-core' ),
				'options'       => array(
					'1024' => esc_html__( 'Below 1024px', 'etchy-core' ),
					'768'  => esc_html__( 'Below 768px', 'etchy-core' ),
				),
				'default_value' => '1024'
			) );
			$this->set_option( array(
				'field_type' => 'repeater',
				'name'       => 'children',
				'title'      => esc_html__( 'Slide Items', 'etchy-core' ),
				'items'      => array(
					array(
						'field_type' => 'select',
						'name'       => 'slide_header_style',
						'title'      => esc_html__( 'Header/Bullets Style', 'etchy-core' ),
						'options'    => array(
							''      => esc_html__( 'Default', 'etchy-core' ),
							'light' => esc_html__( 'Light', 'etchy-core' ),
							'dark'  => esc_html__( 'Dark', 'etchy-core' ),
						)
					),
					array(
						'field_type' => 'select',
						'name'       => 'slide_layout',
						'title'      => esc_html__( 'Slide Layout', 'etchy-core' ),
						'options'    => array(
							'image-left'  => esc_html__( 'Image On Left', 'etchy-core' ),
							'image-right' => esc_html__( 'Image On Right', 'etchy-core' )
						),
					),
					array(
						'field_type' => 'image',
						'name'       => 'slide_image',
						'title'      => esc_html__( 'Image', 'etchy-core' )
					),
					array(
						'field_type' => 'text',
						'name'       => 'slide_content_title',
						'title'      => esc_html__( 'Title', 'etchy-core' ),
					),
					array(
						'field_type' => 'select',
						'name'       => 'slide_content_title_tag',
						'title'      => esc_html__( 'Title Tag', 'etchy-core' ),
						'options'    => etchy_core_get_select_type_options_pool( 'title_tag', false ),
					),
					array(
						'field_type' => 'textarea',
						'name'       => 'slide_content_text',
						'title'      => esc_html__( 'Text', 'etchy-core' ),
					),
					array(
						'field_type' => 'image',
						'name'       => 'slide_content_image',
						'title'      => esc_html__( 'Content Image', 'etchy-core' )
					),
					array(
						'field_type' => 'text',
						'name'       => 'slide_content_button_link',
						'title'      => esc_html__( 'Button Link', 'etchy-core' ),
					),
					array(
						'field_type' => 'text',
						'name'       => 'slide_content_button_text',
						'title'      => esc_html__( 'Button Text', 'etchy-core' ),
					),
					array(
						'field_type' => 'select',
						'name'       => 'slide_content_button_target',
						'title'      => esc_html__( 'Button Target', 'etchy-core' ),
						'options'    => etchy_core_get_select_type_options_pool( 'link_target', false )
					),
				)
			) );
		}
		
		public function load_assets() {
			wp_enqueue_script( 'jquery-effects-core' );
			
			wp_enqueue_script( 'multiscroll');
			wp_enqueue_style( 'multiscroll' );
		}

		public function render( $options, $content = null ) {
			parent::render( $options );

			$atts                   = $this->get_atts();
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['this_object']    = $this;
			$atts['items']          = $this->parse_repeater_items( $atts['children'] );

			return etchy_core_get_template_part( 'shortcodes/vertical-split-slider', 'templates/vertical-split-slider', '', $atts );
		}

		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();

			$holder_classes[] = 'qodef-vertical-split-slider qodef-m';
			$holder_classes[] = ! empty ( $atts['disable_breakpoint'] ) ? 'qodef-disable-below--' . $atts['disable_breakpoint'] : '';

			return implode( ' ', $holder_classes );
		}

		public function get_slide_image_styles( $slide_atts ) {
			$styles = array();

			$styles[] = ! empty( $slide_atts['slide_image'] ) ? 'background-image: url(' . wp_get_attachment_url( $slide_atts['slide_image'] ) . ')' : '';

			return $styles;
		}

		public function get_slide_data( $slide_atts ) {
			$data = array();

			$data['data-header-skin'] = ! empty( $slide_atts['slide_header_style'] ) ? $slide_atts['slide_header_style'] : '';

			return $data;
		}
	}
}