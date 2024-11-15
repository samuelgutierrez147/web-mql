<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/banner/banner.php';

foreach ( glob( ETCHY_CORE_INC_PATH . '/shortcodes/banner/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}