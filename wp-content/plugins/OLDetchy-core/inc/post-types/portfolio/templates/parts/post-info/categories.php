<?php
$categories = wp_get_post_terms( get_the_ID(), 'portfolio-category' );

if ( is_array( $categories ) && count( $categories ) ) { ?>
	<div class="qodef-e qodef-info--category">
		<h6 class="qodef-e-title"><?php esc_html_e( 'Category: ', 'etchy-core' ); ?></h6>
		<div class="qodef-e-categories">
			<?php $first_item = true;
			foreach ( $categories as $cat ) {
				if ( ! $first_item ) {
					echo ', ';
				} else {
					$first_item = false;
				} ?><a itemprop="url" class="qodef-e-category" href="<?php echo esc_url( get_term_link( $cat->term_id ) ); ?>">
					<?php echo esc_html( $cat->name ); ?></a><?php } ?>
		</div>
	</div>
<?php }
