<div <?php qode_framework_class_attribute( $holder_classes ); ?> <?php qode_framework_inline_attr( $slider_attr, 'data-options' ); ?>>
	<div class="qodef-pag-alt">
		<?php
		if ( $query_result->have_posts() ) {
			while ( $query_result->have_posts() ) : $query_result->the_post();

				etchy_core_template_part( 'post-types/testimonials/shortcodes/testimonials-showcase', 'templates/post-info/image', '', $params );
			endwhile; // End of the loop.
		}
		?>
	</div>
	<div class="qodef-custom-swiper-wrapper swiper-wrapper">
		<?php
		if ( $query_result->have_posts() ) {
			while ( $query_result->have_posts() ) : $query_result->the_post();

				etchy_core_template_part( 'post-types/testimonials/shortcodes/testimonials-showcase', 'templates/item', '', $params );
			endwhile; // End of the loop.
		} else {
			etchy_core_template_part( 'post-types/testimonials/shortcodes/testimonials-showcase', 'templates/posts-not-found' );
		}

		wp_reset_postdata();
		?>
	</div>
	<?php if ( $slider_pagination !== 'no' ) { ?>
		<div class="swiper-pagination"></div>
	<?php } ?>
	<?php if ( $slider_navigation !== 'no' ) { ?>
		<div class="qodef-swiper-navigation-holder">
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
	<?php } ?>
</div>