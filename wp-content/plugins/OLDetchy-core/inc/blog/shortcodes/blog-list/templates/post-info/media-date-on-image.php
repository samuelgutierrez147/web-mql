<div class="qodef-e-media">
	<?php switch ( get_post_format() ) {
		case 'gallery':
			etchy_core_theme_template_part( 'blog', 'templates/parts/post-format/gallery','date-on-image', $params );
			break;
		case 'video':
			etchy_core_theme_template_part( 'blog', 'templates/parts/post-format/video','date-on-image', $params );
			break;
		case 'audio':
			etchy_core_theme_template_part( 'blog', 'templates/parts/post-format/audio','date-on-image', $params );
			break;
		default:
			etchy_core_template_part( 'blog/shortcodes/blog-list', 'templates/post-info/image','date-on-image', $params );
			break;
	} ?>
</div>