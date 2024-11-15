<?php
$fake_card = end( $items );
?>
<div <?php qode_framework_class_attribute( $holder_classes ); ?>>
	<div class="qodef-e-inner">
		<?php
		$i = 1;
		foreach ( $items as $item ) {
			$image_original = wp_get_attachment_image_src( $item['item_image'], 'full' );
			$item['url']    = $image_original[0];
			$item['alt']    = get_post_meta( $item['item_image'], '_wp_attachment_image_alt', true );
			?>
			<div class="qodef-m-card">
				<div class="qodef-m-bundle-item" data-bundle-move-top="<?php echo esc_attr( $i * 300 ); ?>">
					<?php if ( $item['item_link'] !== '' ){ ?>
						<a href="<?php echo esc_url( $item['item_link'] ) ?>" target="<?php echo esc_attr( $link_target ) ?>">
					<?php } ?>
						<img src="<?php echo esc_url( $item['url'] ); ?>" alt="<?php echo esc_attr( $item['alt'] ); ?>" />
					<?php if ( $item['item_link'] !== '' ){ ?>
						</a>
					<?php }
				$i ++;
				?>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="qodef-m-fake-card">
		<?php
		$image_original   = wp_get_attachment_image_src( $fake_card['item_image'], 'full' );
		$fake_card['url'] = $image_original[0];
		$fake_card['alt'] = get_post_meta( $fake_card['item_image'], '_wp_attachment_image_alt', true );
		?>
		<img src="<?php echo esc_url( $fake_card['url'] ); ?>" alt="<?php echo esc_attr( $fake_card['alt'] ); ?>" />
	</div>
</div>