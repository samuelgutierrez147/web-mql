<?php

if ( ! function_exists( 'etchy_core_register_testimonials_for_meta_options' ) ) {
	function etchy_core_register_testimonials_for_meta_options( $post_types ) {
		$post_types[] = 'testimonials';
		return $post_types;
	}
	
	add_filter( 'qode_framework_filter_meta_box_save', 'etchy_core_register_testimonials_for_meta_options' );
	add_filter( 'qode_framework_filter_meta_box_remove', 'etchy_core_register_testimonials_for_meta_options' );
}

if ( ! function_exists( 'etchy_core_add_testimonials_custom_post_type' ) ) {
	/**
	 * Function that adds testimonials custom post type
	 *
	 * @param array $cpts
	 *
	 * @return array
	 */
	function etchy_core_add_testimonials_custom_post_type( $cpts ) {
		$cpts[] = 'EtchyCoreTestimonialsCPT';
		
		return $cpts;
	}
	
	add_filter( 'etchy_core_filter_register_custom_post_types', 'etchy_core_add_testimonials_custom_post_type' );
}

if ( class_exists( 'QodeFrameworkCustomPostType' ) ) {
	class EtchyCoreTestimonialsCPT extends QodeFrameworkCustomPostType {
		
		public function map_post_type() {
			$name = esc_html__( 'Testimonials', 'etchy-core' );
			$this->set_base( 'testimonials' );
			$this->set_menu_position( 10 );
			$this->set_menu_icon( 'dashicons-format-status' );
			$this->set_slug( 'testimonials' );
			$this->set_name( $name );
			$this->set_path( ETCHY_CORE_CPT_PATH . '/testimonials' );
			$this->set_labels( array(
				'name'          => esc_html__( 'Etchy Testimonials', 'etchy-core' ),
				'singular_name' => esc_html__( 'Testimonial', 'etchy-core' ),
				'add_item'      => esc_html__( 'New Testimonial', 'etchy-core' ),
				'add_new_item'  => esc_html__( 'Add New Testimonial', 'etchy-core' ),
				'edit_item'     => esc_html__( 'Edit Testimonial', 'etchy-core' )
			) );
			$this->set_public( false );
			$this->set_archive( false );
			$this->set_supports( array(
				'title',
				'thumbnail'
			) );
			$this->add_post_taxonomy( array(
				'base'          => 'testimonials-category',
				'slug'          => 'testimonials-category',
				'singular_name' => esc_html__( 'Category', 'etchy-core' ),
				'plural_name'   => esc_html__( 'Categories', 'etchy-core' ),
			) );
		}
		
	}
}