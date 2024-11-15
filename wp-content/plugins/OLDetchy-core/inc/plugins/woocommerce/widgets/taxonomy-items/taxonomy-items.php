<?php

if ( ! function_exists( 'etchy_core_add_woo_taxonomy_items_widget' ) ) {
	/**
	 * Function that add widget into widgets list for registration
	 *
	 * @param array $widgets
	 *
	 * @return array
	 */
	function etchy_core_add_woo_taxonomy_items_widget( $widgets ) {
		$widgets[] = 'EtchyCoreWooTaxonomyItemsWidget';
		
		return $widgets;
	}
	
	add_filter( 'etchy_core_filter_register_widgets', 'etchy_core_add_woo_taxonomy_items_widget' );
}

if ( class_exists( 'QodeFrameworkWidget' ) ) {
	class EtchyCoreWooTaxonomyItemsWidget extends QodeFrameworkWidget {
		
		public function map_widget() {
			$this->set_base( 'etchy_core_woo_taxonomy_items' );
			$this->set_name( esc_html__( 'Etchy WooCommerce Taxonomy Items', 'etchy-core' ) );
			$this->set_description( esc_html__( 'Display a list of items for selected Woocommerce taxonomy', 'etchy-core' ) );
			$this->set_widget_option(
				array(
					'field_type' => 'text',
					'name'       => 'widget_title',
					'title'      => esc_html__( 'Title', 'etchy-core' ),
				)
			);
			$this->set_widget_option( array(
				'field_type'    => 'select',
				'name'          => 'taxonomy',
				'title'         => esc_html__( 'Taxonomy', 'etchy-core' ),
				'options'       => array(
					'product_cat' => esc_html__( 'Categories', 'etchy-core' ),
					'product_tag' => esc_html__( 'Tags', 'etchy-core' )
				),
				'default_value' => 'product_cat'
			) );
		}
		
		public function render( $atts ) {
			$styles = array();

			?>
			<div class="qodef-woo-taxonomy-items qodef-m" <?php qode_framework_inline_style( $styles ); ?>>
				<?php etchy_core_template_part( 'plugins/woocommerce/widgets/taxonomy-items', 'templates/content', '', $atts ); ?>
			</div>
			<?php
		}
	}
}