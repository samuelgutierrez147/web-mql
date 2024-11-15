<?php

if ( ! function_exists( 'etchy_core_register_portfolio_for_meta_options' ) ) {
	function etchy_core_register_portfolio_for_meta_options( $post_types ) {
		$post_types[] = 'portfolio-item';
		
		return $post_types;
	}
	
	add_filter( 'qode_framework_filter_meta_box_save', 'etchy_core_register_portfolio_for_meta_options' );
	add_filter( 'qode_framework_filter_meta_box_remove', 'etchy_core_register_portfolio_for_meta_options' );
}

if ( ! function_exists( 'etchy_core_add_portfolio_custom_post_type' ) ) {
	/**
	 * Function that adds portfolio custom post type
	 *
	 * @param array $cpts
	 *
	 * @return array
	 */
	function etchy_core_add_portfolio_custom_post_type( $cpts ) {
		$cpts[] = 'EtchyCorePortfolioCPT';
		
		return $cpts;
	}
	
	add_filter( 'etchy_core_filter_register_custom_post_types', 'etchy_core_add_portfolio_custom_post_type' );
}

if ( class_exists( 'QodeFrameworkCustomPostType' ) ) {
	class EtchyCorePortfolioCPT extends QodeFrameworkCustomPostType {
		
		public function map_post_type() {
			$name = esc_html__( 'Portfolio', 'etchy-core' );
			$this->set_base( 'portfolio-item' );
			$this->set_menu_position( 10 );
			$this->set_menu_icon( 'dashicons-grid-view' );
			$this->set_slug( 'portfolio-item' );
			$this->set_name( $name );
			$this->set_path( ETCHY_CORE_CPT_PATH . '/portfolio' );
			$this->set_labels( array(
				'name'          => esc_html__( 'Etchy Portfolio', 'etchy-core' ),
				'singular_name' => esc_html__( 'Portfolio Item', 'etchy-core' ),
				'add_item'      => esc_html__( 'New Portfolio Item', 'etchy-core' ),
				'add_new_item'  => esc_html__( 'Add New Portfolio Item', 'etchy-core' ),
				'edit_item'     => esc_html__( 'Edit Portfolio Item', 'etchy-core' )
			) );
			$this->add_post_taxonomy( array(
				'base'          => 'portfolio-category',
				'slug'          => 'portfolio-category',
				'singular_name' => esc_html__( 'Category', 'etchy-core' ),
				'plural_name'   => esc_html__( 'Categories', 'etchy-core' ),
			) );
			$this->add_post_taxonomy( array(
				'base'          => 'portfolio-tag',
				'slug'          => 'portfolio-tag',
				'singular_name' => esc_html__( 'Tag', 'etchy-core' ),
				'plural_name'   => esc_html__( 'Tags', 'etchy-core' ),
			) );
		}
	}
}