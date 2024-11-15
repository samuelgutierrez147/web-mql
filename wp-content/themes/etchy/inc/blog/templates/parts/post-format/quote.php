<?php
$quote_meta = get_post_meta( get_the_ID(), 'qodef_post_format_quote_text', true );
$quote_text = ! empty( $quote_meta ) ? $quote_meta : get_the_title();

if ( ! empty( $quote_text ) ) {
	$quote_author     = get_post_meta( get_the_ID(), 'qodef_post_format_quote_author', true );
	$quote_degree     = get_post_meta( get_the_ID(), 'qodef_post_format_quote_degree', true );
	$title_tag        = isset( $title_tag ) && ! empty( $title_tag ) ? $title_tag : 'h4';
	$author_title_tag = isset( $author_title_tag ) && ! empty( $author_title_tag ) ? $author_title_tag : 'span';
	$degree_title_tag = isset( $degree_title_tag ) && ! empty( $degree_title_tag ) ? $degree_title_tag : 'span';
	?>
	
	<?php
	$blog_list_image_id = get_post_meta( get_the_ID(), 'qodef_blog_list_image', true );;
	$has_featured = ! empty( $blog_list_image_id ) || has_post_thumbnail();
	
	$bg_image_styles = '';
	
	if ( $has_featured ) {
		
		if ( ! empty( $blog_list_image_id ) ) {
			$bg_image_url = wp_get_attachment_image_src( $blog_list_image_id, 'full' );
		} else {
			$bg_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		}
		
		$bg_image_styles .= ' background-image: url(' . $bg_image_url[0] . ');';
		$bg_image_styles .= ' background-repeat: no-repeat;';
		$bg_image_styles .= ' background-size: cover;';
		$bg_image_styles .= ' background-position-x: right;';
	}
	?>
	
	<div class="qodef-e-quote">
	 <div class="qodef-image-holder" style="<?php echo esc_attr( $bg_image_styles ); ?>"></div>
		<span class="qodef-quote-mark"><?php echo etchy_quote_svg(); ?></span>
			<<?php echo esc_attr( $title_tag ); ?> class="qodef-e-quote-text"><?php echo esc_html( $quote_text ); ?></<?php echo esc_attr( $title_tag ); ?>>
			<?php if ( ! empty( $quote_author ) ) { ?>
				<<?php echo esc_attr( $author_title_tag ); ?> class="qodef-e-quote-author"><?php echo esc_html( $quote_author ); ?></<?php echo esc_attr( $author_title_tag ); ?>>
			<?php } ?>
			<?php if ( ! empty( $quote_degree ) ) { ?>
				<<?php echo esc_attr( $degree_title_tag ); ?> class="qodef-e-quote-degree"><?php echo esc_html( $quote_degree ); ?></<?php echo esc_attr( $degree_title_tag ); ?>>
			<?php } ?>
			<?php if ( ! is_single() ) { ?>
				<a itemprop="url" class="qodef-e-quote-url" href="<?php the_permalink(); ?>"></a>
			<?php } ?>
	</div>
<?php } ?>