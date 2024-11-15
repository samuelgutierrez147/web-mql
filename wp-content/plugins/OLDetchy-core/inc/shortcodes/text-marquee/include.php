<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/text-marquee/text-marquee.php';

foreach ( glob( ETCHY_CORE_INC_PATH . '/shortcodes/text-marquee/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}