<?php

if ( ! function_exists( 'etchy_core_add_testimonials_showcase_shortcode' ) ) {
	/**
	 * Function that is adding shortcode into shortcodes list for registration
	 *
	 * @param array $shortcodes - Array of registered shortcodes
	 *
	 * @return array
	 */
	function etchy_core_add_testimonials_showcase_shortcode( $shortcodes ) {
		$shortcodes[] = 'EtchyCoreTestimonialsShowcaseShortcode';
		
		return $shortcodes;
	}
	
	add_filter( 'etchy_core_filter_register_shortcodes', 'etchy_core_add_testimonials_showcase_shortcode' );
}

if ( class_exists( 'EtchyCoreListShortcode' ) ) {
	class EtchyCoreTestimonialsShowcaseShortcode extends EtchyCoreListShortcode {
		
		public function __construct() {
			$this->set_post_type( 'testimonials' );
			$this->set_post_type_additional_taxonomies( array( 'testimonials-category' ) );
			$this->set_layouts( apply_filters( 'etchy_core_filter_testimonials_showcase_layouts', array() ) );
			$this->set_extra_options( apply_filters( 'etchy_core_filter_testimonials_showcase_extra_options', array() ) );
			
			parent::__construct();
		}
		
		public function map_shortcode() {
			$this->set_shortcode_path( ETCHY_CORE_CPT_URL_PATH . '/testimonials/shortcodes/testimonials-showcase' );
			$this->set_base( 'etchy_core_testimonials_showcase' );
			$this->set_name( esc_html__( 'Testimonials Showcase', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Shortcode that displays showcase of testimonials', 'etchy-core' ) );
			$this->set_category( esc_html__( 'Etchy Core', 'etchy-core' ) );
			$this->set_option( array(
				'field_type' => 'text',
				'name'       => 'custom_class',
				'title'      => esc_html__( 'Custom Class', 'etchy-core' )
			) );
			$this->map_slider_options();
			$this->map_query_options( array( 'post_type' => $this->get_post_type() ) );
			$this->map_layout_options( array( 'layouts' => $this->get_layouts() ) );
			$this->map_extra_options();
		}
		
		public function render( $options, $content = null ) {
			parent::render( $options );
			
			$atts = $this->get_atts();
			
			$atts['post_type'] = $this->get_post_type();
			
			$atts['behavior']  = 'slider';
			$atts['direction'] = 'vertical';

			// Additional query args
			$atts['additional_query_args'] = $this->get_additional_query_args( $atts );
			
			$atts['holder_classes'] = $this->get_holder_classes( $atts );
			$atts['item_classes']   = $this->get_item_classes( $atts );
			$atts['slider_attr']    = $this->get_slider_data( $atts );
			$atts['query_result']   = new \WP_Query( etchy_core_get_query_params( $atts ) );
			
			$atts['this_shortcode'] = $this;
			
			return etchy_core_get_template_part( 'post-types/testimonials/shortcodes/testimonials-showcase', 'templates/content', '', $atts );
		}
		
		private function get_holder_classes( $atts ) {
			$holder_classes = $this->init_holder_classes();
			
			$holder_classes[] = 'qodef-testimonials-showcase';
			$holder_classes[] = 'qodef-navigation--custom qodef-pagination--custom qodef-pagination--vertical';
			$holder_classes[] = isset( $atts['skin'] ) && ! empty( $atts['skin'] ) ? 'qodef-skin--' . $atts['skin'] : '';

			$list_classes   = $this->get_list_classes( $atts );
			$holder_classes = array_merge( $holder_classes, $list_classes );
			
			return implode( ' ', $holder_classes );
		}
		
		private function get_item_classes( $atts ) {
			$item_classes = $this->init_item_classes();
			
			$list_item_classes = $this->get_list_item_classes( $atts );
			
			$item_classes = array_merge( $item_classes, $list_item_classes );
			
			return implode( ' ', $item_classes );
		}
		
		public function get_title_styles( $atts ) {
			$styles = array();
			
			if ( ! empty( $atts['text_transform'] ) ) {
				$styles[] = 'text-transform: ' . $atts['text_transform'];
			}
			
			return $styles;
		}
	}
}