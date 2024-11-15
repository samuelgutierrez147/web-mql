<a itemprop="url" href="#" class="qodef-m-link">
	<?php echo qode_framework_icons()->render_icon( 'far fa-heart', 'font-awesome', array( 'icon_attributes' => array( 'class' => 'qodef-m-link-icon' ) ) ); ?>
	<span class="qodef-m-link-count"><?php echo esc_html( $number_of_items ); ?></span>
</a>
<div class="qodef-m-items">
	<?php if ( ! empty( $number_of_items ) ) {
		$items = etchy_core_get_wishlist_items();
		
		foreach ( $items as $id => $title ) {
			$item_params          = array();
			$item_params['id']    = $id;
			$item_params['title'] = $title;
			
			etchy_core_template_part( 'wishlist', 'widgets/wishlist-dropdown/templates/item', '', $item_params );
		}
	}
	?>
</div>