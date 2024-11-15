<?php

include_once ETCHY_CORE_CPT_PATH . '/clients/shortcodes/clients-list/clients-list.php';

foreach ( glob( ETCHY_CORE_CPT_PATH . '/clients/shortcodes/clients-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}