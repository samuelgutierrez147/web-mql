<?php

if ( ! function_exists( 'etchy_core_add_info_section_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_info_section_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreInfoSectionShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_info_section_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreInfoSectionShortcode extends EtchyCoreShortcode {
		
		public function __construct() {
			$this->set_layouts( apply_filters( 'etchy_core_filter_info_section_layouts', array() ) );
			
			$options_map = etchy_core_get_variations_options_map( $this->get_layouts() );
			$default_value = $options_map['default_value'];
			
			$this->set_extra_options( apply_filters( 'etchy_core_filter_info_section_extra_options', array(), $default_value ) );
			
			parent::__construct();
		}
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/info-section' );
			$this->set_base( 'etchy_core_info_section' );
			$this->set_name( esc_html__( 'Info Section', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds info section element', 'etchy-core' ) );
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
				'visibility'    => array(
					'map_for_page_builder' => $options_map['visibility']
				)
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'title',
				'title'      => esc_html__( 'Title', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'title_tag',
				'title'         => esc_html__( 'Title Tag', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'title_tag' ),
				'default_value' => 'h4'
			) );
			$this->set_option( array(
				'field_type'  => 'text',
				'name'        => 'line_break_positions',
				'title'       => esc_html__( 'Positions of Line Break', 'etchy-core' ),
				'description' => esc_html__( 'Enter the positions of the words after which you would like to create a line break. Separate the positions with commas (e.g. if you would like the first, third, and fourth word to have a line break, you would enter "1,3,4")', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'disable_title_break_words',
				'title'         => esc_html__( 'Disable Title Line Break', 'etchy-core' ),
				'description'   => esc_html__( 'Enabling this option will disable title line breaks for screen size 1024 and lower', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'no_yes', false ),
				'default_value' => 'no'
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'title_color',
				'title'      => esc_html__( 'Title Color', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'textarea',
				'name'       => 'info_text',
				'title'      => esc_html__( 'Text', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'color',
				'name'       => 'info_text_color',
				'title'      => esc_html__( 'Text Color', 'etchy-core' )
			) );
			$this->import_shortcode_options( array(
				'shortcode_base'    => 'etchy_core_button',
				'exclude'           => array( 'custom_class' ),
				'additional_params' => array(
					'group' => esc_html__( 'Button', 'etchy-core' ),
				)
			) );
			$this->map_extra_options();
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['button_params']  = $this->generate_button_params( $atts );
			$atts['title']          = $this->get_modified_title( $atts );
			$atts['title_styles']   = $this->get_title_styles( $atts );
			$atts['text_styles']    = $this->get_text_styles( $atts );
			
			$atts = apply_filters( 'etchy_core_filter_info_section_variation_atts', $atts );
			
			return etchy_core_get_template_part( 'shortcodes/info-section', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-info-section';
			$holder_classes[] = ! empty ( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[]  = $atts['disable_title_break_words'] === 'yes' ? 'qodef-title-break--disabled' : '';
			$holder_classes = apply_filters( 'etchy_core_filter_info_section_variation_classes', $holder_classes, $atts );
			
			return implode( ' ', $holder_classes );
		}
		
		private function generate_button_params( $atts ) {
			$params = $this->populate_imported_shortcode_atts( array(
				'shortcode_base' => 'etchy_core_button',
				'exclude'        => array( 'custom_class' ),
				'atts'           => $atts,
			) );
			
			return $params;
		}
		
		private function get_modified_title( $atts ) {
			$title = $atts['title'];
			
			if ( ! empty( $title ) && ! empty( $atts['line_break_positions'] ) ) {
				$split_title          = explode( ' ', $title );
				$line_break_positions = explode( ',', str_replace( ' ', '', $atts['line_break_positions'] ) );
				
				foreach ( $line_break_positions as $position ) {
					$position = intval($position);
					if ( isset( $split_title[ $position - 1 ] ) && ! empty( $split_title[ $position - 1 ] ) ) {
						$split_title[ $position - 1 ] = $split_title[ $position - 1 ] . '<br />';
					}
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
		
		private function get_text_styles( $atts ) {
			$styles = array();
			
			if ( ! empty( $atts['info_text_color'] ) ) {
				$styles[] = 'color: ' . $atts['info_text_color'];
			}
			
			return $styles;
		}
	}
}