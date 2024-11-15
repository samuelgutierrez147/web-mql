<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/custom-font/custom-font.php';

foreach ( glob( ETCHY_CORE_SHORTCODES_PATH . '/custom-font/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}