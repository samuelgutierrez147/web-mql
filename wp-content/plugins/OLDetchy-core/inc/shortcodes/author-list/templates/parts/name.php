<?php
$title_tag   = isset( $title_tag ) && ! empty( $title_tag ) ? $title_tag : 'h4';
$author_link = get_author_posts_url( $author_params['ID'] );
?>

<<?php echo esc_attr( $title_tag ); ?> itemprop="name" class="qodef-e-title entry-title" <?php qode_framework_inline_style( $this_shortcode->get_title_styles( $params ) ); ?>>
	<a itemprop="url" class="qodef-e-title-link" href="<?php echo esc_url( $author_link ); ?>">
		<?php echo esc_html( $author_params['name'] ); ?>
	</a>
</<?php echo esc_attr( $title_tag ); ?>>