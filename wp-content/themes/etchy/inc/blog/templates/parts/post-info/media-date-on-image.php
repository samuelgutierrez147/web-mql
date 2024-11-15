<div class="qodef-e-media">
	<?php switch ( get_post_format() ) {
		case 'gallery':
			etchy_template_part( 'blog', 'templates/parts/post-format/gallery', 'date-on-image' );
			break;
		case 'video':
			etchy_template_part( 'blog', 'templates/parts/post-format/video', 'date-on-image' );
			break;
		case 'audio':
			etchy_template_part( 'blog', 'templates/parts/post-format/audio', 'date-on-image' );
			break;
		default:
			etchy_template_part( 'blog', 'templates/parts/post-info/image', 'date-on-image' );
			break;
	} ?>
</div>