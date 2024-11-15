<?php

if ( ! function_exists( 'etchy_core_add_check_list_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param $shortcodes array
	 *
	 * @return array
	 */
	function etchy_core_add_check_list_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreCheckListShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_check_list_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreCheckListShortcode extends EtchyCoreShortcode {
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_SHORTCODES_URL_PATH . '/check-list' );
			$this->set_base( 'etchy_core_check_list' );
			$this->set_name( esc_html__( 'Check List', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that adds check price list holder', 'etchy-core' ) );
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
				'field_type'    => 'select',
				'name'          => 'title_tag',
				'title'         => esc_html__( 'Title Tag', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'title_tag' ),
				'default_value' => 'h6'
			) );
			$this->set_option( array(
				'field_type' => 'textarea',
				'name'       => 'text_side',
				'title'      => esc_html__( 'Text Side', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'currency',
				'title'      => esc_html__( 'Currency', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'button_text',
				'title'      => esc_html__( 'Button Text', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'button_url',
				'title'      => esc_html__( 'Button Url', 'etchy-core' )
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'button_target',
				'title'         => esc_html__( 'Button Target', 'etchy-core' ),
				'options'       => etchy_core_get_select_type_options_pool( 'link_target' ),
				'default_value' => '_self'
			) );
			$this->set_option( array(
				'field_type' => 'repeater',
				'name'       => 'children',
				'title'      => esc_html__( 'Child elements', 'etchy-core' ),
				'items'      => array(
					array(
						'field_type' => 'text',
						'name'       => 'item_price',
						'title'      => esc_html__( 'Price', 'etchy-core' )
					),
					array(
						'field_type' => 'text',
						'name'       => 'item_title',
						'title'      => esc_html__( 'Title', 'etchy-core' )
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
			$atts['total_price']    = $this->getPrice( $atts );
			
			return etchy_core_get_template_part( 'shortcodes/check-list', 'templates/check-list', '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-check-list';
			$holder_classes[] = ! empty( $atts['skin'] ) ? 'qodef-skin--' . $atts['skin'] : '';
			
			return implode( ' ', $holder_classes );
		}
		
		private function getPrice( $atts ) {
			
			$total_price = 0;
			
			if ( is_array( $atts['items'] ) && count( $atts['items'] ) > 0 ) {
				foreach ( $atts['items'] as $item ) {
					if ( $item['item_active'] == 'yes' ) {
						$total_price += (float) $item['item_price'];
					}
				}
			}
			
			return $total_price;
		}
	}
}