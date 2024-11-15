<?php

if ( ! function_exists( 'etchy_core_add_stamp_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_stamp_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreStampShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_stamp_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreStampShortcode extends EtchyCoreShortcode {
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/stamp' );
			$this->set_base( 'etchy_core_stamp' );
			$this->set_name( esc_html__( 'Stamp', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds stamp element', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'custom_class',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' ),
			) );
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'text',
				'title'      => esc_html__( 'Stamp Text', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'text_color',
				'title'      => esc_html__( 'Text Color', 'etchy-core' ),
				'dependency' => array(
					'hide' => array(
						'text' => array(
							'values' => ''
						)
					)
				),
				'group'      => esc_html__( 'Text Settings', 'etchy-core' )
			) );
			
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'words_number',
				'title'      => esc_html__( 'Numbers Of Words', 'etchy-core' ),
				'dependency' => array(
					'hide' => array(
						'text' => array(
							'values' => ''
						)
					)
				),
				'group'      => esc_html__( 'Text Settings', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'text_font_size',
				'title'      => esc_html__( 'Text Font Size (px)', 'etchy-core' ),
				'dependency' => array(
					'hide' => array(
						'text' => array(
							'values' => ''
						)
					)
				),
				'group'      => esc_html__( 'Text Settings', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'centered_text',
				'title'      => esc_html__( 'Centered Text', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'centered_text_color',
				'title'      => esc_html__( 'Centered Text Color', 'etchy-core' ),
				'dependency' => array(
					'hide' => array(
						'centered_text' => array(
							'values' => ''
						)
					)
				),
				'group'      => esc_html__( 'Text Settings', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'centered_text_font_size',
				'title'      => esc_html__( 'Centered Text Font Size (px)', 'etchy-core' ),
				'dependency' => array(
					'hide' => array(
						'centered_text' => array(
							'values' => ''
						)
					)
				),
				'group'      => esc_html__( 'Text Settings', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'  => 'textfield',
				'name'        => 'stamp_size',
				'title'       => esc_html__( 'Stamp Size (px)', 'etchy-core' ),
				'description' => esc_html__( 'Default value is 114', 'etchy-core' ),
				'dependency'  => array(
					'hide' => array(
						'text' => array(
							'values' => ''
						)
					)
				),
				'group'       => esc_html__( 'Text Settings', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'select',
				'name'       => 'disable_stamp',
				'title'      => esc_html__( 'Disable Stamp', 'etchy-core' ),
				'group'      => esc_html__( 'Visibility', 'etchy-core' ),
				'options'    => array(
					''     => esc_html__( 'Never', 'etchy-core' ),
					'1440' => esc_html__( 'Below 1440px', 'etchy-core' ),
					'1280' => esc_html__( 'Below 1280px', 'etchy-core' ),
					'1024' => esc_html__( 'Below 1024px', 'etchy-core' ),
					'768'  => esc_html__( 'Below 768px', 'etchy-core' ),
					'680'  => esc_html__( 'Below 680px', 'etchy-core' ),
					'480'  => esc_html__( 'Below 480px', 'etchy-core' ),
				),
				'dependency' => array(
					'hide' => array(
						'text' => array(
							'values' => ''
						)
					)
				)
			) );
			$this->set_option( array(
				'field_type'  => 'textfield',
				'name'        => 'appearing_delay',
				'title'       => esc_html__( 'Appearing Delay (ms)', 'etchy-core' ),
				'description' => esc_html__( 'Default value is 0', 'etchy-core' ),
				'dependency'  => array(
					'hide' => array(
						'text' => array(
							'values' => ''
						)
					)
				),
				'group'       => esc_html__( 'Visibility', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'select',
				'name'       => 'absolute_position',
				'title'      => esc_html__( 'Enable Absolute Position', 'etchy-core' ),
				'options'    => etchy_core_get_select_type_options_pool( 'no_yes', false ),
				'dependency' => array(
					'hide' => array(
						'text' => array(
							'values' => ''
						)
					)
				),
				'group'      => esc_html__( 'Visibility', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'top_position',
				'title'      => esc_html__( 'Top Position (px or %)', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'absolute_position' => array(
							'values'        => 'yes',
							'default_value' => 'no'
						)
					)
				),
				'group'      => esc_html__( 'Visibility', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'bottom_position',
				'title'      => esc_html__( 'Bottom Position (px or %)', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'absolute_position' => array(
							'values'        => 'yes',
							'default_value' => 'no'
						)
					)
				),
				'group'      => esc_html__( 'Visibility', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'left_position',
				'title'      => esc_html__( 'Left Position (px or %)', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'absolute_position' => array(
							'values'        => 'yes',
							'default_value' => 'no'
						)
					)
				),
				'group'      => esc_html__( 'Visibility', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textfield',
				'name'       => 'right_position',
				'title'      => esc_html__( 'Right Position (px or %)', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'absolute_position' => array(
							'values'        => 'yes',
							'default_value' => 'no'
						)
					)
				),
				'group'      => esc_html__( 'Visibility', 'etchy-core' )
			) );
		}

		public static function call_shortcode( $params ) {
			$html = qode_framework_call_shortcode( 'etchy_core_stamp', $params );
			$html = str_replace( "\n", '', $html );

			return $html;
		}

		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			
			$atts['holder_classes']       = $this->getHolderClasses( $atts );
			$atts['holder_styles']        = $this->getHolderStyles( $atts );
			$atts['centered_text_styles'] = $this->getCenteredTextStyles( $atts );
			$atts['holder_data']          = $this->getHolderData( $atts );
			$atts['text_data']            = $this->getModifiedText( $atts );
			
			return etchy_core_get_template_part( 'shortcodes/stamp', 'templates/stamp', '', $atts );
		}
		
		private function getHolderClasses( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-stamp';
			$holder_classes[] = ! empty( $atts['disable_stamp'] ) ? 'qodef-hide-on--' . $atts['disable_stamp'] : '';
			$holder_classes[] = $atts['absolute_position'] === 'yes' ? 'qodef--abs' : '';
			
			return implode( ' ', $holder_classes );
		}
		
		private function getHolderStyles( $atts ) {
			$styles = array();
			
			if ( ! empty( $atts['text_color'] ) ) {
				$styles[] = 'color: ' . $atts['text_color'];
			}
			
			if ( ! empty( $atts['text_font_size'] ) ) {
				$styles[] = 'font-size: ' . intval( $atts['text_font_size'] ) . 'px';
			}
			if ( ! empty( $atts['text_color'] ) ) {
				$styles[] = 'color: ' . $atts['text_color'];
			}
			
			if ( ! empty( $atts['stamp_size'] ) ) {
				$styles[] = 'width: ' . intval( $atts['stamp_size'] ) . 'px';
				$styles[] = 'height: ' . intval( $atts['stamp_size'] ) . 'px';
			}
			
			if ( $atts['top_position'] !== '' ) {
				$styles[] = 'top: ' . $atts['top_position'];
			}
			if ( $atts['bottom_position'] !== '' ) {
				$styles[] = 'bottom: ' . $atts['bottom_position'];
			}
			
			if ( $atts['left_position'] !== '' ) {
				$styles[] = 'left: ' . $atts['left_position'];
			}
			
			if ( $atts['right_position'] !== '' ) {
				$styles[] = 'right: ' . $atts['right_position'];
			}
			
			return implode( ';', $styles );
		}
		
		private function getCenteredtextStyles( $atts ) {
			$styles = array();
			
			if ( ! empty( $atts['centered_text_font_size'] ) ) {
				$styles[] = 'font-size: ' . intval( $atts['centered_text_font_size'] ) . 'px';
			}
			if ( ! empty( $atts['centered_text_color'] ) ) {
				$styles[] = 'color: ' . $atts['centered_text_color'];
			}
			
			return implode( ';', $styles );
		}
		
		private function getHolderData( $atts ) {
			$slider_data = array();
			
			$slider_data['data-appearing-delay'] = ! empty( $atts['appearing_delay'] ) ? intval( $atts['appearing_delay'] ) : 0;
			
			return $slider_data;
		}
		
		private function getModifiedText( $atts ) {
			$text = $atts['text'];
			$num  = $atts['words_number'];
			$data = array(
				'text'  => $this->get_split_text( $text, $num ),
				'count' => count( $this->str_split_unicode( $text ) )
			);
			
			return $data;
		}
		
		private function str_split_unicode( $str ) {
			return preg_split( '~~u', $str, - 1, PREG_SPLIT_NO_EMPTY );
		}
		
		private function get_split_text( $text, $num ) {
			
			if ( ! empty( $text ) ) {
				$split_text = $this->str_split_unicode( $text );
				$num        = intval( $num );
				
				$class_array = array(
					'qodef-main-class',
					'qodef-second-class'
				);
				
				$count       = 0;
				$class_index = 0;
				foreach ( $split_text as $key => $value ) {
					$split_text[ $key ] = '<span class="qodef-m-character ' . esc_attr( $class_array[ $class_index ] ) . '">' . $value . '</span>';
					$count ++;
					
					if ( $count == $num ) {
						$count = 0;
						$class_index ++;
						if ( $class_index == count( $class_array ) ) {
							$class_index = 0;
						}
					}
				}
				
				return implode( ' ', $split_text );
			}
			
			return $text;
		}
	}
}