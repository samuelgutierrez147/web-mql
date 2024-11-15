<div class="qodef-m-item qodef-e">
	<?php if ( has_post_thumbnail( $id ) ) { ?>
		<a itemprop="url" class="qodef-e-image-link" href="<?php echo get_the_permalink( $id ); ?>">
			<?php echo get_the_post_thumbnail( $id, 'thumbnail', array( 'class' => 'qodef-e-image' ) ); ?>
		</a>
	<?php } ?>
	<h6 itemprop="name" class="qodef-e-title entry-title">
		<a itemprop="url" href="<?php echo get_the_permalink( $id ); ?>"><?php echo esc_html( $title ); ?></a>
	</h6>
	<a class="qodef-e-remove" href="#" data-id="<?php echo esc_attr( $id ); ?>">
		<?php echo qode_framework_icons()->render_icon( 'icon_close', 'elegant-icons' ); ?>
	</a>
</div>