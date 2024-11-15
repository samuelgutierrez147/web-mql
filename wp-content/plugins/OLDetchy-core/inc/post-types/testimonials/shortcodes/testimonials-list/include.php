<?php

include_once ETCHY_CORE_CPT_PATH . '/testimonials/shortcodes/testimonials-list/testimonials-list.php';

foreach ( glob( ETCHY_CORE_CPT_PATH . '/testimonials/shortcodes/testimonials-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}