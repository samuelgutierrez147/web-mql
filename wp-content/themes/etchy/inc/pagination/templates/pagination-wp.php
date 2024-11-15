<?php if ( get_the_posts_pagination() !== '' ): ?>
	
	<div class="qodef-m-pagination qodef--wp">
		<?php
		// Load posts pagination (in order to override template use navigation_markup_template filter hook)
		the_posts_pagination( array(
			'prev_text'          => etchy_get_icon( 'icon-arrows-left', 'linea-icons', etchy_arrow_left_svg() ),
			'next_text'          => etchy_get_icon( 'icon-arrows-right', 'linea-icons', etchy_arrow_right_svg() )
		) ); ?>
	</div>

<?php endif; ?>