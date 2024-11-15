<?php

include_once ETCHY_CORE_CPT_PATH . '/portfolio/shortcodes/portfolio-list/portfolio-list.php';

foreach ( glob( ETCHY_CORE_CPT_PATH . '/portfolio/shortcodes/portfolio-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}