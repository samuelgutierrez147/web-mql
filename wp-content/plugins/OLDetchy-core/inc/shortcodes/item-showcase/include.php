<?php

include_once ETCHY_CORE_SHORTCODES_PATH . '/item-showcase/item-showcase.php';

foreach ( glob( ETCHY_CORE_SHORTCODES_PATH . '/item-showcase/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}