<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/button/button.php';

foreach ( glob( ETCHY_CORE_SHORTCODES_PATH . '/button/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}