<?php

$author_socials = etchy_core_get_author_social_networks( $author_params['ID'] );

if ( ! empty( $author_socials ) ) { ?>
	<div class="qodef-e-social-icons">
		<?php foreach ( $author_socials as $social ) { ?>
			<a itemprop="url" class="<?php echo esc_attr( $social['class'] ) ?>" href="<?php echo esc_url( $social['url'] ) ?>" target="_blank">
				<?php echo qode_framework_icons()->render_icon( $social['icon'], 'elegant-icons' ); ?>
			</a>
		<?php } ?>
	</div>
<?php } ?>