<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/image-marquee/image-marquee.php';

foreach ( glob( ETCHY_CORE_INC_PATH . '/shortcodes/image-marquee/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}