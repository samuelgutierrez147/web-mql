<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/info-section/info-section.php';

foreach ( glob( ETCHY_CORE_SHORTCODES_PATH . '/info-section/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}