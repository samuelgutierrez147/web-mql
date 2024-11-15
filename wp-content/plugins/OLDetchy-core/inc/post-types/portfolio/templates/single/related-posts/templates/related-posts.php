<?php
$post_id       = get_the_ID();
$is_enabled    = etchy_core_get_post_value_through_levels( 'qodef_portfolio_single_enable_related_posts' );
$related_posts = etchy_core_get_custom_post_type_related_posts( $post_id, etchy_core_get_portfolio_single_post_taxonomies( $post_id ) );

if ( $is_enabled === 'yes' && ! empty( $related_posts ) && class_exists( 'EtchyCorePortfolioListShortcode' ) ) { ?>
	<div id="qodef-portfolio-single-related-items">
		<?php
		$params = apply_filters( 'etchy_core_filter_portfolio_single_related_posts_params', array(
			'custom_class'      => 'qodef--no-bottom-space',
			'columns'           => '3',
			'posts_per_page'    => 3,
			'additional_params' => 'id',
			'post_ids'          => $related_posts['items'],
			'layouts'           => 'info-below',
			'title_tag'         => 'h5',
			'excerpt_length'    => '100'
		) );
		
		echo EtchyCorePortfolioListShortcode::call_shortcode( $params ); ?>
	</div>
<?php } ?>