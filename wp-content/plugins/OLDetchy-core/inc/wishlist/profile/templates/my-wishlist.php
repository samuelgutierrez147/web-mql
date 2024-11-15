<?php
$wishlist_items = etchy_core_get_wishlist_items();
?>
<div class="qodef-listing-profile-wishlist">
	<?php if ( ! empty( $wishlist_items ) ) { ?>
		<div class="qodef-lp-section-title">
			<h3 class="qodef-lp-st-title"><?php esc_html_e( 'My Wishlist', 'etchy-core' ); ?></h3>
			<p class="qodef-lp-st-text"><?php esc_html_e( 'This page contains all the items you have added to your personal wishlist. Add items to your wishlist by clicking the "heart" icon while logged in to your account.', 'etchy-core' ); ?></p>
		</div>
		<?php
		$included_items = array();
		foreach ( $wishlist_items as $id => $title ) {
			$included_items[] = $id;
		}
		
		if ( ! empty( $included_items ) && class_exists( 'EtchyCoreListingListShortcode' ) ) {
			$shortcode_params = array(
				'number_of_columns' => 'four',
				'selected_items'    => implode( ',', $included_items ),
				'enable_excerpt'    => 'no'
			);
			
			echo EtchyCoreListingListShortcode::call_shortcode( apply_filters( 'etchy_core_filter_wishlist_profile_page_params', $shortcode_params ) );
		}
	} else { ?>
		<h3 class="qodef-lp-not-found"><?php esc_html_e( 'Your wishlist is empty.', 'etchy-core' ); ?> </h3>
	<?php } ?>
</div>