<?php

if ( is_user_logged_in() ) {
	$user_id   = get_current_user_id();
	$user_meta = get_user_meta( $user_id, 'qodef_user_wishlist_items', true );
}

$wishlist_class = isset( $user_meta ) && isset( $user_meta[ get_the_ID() ] ) ? 'qodef--added' : '';
$enable_title   = isset( $enable_title ) ? $enable_title : true;
?>
<div class="qodef-wishlist qodef-m">
	<a class="qodef-m-link <?php echo esc_attr( $wishlist_class ); ?>" href="#" data-id="<?php the_ID(); ?>">
		<?php if ( $enable_title ) {
			$wishlist_title       = esc_html__( 'Add to wishlist', 'etchy-core' );
			$wishlist_added_title = esc_html__( 'Added into wishlist', 'etchy-core' );
			?>
			<span class="qodef-m-link-label" data-title="<?php echo esc_attr( $wishlist_title ); ?>" data-added-title="<?php echo esc_attr( $wishlist_added_title ); ?>"><?php echo ! empty( $wishlist_class ) ? $wishlist_added_title : $wishlist_title; ?></span>
		<?php } ?>
		<?php echo qode_framework_icons()->render_icon( 'far fa-heart', 'font-awesome' ); ?>
	</a>
	<div class="qodef-m-response"></div>
</div>