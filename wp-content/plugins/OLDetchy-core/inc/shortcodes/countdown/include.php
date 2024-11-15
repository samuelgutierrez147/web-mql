<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/countdown/countdown.php';

foreach ( glob( ETCHY_CORE_SHORTCODES_PATH . '/countdown/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}