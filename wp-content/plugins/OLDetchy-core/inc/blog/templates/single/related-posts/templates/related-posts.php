<?php
$post_id       = get_the_ID();
$is_enabled    = etchy_core_get_post_value_through_levels( 'qodef_blog_single_enable_related_posts' );
$related_posts = etchy_core_get_custom_post_type_related_posts( $post_id, etchy_core_get_blog_single_post_taxonomies( $post_id ) );

if ( $is_enabled === 'yes' && ! empty( $related_posts ) && class_exists( 'EtchyCoreBlogListShortcode' ) ) { ?>
	<div id="qodef-related-posts">
		<div class="qodef-related-posts-title" >
			<?php echo esc_html__( 'Related Posts', 'etchy-core' ); ?>
		</div>
		<?php
		$params = apply_filters( 'etchy_core_filter_blog_single_related_posts_params', array(
			'custom_class'      => 'qodef--no-bottom-space',
			'layout'            => 'standard',
			'images_proportion' => 'landscape',
			'enable_share'      => 'no',
			'enable_date'       => 'no',
			'enable_content'    => 'no',
			'enable_excerpt'    => 'no',
			'enable_button'     => 'no',
			'columns'           => '3',
			'posts_per_page'    => 3,
			'additional_params' => 'tax',
			'post_ids'          => $related_posts['items'],
			'title_tag'         => 'h6',
			'excerpt_length'    => '100'
		) );
		
		echo EtchyCoreBlogListShortcode::call_shortcode( $params ); ?>
	</div>
<?php } ?>