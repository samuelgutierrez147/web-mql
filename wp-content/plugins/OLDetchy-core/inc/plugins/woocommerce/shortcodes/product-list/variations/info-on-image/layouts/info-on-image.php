<div <?php wc_product_class( $item_classes ); ?>>
    <div class="qodef-woo-product-inner">
		<?php if ( has_post_thumbnail() ) { ?>
            <div class="qodef-woo-product-image">
				<?php etchy_core_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/mark' ); ?>
				<?php etchy_core_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/image', '', $params ); ?>
                <div class="qodef-woo-product-image-inner">
	                <div class="qodef-woo-product-image-inner-2">
		                <div class="qodef-woo-product-content-top">
	                        <?php etchy_core_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/price', '', $params ); ?>
	                        <?php etchy_core_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/title', '', $params ); ?>
	                        <?php etchy_core_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/category', '', $params ); ?>
		                </div>
		                <div class="qodef-woo-product-button-holder">
                            <?php etchy_core_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/add-to-cart' ); ?>
		                </div>
						<?php
						// Hook to include additional content inside product list item image
						do_action( 'etchy_core_action_product_list_item_additional_image_content' );
						?>
	                </div>
                </div>
            </div>
		<?php } ?>
		<?php etchy_core_template_part( 'plugins/woocommerce/shortcodes/product-list', 'templates/post-info/link' ); ?>
    </div>
</div>