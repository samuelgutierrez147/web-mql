<?php

if ( ! function_exists( 'etchy_core_add_product_taxonomy_options' ) ) {
	function etchy_core_add_product_taxonomy_options() {
		$qode_framework = qode_framework_get_framework_root();
		
		$page = $qode_framework->add_options_page(
			array(
				'scope' => array( 'product_cat' ),
				'type'  => 'taxonomy',
				'slug'  => 'product_cat',
			)
		);

		if ( $page ) {
			$page->add_field_element(
				array(
					'field_type'  => 'textarea',
					'name'        => 'qodef_product_cat_svg_icon',
					'title'       => esc_html__( 'SVG Code', 'etchy-core' ),
					'description' => esc_html__( 'Choose svg icon for custom widget items', 'etchy-core' )
				)
			);
		}

		$page = $qode_framework->add_options_page(
			array(
				'scope' => array( 'product_tag' ),
				'type'  => 'taxonomy',
				'slug'  => 'product_tag',
			)
		);

		if ( $page ) {
			$page->add_field_element(
				array(
					'field_type'  => 'textarea',
					'name'        => 'qodef_product_tag_svg_icon',
					'title'       => esc_html__( 'SVG Code', 'etchy-core' ),
					'description' => esc_html__( 'Choose svg icon for custom widget items', 'etchy-core' )
				)
			);
		}
	}
	
	add_action( 'etchy_core_action_register_cpt_tax_fields', 'etchy_core_add_product_taxonomy_options' );
}