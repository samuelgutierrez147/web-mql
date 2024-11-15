<?php

if ( ! function_exists( 'etchy_core_add_outline_text_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_outline_text_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreOutlineTextShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_outline_text_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreOutlineTextShortcode extends EtchyCoreShortcode {
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/outline-text' );
			$this->set_base( 'etchy_core_outline_text' );
			$this->set_name( esc_html__( 'Outline Text', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds outline text element', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'custom_class',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' ),
			) );
			$this->set_option( array(
				'field_type' => 'textarea',
				'name'       => 'title',
				'title'      => esc_html__( 'Title', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'select',
				'name'       => 'content_alignment',
				'title'      => esc_html__( 'Content Alignment', 'etchy-core' ),
				'options'    => array(
					''       => esc_html__( 'Default', 'etchy-core' ),
					'left'   => esc_html__( 'Left', 'etchy-core' ),
					'center' => esc_html__( 'Center', 'etchy-core' ),
					'right'  => esc_html__( 'Right', 'etchy-core' )
				),
			) );
			$this->set_option( array(
				'field_type'  => 'text',
				'name'        => 'line_break_positions',
				'title'       => esc_html__( 'Positions of Line Break', 'etchy-core' ),
				'description' => esc_html__( 'Enter the positions of the words after which you would like to create a line break. Separate the positions with commas (e.g. if you would like the first, third, and fourth word to have a line break, you would enter "1,3,4")', 'etchy-core' ),
				'group'       => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'disable_title_break_words',
				'title'         => esc_html__( 'Disable Title Line Break', 'etchy-core' ),
				'description'   => esc_html__( 'Enabling this option will disable title line breaks for screen size 1024 and lower', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'no_yes', false ),
				'default_value' => 'no',
				'group'         => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'text',
				'name'          => 'custom_styled_words',
				'title'         => esc_html__( 'Custom Styled Words', 'etchy-core' ),
				'description'   => esc_html__( 'Enter the positions of the words which you would like to apply custom styles on. Separate the positions with commas (e.g. if you would like the first, third, and fourth word to have a custom styles, you would enter "1,3,4")', 'etchy-core' ),
				'default_value' => '',
				'group'         => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'outline_color',
				'title'      => esc_html__( 'Outline Color', 'etchy-core' ),
				'group'      => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'title_tag',
				'title'         => esc_html__( 'Title Tag', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'title_tag' ),
				'default_value' => 'h1',
				'group'         => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'font_size',
				'title'      => esc_html__( 'Font Size', 'etchy-core' ),
				'group'      => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'link',
				'title'      => esc_html__( 'Title Custom Link', 'etchy-core' ),
				'group'      => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'target',
				'title'         => esc_html__( 'Custom Link Target', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'link_target' ),
				'default_value' => '_self',
				'group'         => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'layout',
				'title'         => esc_html__( 'Layout', 'etchy-core' ),
				'options'       => array(
					''           => esc_html__( 'Default', 'etchy-core' ),
					'predefined' => esc_html__( 'Predefined', 'etchy-core' )
				),
				'default_value' => '',
				'group'         => esc_html__( 'Additional', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'enable_appear',
				'title'         => esc_html__( 'Enable Appear Animation', 'etchy-core' ),
				'default_value' => 'yes',
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no', false ),
			) );
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['title']          = $this->get_modified_title( $atts );
			$atts['title_styles']   = $this->get_title_styles( $atts );
			
			return etchy_core_get_template_part( 'shortcodes/outline-text', 'templates/outline-text', '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-outline-text';
			$holder_classes[] = ! empty( $atts['content_alignment'] ) ? 'qodef-alignment--' . $atts['content_alignment'] : 'qodef-alignment--left';
			$holder_classes[] = $atts['disable_title_break_words'] === 'yes' ? 'qodef-title-break--disabled' : '';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ($atts['enable_appear'] == 'yes' ) ? 'qodef--has-appear': '';

			return implode( ' ', $holder_classes );
		}
		
		private function get_modified_title( $atts ) {
			$title = $atts['title'];
			
			if (
				! empty( $title ) &&
				(
					! empty( $atts['custom_styled_words'] ) ||
					! empty( $atts['line_break_positions'] )
				)
			) {
				$split_title = explode( ' ', $title );
				
				if ( ! empty( $atts['custom_styled_words'] ) ) {
					$custom_styled_words = explode( ',', str_replace( ' ', '', $atts['custom_styled_words'] ) );
					
					if ( $atts['enable_appear'] == 'no' ) {
						foreach ( $custom_styled_words as $word ) {
							if ( isset( $split_title[ $word - 1 ] ) && ! empty( $split_title[ $word - 1 ] ) ) {
								$split_title[ $word - 1 ] = '<span class="qodef-custom-styles">' . $split_title[ $word - 1 ] . '</span>';
							}
						}
					}
				}
				
				if ( ! empty( $atts['line_break_positions'] ) ) {
					$line_break_positions = explode( ',', str_replace( ' ', '', $atts['line_break_positions'] ) );
					
					foreach ( $line_break_positions as $position ) {
						if ( isset( $split_title[ $position - 1 ] ) && ! empty( $split_title[ $position - 1 ] ) ) {
							$split_title[ $position - 1 ] = $split_title[ $position - 1 ] . '<br/>';
						}
					}
				}
				
				$title = implode( ' ', $split_title );
				
				if ( $atts['enable_appear'] != 'no' ) {
					
					$output = explode(" ", $title );
					
					foreach ( $output as $key => $value ) {
						$output[ $key ] = '<span class="qodef-m-title-word">' . $value . '</span>';
					}
					
					if ( !empty( $atts['custom_styled_words'] ) ) {
						foreach ( $custom_styled_words as $word ) {
							$output[ $word - 1 ] = '<span class="qodef-custom-styles">' . $output[ $word - 1 ] . '</span>';
						}
					}
					
					return implode( ' ', $output );
				}
				
				$title = implode( ' ', $split_title );
			}
			
			return $title;
		}
		
		private function get_title_styles( $atts ) {
			$styles = array();
			
			if ( ! empty( $atts['outline_color'] ) ) {
				$styles[] = '-webkit-text-stroke-color: ' . $atts['outline_color'];
			}
			
			$font_size = $atts['font_size'];
			if ( ! empty( $font_size ) ) {
				if ( qode_framework_string_ends_with_typography_units( $font_size ) ) {
					$styles[] = 'font-size: ' . $font_size;
				} else {
					$styles[] = 'font-size: ' . intval( $font_size ) . 'px';
				}
			}
			
			return $styles;
		}
	}
}