<?php

if ( ! function_exists( 'etchy_core_add_icon_with_text_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_icon_with_text_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreIconWithTextShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_icon_with_text_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreIconWithTextShortcode extends EtchyCoreShortcode {
		
		public function __construct() {
			$this->set_layouts( apply_filters( 'etchy_core_filter_icon_with_text_layouts', array() ) );
			
			$options_map = etchy_core_get_variations_options_map( $this->get_layouts() );
			$default_value = $options_map['default_value'];
			
			$this->set_extra_options( apply_filters( 'etchy_core_filter_icon_with_text_extra_options', array(), $default_value ) );
			
			parent::__construct();
		}
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/icon-with-text' );
			$this->set_base( 'etchy_core_icon_with_text' );
			$this->set_name( esc_html__( 'Icon With Text', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds icon with text element', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'custom_class',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' ),
			) );
			
			$options_map = etchy_core_get_variations_options_map( $this->get_layouts() );
			
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'layout',
				'title'         => esc_html__( 'Layout', 'etchy-core' ),
				'options'		=> $this->get_layouts(),
				'default_value' => $options_map['default_value'],
				'visibility'    => array( 'map_for_page_builder' => $options_map['visibility'] )
			) );
			$this->set_option( array(
				'field_type'    => 'text',
				'name'          => 'link',
				'title'         => esc_html__( 'Link', 'etchy-core' ),
				'default_value' => ''
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'target',
				'title'         => esc_html__( 'Link Target', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'link_target' ),
				'default_value' => '_self'
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
				'group'         => esc_html__( 'Icon', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'image',
				'name'       => 'custom_icon',
				'title'      => esc_html__( 'Custom Icon', 'etchy-core' ),
				'group'      => esc_html__( 'Icon', 'etchy-core' ),
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
				'exclude'           => array( 'custom_class', 'link', 'target', 'margin' ),
				'additional_params' => array(
					'group'      => esc_html__( 'Icon', 'etchy-core' ),
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
				'name'       => 'title',
				'title'      => esc_html__( 'Title', 'etchy-core' ),
				'group'      => esc_html__( 'Content', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'title_tag',
				'title'         => esc_html__( 'Title Tag', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'title_tag' ),
				'default_value' => 'h3',
				'group'         => esc_html__( 'Content', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'title_color',
				'title'      => esc_html__( 'Title Color', 'etchy-core' ),
				'group'      => esc_html__( 'Content', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'title_margin_top',
				'title'      => esc_html__( 'Title Margin Top', 'etchy-core' ),
				'group'      => esc_html__( 'Content', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textarea',
				'name'       => 'text',
				'title'      => esc_html__( 'Text', 'etchy-core' ),
				'group'      => esc_html__( 'Content', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'text_color',
				'title'      => esc_html__( 'Text Color', 'etchy-core' ),
				'group'      => esc_html__( 'Content', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'text_margin_top',
				'title'      => esc_html__( 'Text Margin Top', 'etchy-core' ),
				'group'      => esc_html__( 'Content', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'enable_appear',
				'title'         => esc_html__( 'Enable Appear Animation', 'etchy-core' ),
				'default_value' => 'yes',
				'options'       => etchy_core_get_select_type_options_pool( 'yes_no', false ),
			) );
			
			$this->map_extra_options();
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['title_styles']   = $this->get_title_styles( $atts );
			$atts['text_styles']    = $this->get_text_styles( $atts );
			$atts['icon_params']    = $this->generate_icon_params( $atts );
			
			return etchy_core_get_template_part( 'shortcodes/icon-with-text', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-icon-with-text';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['icon_type'] ) ? 'qodef--' . $atts['icon_type'] : '';
			$holder_classes[] = ($atts['enable_appear'] == 'yes' ) ? 'qodef--has-custom-appear': '';
			
			$holder_classes = apply_filters( 'etchy_core_filter_icon_with_text_variation_classes', $holder_classes, $atts );
			
			return implode( ' ', $holder_classes );
		}
		
		private function get_title_styles( $atts ) {
			$styles = array();
			
			if ( $atts['title_margin_top'] !== '' ) {
				if ( qode_framework_string_ends_with_space_units( $atts['title_margin_top'] ) ) {
					$styles[] = 'margin-top: ' . $atts['title_margin_top'];
				} else {
					$styles[] = 'margin-top: ' . intval( $atts['title_margin_top'] ) . 'px';
				}
			}
			
			if ( ! empty( $atts['title_color'] ) ) {
				$styles[] = 'color: ' . $atts['title_color'];
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
		
		private function generate_icon_params( $atts ) {
			$params = $this->populate_imported_shortcode_atts( array(
				'shortcode_base' => 'etchy_core_icon',
				'exclude'        => array( 'custom_class', 'link', 'target', 'margin' ),
				'atts'           => $atts,
			) );
			
			return $params;
		}
	}
}