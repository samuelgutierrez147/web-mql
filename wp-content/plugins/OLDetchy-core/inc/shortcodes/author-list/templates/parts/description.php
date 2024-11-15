<?php

$author_description	= get_the_author_meta( 'description', $author_params['ID'] );

?>
<p itemprop="description" class="qodef-e-description">
	<?php echo esc_html( $author_description ); ?>
</p>