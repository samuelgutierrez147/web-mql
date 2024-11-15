<?php
$author = get_post_meta( get_the_ID(), 'qodef_testimonials_author', true );
$author_job = get_post_meta( get_the_ID(), 'qodef_testimonials_author_job', true );
if( ! empty ( $author ) ) { ?>
<div class="qodef-e-author">
	<h6 class="qodef-e-author-name">
		<?php echo esc_html( $author ); ?>
	</h6>
	<div class="qodef-e-author-job">
		<?php echo esc_html( $author_job ); ?>
	</div>
</div>
<?php }