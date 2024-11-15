<?php
$tags = get_the_tags();

if ( $tags ) { ?>
	<div class="qodef-e-info-item qodef-e-info-tags">
		<span class="qodef-info-tags-title"><?php echo esc_html__( 'Tag:', 'etchy' )?></span>
		<?php the_tags( '', '', '' ); ?>
	</div>
<?php } ?>