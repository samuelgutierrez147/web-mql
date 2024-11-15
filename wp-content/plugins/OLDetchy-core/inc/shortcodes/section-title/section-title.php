<?php

if ( ! function_exists( 'etchy_core_add_section_title_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_section_title_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreSectionTitleShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_section_title_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreSectionTitleShortcode extends EtchyCoreShortcode {
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/section-title' );
			$this->set_base( 'etchy_core_section_title' );
			$this->set_name( esc_html__( 'Section Title', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds section title element', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'custom_class',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' ),
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'title',
				'title'      => esc_html__( 'Title', 'etchy-core' )
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
				'field_type'    => 'select',
				'name'          => 'title_tag',
				'title'         => esc_html__( 'Title Tag', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'title_tag' ),
				'default_value' => 'h2',
				'group'         => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'title_color',
				'title'      => esc_html__( 'Title Color', 'etchy-core' ),
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
				'dependency'    => array(
					'show' => array(
						'image_action' => array(
							'values'        => 'custom-link',
							'default_value' => ''
						)
					)
				),
				'group'         => esc_html__( 'Title Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'icon_type',
				'title'         => esc_html__( 'Icon Type', 'etchy-core' ),
				'options'       => array(
					'icon-pack'   => esc_html__( 'Icon Pack', 'etchy-core' ),
					'custom-icon' => esc_html__( 'Custom Icon', 'etchy-core' )
				),
				'default_value' => 'icon-pack',
				'group'         => esc_html__( 'Title Icon', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'image',
				'name'       => 'custom_icon',
				'title'      => esc_html__( 'Custom Icon', 'etchy-core' ),
				'group'      => esc_html__( 'Title Icon', 'etchy-core' ),
				'dependency' => array(
					'show' => array(
						'icon_type' => array(
							'values'        => 'custom-icon',
							'default_value' => 'icon-pack'
						)
					)
				)
			) );
			$this->import_shortcode_options( array(
				'shortcode_base'    => 'etchy_core_icon',
				'exclude'           => array( 'link', 'target', 'margin' ),
				'additional_params' => array(
					'group'      => esc_html__( 'Title Icon', 'etchy-core' ),
					'dependency' => array(
						'show' => array(
							'icon_type' => array(
								'values'        => 'icon-pack',
								'default_value' => 'icon-pack'
							)
						)
					)
				)
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'icon_margin',
				'title'      => esc_html__( 'Icon Margin', 'etchy-core' ),
				'group'      => esc_html__( 'Title Icon', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textarea',
				'name'       => 'subtitle',
				'title'      => esc_html__( 'Subtitle', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'subtitle_color',
				'title'      => esc_html__( 'Subtitle Color', 'etchy-core' ),
				'group'      => esc_html__( 'Subtitle Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'subtitle_margin_top',
				'title'      => esc_html__( 'Subtitle Margin Top', 'etchy-core' ),
				'group'      => esc_html__( 'Subtitle Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textarea',
				'name'       => 'text',
				'title'      => esc_html__( 'Text', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'text_color',
				'title'      => esc_html__( 'Text Color', 'etchy-core' ),
				'group'      => esc_html__( 'Text Style', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'text_margin_top',
				'title'      => esc_html__( 'Text Margin Top', 'etchy-core' ),
				'group'      => esc_html__( 'Text Style', 'etchy-core' )
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
			
			$atts['holder_classes']  = $this->get_holder_classes( $atts );
			$atts['title']           = $this->get_modified_title( $atts );
			$atts['title_styles']    = $this->get_title_styles( $atts );
			$atts['icon_params']     = $this->generate_icon_params( $atts );
			$atts['icon_styles']     = $this->get_icon_styles( $atts );
			$atts['text_styles']     = $this->get_text_styles( $atts );
			$atts['subtitle_styles'] = $this->get_subtitle_styles( $atts );
			
			return etchy_core_get_template_part( 'shortcodes/section-title', 'templates/section-title', '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-section-title';
			$holder_classes[] = ! empty( $atts['content_alignment'] ) ? 'qodef-alignment--' . $atts['content_alignment'] : 'qodef-alignment--left';
			$holder_classes[] = $atts['disable_title_break_words'] === 'yes' ? 'qodef-title-break--disabled' : '';
			$holder_classes[] = ! empty( $atts['icon_type'] ) ? 'qodef--' . $atts['icon_type'] : '';
			$holder_classes[] = ($atts['enable_appear'] == 'yes' ) ? 'qodef--has-appear': '';
			
			return implode( ' ', $holder_classes );
		}

		private function get_modified_title( $atts ) {
			$title = $atts['title'];
			
			if( !function_exists('array_key_last') ) {
				function array_key_last(array $array) {
					if( !empty($array) ) return key(array_slice($array, -1, 1, true));
				}
			}

			if ( ! empty( $title ) ) {
				$split_title                     = explode( ' ', $title );
				$last_word_index                 = array_key_last( $split_title );
				$split_title[ $last_word_index ] = '<span class="qodef-last-word">' . $split_title[ $last_word_index ] . $this->get_icon( $atts ) . '</span>';

				if ( ! empty( $atts['line_break_positions'] ) ) {
					$line_break_positions = explode( ',', str_replace( ' ', '', $atts['line_break_positions'] ) );

					foreach ( $line_break_positions as $position ) {
						$position = intval($position);
						if ( isset( $split_title[ $position - 1 ] ) && ! empty( $split_title[ $position - 1 ] ) ) {
							$split_title[ $position - 1 ] = $split_title[ $position - 1 ] . '<br />';
						}
					}
				}
				
				$title = implode( ' ', $split_title );
				
				if ( ! empty( $atts['enable_appear'] ) ) {
					
					$output = explode("<br />", $title );
					
					foreach ( $output as $key => $value ) {
						$output[ $key ] = '<span class="qodef-m-title-line">' . $value . '</span>';
					}
					
					return implode( ' ', $output );
				}

				$title = implode( ' ', $split_title );
			}

			return $title;
		}
		
		private function get_title_styles( $atts ) {
			$styles = array();
			
			if ( ! empty( $atts['title_color'] ) ) {
				$styles[] = 'color: ' . $atts['title_color'];
			}
			
			return $styles;
		}

		private function get_icon( $atts ) {

			if ($atts['icon_type'] === 'icon-pack') {

				return '<span class="qodef-m-icon-wrapper" ' . qode_framework_get_inline_style( $this->get_icon_styles( $atts ) ) . '>' . EtchyCoreIconShortcode::call_shortcode( $this->generate_icon_params( $atts ) ) . '</span>';

			} else if ($atts['icon_type'] === 'custom-icon' && ! empty( $atts['custom_icon'] )) {

				return '<span class="qodef-m-icon-wrapper" ' . qode_framework_get_inline_style( $this->get_icon_styles( $atts ) ) . '>' . wp_get_attachment_image( $atts['custom_icon'], 'full' ) . '</span>';

			}

			return '';
		}

		private function generate_icon_params( $atts ) {
			$params = $this->populate_imported_shortcode_atts( array(
				'shortcode_base' => 'etchy_core_icon',
				'exclude'        => array( 'link', 'target', 'margin' ),
				'atts'           => $atts,
			) );
			
			return $params;
		}
		
		private function get_icon_styles( $atts ) {
			$styles = array();
			
			if ( $atts['icon_margin'] !== '' ) {
				if ( qode_framework_string_ends_with_space_units( $atts['icon_margin'] ) ) {
					$styles[] = 'margin: ' . $atts['icon_margin'];
				} else {
					$styles[] = 'margin: ' . intval( $atts['icon_margin'] ) . 'px';
				}
			}
			
			return $styles;
		}
		
		private function get_text_styles( $atts ) {
			$styles = array();
			
			if ( $atts['text_margin_top'] !== '' ) {
				if ( qode_framework_string_ends_with_space_units( $atts['text_margin_top'] ) ) {
					$styles[] = 'margin-top: ' . $atts['text_margin_top'];
				} else {
					$styles[] = 'margin-top: ' . intval( $atts['text_margin_top'] ) . 'px';
				}
			}
			
			if ( ! empty( $atts['text_color'] ) ) {
				$styles[] = 'color: ' . $atts['text_color'];
			}
			
			return $styles;
		}
		
		private function get_subtitle_styles( $atts ) {
			$styles = array();
			
			if ( $atts['subtitle_margin_top'] !== '' ) {
				if ( qode_framework_string_ends_with_space_units( $atts['subtitle_margin_top'] ) ) {
					$styles[] = 'margin-top: ' . $atts['subtitle_margin_top'];
				} else {
					$styles[] = 'margin-top: ' . intval( $atts['subtitle_margin_top'] ) . 'px';
				}
			}
			
			if ( ! empty( $atts['subtitle_color'] ) ) {
				$styles[] = 'color: ' . $atts['subtitle_color'];
			}
			
			return $styles;
		}
		
	}
}