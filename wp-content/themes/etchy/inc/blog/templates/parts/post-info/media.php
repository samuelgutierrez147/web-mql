<div class="qodef-e-media">
	<?php switch ( get_post_format() ) {
		case 'gallery':
			etchy_template_part( 'blog', 'templates/parts/post-format/gallery' );
			break;
		case 'video':
			etchy_template_part( 'blog', 'templates/parts/post-format/video' );
			break;
		case 'audio':
			etchy_template_part( 'blog', 'templates/parts/post-format/audio' );
			break;
		default:
			etchy_template_part( 'blog', 'templates/parts/post-info/image' );
			break;
	} ?>
</div>