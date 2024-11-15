<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/tabs/tab.php';
include_once ETCHY_CORE_SHORTCODES_PATH . '/tabs/tab-child.php';

foreach ( glob( ETCHY_CORE_SHORTCODES_PATH . '/tabs/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}