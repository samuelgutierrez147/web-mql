<?php

if ( ! function_exists( 'etchy_core_add_social_share_shortcode' ) ) {
	/**
	 * Function that add shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_social_share_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreSocialShareShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_social_share_shortcode' );
}

if ( class_exists( 'EtchyCoreShortcode' ) ) {
	class EtchyCoreSocialShareShortcode extends EtchyCoreShortcode {
		
		public function __construct() {
			$this->set_layouts( apply_filters( 'etchy_core_filter_social_share_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'etchy_core_filter_social_share_extra_options', array() ) );
			
			parent::__construct();
		}
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_INC_URL_PATH . '/social-share/shortcodes/social-share' );
			$this->set_base( 'etchy_core_social_share' );
			$this->set_name( esc_html__( 'Social Share', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that displays social share networks', 'etchy-core' ) );
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
					'map_for_page_builder' => $options_map['visibility'],
					'map_for_widget' => $options_map['visibility']
				)
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'dropdown_behavior',
				'title'         => esc_html__( 'Dropdown Hover Behavior', 'etchy-core' ),
				'options'       => array(
					'bottom' => esc_html__( 'Animate to Bottom', 'etchy-core' ),
					'right'  => esc_html__( 'Animate on Right', 'etchy-core' ),
					'left'   => esc_html__( 'Animate on Left', 'etchy-core' )
				),
				'default_value' => 'bottom',
				'dependency'    => array(
					'show' => array(
						'layout' => array(
							'values'        => 'dropdown',
							'default_value' => 'list'
						)
					)
				)
			) );
			$this->set_option( array(
				'field_type'    => 'select',
				'name'          => 'icon_font',
				'title'         => esc_html__( 'Icons Font', 'etchy-core' ),
				'options'       => array(
					'font-awesome'      => esc_html__( 'Font Awesome', 'etchy-core' ),
					'elegant-icons'     => esc_html__( 'Elegant Icons', 'etchy-core' ),
					'ionicons'          => esc_html__( 'Ionicons', 'etchy-core' ),
					'simple-line-icons' => esc_html__( 'Simple Line Icons', 'etchy-core' )
				),
				'default_value' => 'font-awesome',
				'dependency'    => array(
					'show' => array(
						'layout' => array(
							'values'        => array( 'list', 'dropdown' ),
							'default_value' => 'list'
						)
					)
				)
			) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'title',
				'title'      => esc_html__( 'Social Share Title', 'etchy-core' )
			) );
			$this->map_extra_options();
		}
		
		public static function call_shortcode( $params ) {
			$html = qode_framework_call_shortcode( 'etchy_core_social_share', $params );
			$html = str_replace( "\n", '', $html );
			
			return $html;
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			$atts = $this->get_atts();
			
			$atts['holder_classes']  = $this->get_holder_classes( $atts );
			$atts['social_networks'] = $this->get_social_network_items( $atts );
			
			return etchy_core_get_template_part( 'social-share/shortcodes/social-share', 'variations/' . $atts['layout'] . '/templates/' . $atts['layout'], '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-social-share';
			$holder_classes[] = 'clear';
			$holder_classes[] = ! empty( $atts['layout'] ) ? 'qodef-layout--' . $atts['layout'] : '';
			$holder_classes[] = ! empty( $atts['layout'] ) && $atts['layout'] == 'dropdown' && ! empty( $atts['dropdown_behavior'] ) ? 'qodef-dropdown--' . $atts['dropdown_behavior'] : '';
			
			return implode( ' ', $holder_classes );
		}
		
		/**
		 * Get Social Networks data to display
		 * @return array
		 */
		public function get_social_network_items( $atts ) {
			$networks             = array();
			$social_networks_list = etchy_core_enabled_social_networks_list();
			
			if ( ! empty( $social_networks_list ) ) {
				$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				
				foreach ( $social_networks_list as $network => $labels ) {
					$params['layout'] = $atts['layout'];
					$params['name']   = $network;
					$params['text']   = $labels['shorten'];
					$params['link']   = etchy_core_get_social_network_share_link( $network, $image );
					
					$icon_params = array(
						'icon_attributes' => array(
							'class' => 'qodef-social-network-icon'
						)
					);
					
					// Override icon pack for VK social network because those packages doesn't have icon for it
					if ( $network === 'vk' && in_array( $atts['icon_font'], array( 'elegant-icons', 'simple-line-icons' ) ) ) {
						$atts['icon_font'] = 'font-awesome';
					}
					
					$params['icon'] = qode_framework_icons()->get_specific_icon_from_pack( $network, $atts['icon_font'], $icon_params );
					$net            = etchy_core_get_template_part( 'social-share/shortcodes/social-share', 'templates/parts/network', '', $params );
					
					$networks[] = $net;
				}
			}
			
			return $networks;
		}
	}
}