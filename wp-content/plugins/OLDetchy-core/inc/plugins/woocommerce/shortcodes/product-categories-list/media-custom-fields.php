<?php

if ( ! function_exists( 'etchy_core_add_product_category_options' ) ) {
	function etchy_core_add_product_category_options() {
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
					'field_type'  => 'select',
					'name'        => 'qodef_product_category_masonry_size',
					'title'       => esc_html__( 'Image Size', 'etchy-core' ),
					'description' => esc_html__( 'Choose image size for list shortcode item if masonry layout > fixed image size is selected in product categories list shortcode', 'etchy-core' ),
					'options'     => etchy_core_get_select_type_options_pool( 'masonry_image_dimension' )
				)
			);
		}
	}

	add_action( 'etchy_core_action_register_cpt_tax_fields', 'etchy_core_add_product_category_options' );
}