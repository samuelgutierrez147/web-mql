<?php

include_once ETCHY_CORE_INC_PATH . '/blog/shortcodes/blog-list/blog-list.php';

foreach ( glob( ETCHY_CORE_INC_PATH . '/blog/shortcodes/blog-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}