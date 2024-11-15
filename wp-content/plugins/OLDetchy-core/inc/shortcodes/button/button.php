<?php

if ( ! function_exists( 'etchy_core_add_button_shortcode' ) ) {
	/**
	 * Function that isadding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_button_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreButtonShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_button_shortcode', 9 );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreButtonShortcode extends EtchyCoreShortcode {
		
		public function __construct() {
			$this->set_layouts( apply_filters( 'etchy_core_filter_button_layouts', array() ) );
			
			parent::__construct();
		}
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/button' );
			$this->set_base( 'etchy_core_button' );
			$this->set_name( esc_html__( 'Button', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that displays button with provided parameters', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'custom_class',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' )
			) );
			
			$options_map = etchy_core_get_variations_options_map( $this->get_layouts() );

			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'button_layout',
				'title'         => esc_html__( 'Layout', 'etchy-core' ),
				'options'       => $this->get_layouts(),
				'default_value' => $options_map['default_value'],
				'visibility'    => array(
					'map_for_page_builder' => $options_map['visibility'],
					'map_for_widget' => $options_map['visibility']
				)
			) );
			$this->set_option( array(
				'field_type' => 'select',
				'name'       => 'size',
				'title'      => esc_html__( 'Size', 'etchy-core' ),
				'options'    => array(
					''      => esc_html__( 'Normal', 'etchy-core' ),
					'small' => esc_html__( 'Small', 'etchy-core' ),
					'large' => esc_html__( 'Large', 'etchy-core' )
				)
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'text',
				'title'      => esc_html__( 'Button Text', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'link',
				'title'      => esc_html__( 'Button Link', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'target',
				'title'         => esc_html__( 'Target', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'link_target' ),
				'default_value' => '_self'
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'color',
				'title'      => esc_html__( 'Text Color', 'etchy-core' ),
				'group'      => esc_html__( 'Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'name'       => 'hover_color',
				'field_type' => 'color',
				'title'      => esc_html__( 'Text Hover Color', 'etchy-core' ),
				'group'      => esc_html__( 'Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'background_color',
				'title'      => esc_html__( 'Background Color', 'etchy-core' ),
				'group'      => esc_html__( 'Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'hover_background_color',
				'title'      => esc_html__( 'Background Hover Color', 'etchy-core' ),
				'group'      => esc_html__( 'Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'border_color',
				'title'      => esc_html__( 'Border Color', 'etchy-core' ),
				'group'      => esc_html__( 'Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'hover_border_color',
				'title'      => esc_html__( 'Border Hover Color', 'etchy-core' ),
				'group'      => esc_html__( 'Style', 'etchy-core' ),
				'dependency'    => array(
					'hide' => array(
						'enable_wave_hover' => array(
							'values'        => 'yes',
							'default_value' => 'yes'
						)
					)
				)
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'margin',
				'title'      => esc_html__( 'Margin', 'etchy-core' ),
				'group'      => esc_html__( 'Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'padding',
				'title'      => esc_html__( 'Padding', 'etchy-core' ),
				'group'      => esc_html__( 'Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'font_size',
				'title'      => esc_html__( 'Font Size', 'etchy-core' ),
				'group'      => esc_html__( 'Typography', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'select',
				'name'       => 'font_weight',
				'title'      => esc_html__( 'Font Weight', 'etchy-core' ),
				'options'    => etchy_core_get_select_type_options_pool( 'font_weight' ),
				'group'      => esc_html__( 'Typography', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'select',
				'name'       => 'text_transform',
				'title'      => esc_html__( 'Text Transform', 'etchy-core' ),
				'options'    => etchy_core_get_select_type_options_pool( 'text_transform' ),
				'group'      => esc_html__( 'Typography', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'html_type',
				'title'         => esc_html__( 'HTML Type', 'etchy-core' ),
				'options'       => array(
					'default' => esc_html__( 'Default', 'etchy-core' ),
					'input'   => esc_html__( 'Input', 'etchy-core' ),
					'submit'  => esc_html__( 'Submit', 'etchy-core' )
				),
				'default_value' => 'default',
				'visibility'    => array(
					'map_for_page_builder'    => false
				)
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'input_name',
				'title'      => esc_html__( 'Input Name', 'etchy-core' ),
				'visibility'    => array(
					'map_for_page_builder'    => false
				)
			) );
			$this->set_option( array(
				'field_type' => 'array',
				'name'       => 'custom_attrs',
				'title'      => esc_html__( 'Custom Data Attributes', 'etchy-core' ),
				'visibility'    => array(
					'map_for_page_builder'    => false
				)
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'enable_wave_hover',
				'title'         => esc_html__( 'Enable Wave Animation', 'etchy-core' ),
				'default_value' => 'yes',
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no', false ),
				'dependency'    => array(
					'show' => array(
						'button_layout' => array(
							'values'        => array('filled', 'outlined'),
							'default_value' => ''
						)
					)
				)
			) );
			/*$this->set_scripts(
				array(
					'snap-svg' => array(
						'registered'	=> false,
						'url'			=> ETCHY_CORE_INC_URL_PATH . '/shortcodes/button/assets/js/plugins/snap.svg-min.js',
					)
				)
			);*/
		}
		
		public static function call_shortcode( $params ) {
			$html = qode_framework_call_shortcode( 'etchy_core_button', $params );
			$html = str_replace( "\n", '', $html );
			
			return $html;
		}
		
		
		/*public function load_assets() {
			wp_enqueue_script( 'snap-svg');
		}*/
		
		public function render( $options, $content = null ) {
			
			parent::render( $options );
			$atts = $this->get_atts();

			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['data_attrs']     = $this->get_data_attrs( $atts );
			$atts['styles']         = $this->get_styles( $atts );
			$atts['button_masked_style']   = $this->get_mask_styles( $atts );

			return etchy_core_get_template_part( 'shortcodes/button', 'variations/'.$atts['button_layout'].'/templates/' . $atts['html_type'], '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-button';
			$holder_classes[] = ! empty( $atts['button_layout'] ) ? 'qodef-layout--' . $atts['button_layout'] : '';
			$holder_classes[] = ! empty( $atts['size'] ) ? 'qodef-size--' . $atts['size'] : '';
			$holder_classes[] = $atts['html_type'] === 'default' ? 'qodef-html--link' : '';
			$holder_classes[] = ( $atts['enable_wave_hover'] == 'yes' ) ? 'qodef-btn-wave-hover': '';
			
			return implode( ' ', $holder_classes );
		}
		
		private function get_data_attrs( $atts ) {
			$data = array();
			
			if ( ! empty( $atts['hover_color'] ) ) {
				$data['data-hover-color'] = $atts['hover_color'];
			}
			
			if ( ! empty( $atts['hover_background_color'] ) ) {
				$data['data-hover-background-color'] = $atts['hover_background_color'];
			}
			
			if ( ! empty( $atts['hover_border_color'] ) ) {
				$data['data-hover-border-color'] = $atts['hover_border_color'];
			}
			
			if ( ! empty( $atts['custom_attrs'] ) && is_array( $atts['custom_attrs'] ) ) {
				$data = array_merge( $data, $atts['custom_attrs'] );
			}
			
			return $data;
		}
		
		private function get_styles( $atts ) {
			$styles = array();
			
			if ( ! empty( $atts['color'] ) ) {
				$styles[] = 'color: ' . $atts['color'];
			}
			
			if ( ! empty( $atts['background_color'] ) && $atts['button_layout'] !== 'outlined' && $atts['button_layout'] !== 'textual' ) {
				$styles[] = 'background-color: ' . $atts['background_color'];
			}
			
			if ( ! empty( $atts['border_color'] ) && $atts['button_layout'] !== 'textual' ) {
				$styles[] = 'border-color: ' . $atts['border_color'];
			}
			
			if ( ! empty( $atts['font_size'] ) ) {
				if ( qode_framework_string_ends_with_typography_units( $atts['font_size'] ) ) {
					$styles[] = 'font-size: ' . $atts['font_size'];
				} else {
					$styles[] = 'font-size: ' . intval( $atts['font_size'] ) . 'px';
				}
			}
			
			if ( ! empty( $atts['font_weight'] ) ) {
				$styles[] = 'font-weight: ' . $atts['font_weight'];
			}
			
			if ( ! empty( $atts['text_transform'] ) ) {
				$styles[] = 'text-transform: ' . $atts['text_transform'];
			}
			
			if ( $atts['margin'] !== '' ) {
				$styles[] = 'margin: ' . $atts['margin'];
			}
			
			if ( $atts['padding'] !== '' ) {
				$styles[] = 'padding: ' . $atts['padding'];
			}
			
			return $styles;
			
		}
		
		private function get_mask_styles( $atts ) {
			$styles = array();
			
			if ( $atts['enable_wave_hover'] === 'yes' ) {
				if ( ! empty( $atts['hover_background_color'] ) ) {
					$styles[] = 'background-color: ' . $atts['hover_background_color'];
				}
				
				if ( ! empty( $atts['hover_color'] ) ) {
					$styles[] = 'color: ' . $atts['hover_color'];
				}
			}
			
			return $styles;
		}
	}
}