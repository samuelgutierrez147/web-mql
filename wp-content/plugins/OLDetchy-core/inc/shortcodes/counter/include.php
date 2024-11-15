<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/counter/counter.php';

foreach ( glob( ETCHY_CORE_SHORTCODES_PATH . '/counter/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}