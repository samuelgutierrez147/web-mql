<?php
// Include items
$authors = $query_result->get_results();

if ( count ( $authors ) ) {
	foreach ( $authors as $author ) {
		$author_params = array();
		$author_params['ID'] = $author->ID;
		$author_params['name'] = $author->display_name;

		$params['author_params'] = $author_params;

		// Include post item
		etchy_core_template_part( 'shortcodes/author-list', 'variations/' . $layout . '/templates/' . $layout, '', $params );
	}
} else {
	// Include global posts not found
	etchy_core_theme_template_part( 'content', 'templates/parts/posts-not-found' );
}
