<div <?php qode_framework_class_attribute( $holder_classes ); ?> <?php qode_framework_inline_attr( $slider_attr, 'data-options' ); ?>>
	<div class="swiper-wrapper">
		<?php
		// Include items
		etchy_core_template_part( 'post-types/testimonials/shortcodes/testimonials-list', 'templates/loop', '', $params );
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