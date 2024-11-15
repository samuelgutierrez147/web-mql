<?php

include_once ETCHY_CORE_PLUGINS_PATH . '/woocommerce/shortcodes/product-list/product-list.php';

foreach ( glob( ETCHY_CORE_PLUGINS_PATH . '/woocommerce/shortcodes/product-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}