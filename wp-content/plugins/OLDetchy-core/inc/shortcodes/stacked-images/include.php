<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/stacked-images/stacked-images.php';

foreach ( glob( ETCHY_CORE_SHORTCODES_PATH . '/stacked-images/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}