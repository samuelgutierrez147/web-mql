<div class="qodef-e qodef-info--date">
	<h6 class="qodef-e-title"><?php esc_html_e( 'Date: ', 'etchy-core' ); ?></h6>
	<p itemprop="dateCreated" class="entry-date updated"><?php the_time( get_option( 'date_format' ) ); ?></p>
	<meta itemprop="interactionCount" content="UserComments: <?php echo get_comments_number( get_the_ID() ); ?>"/>
</div>