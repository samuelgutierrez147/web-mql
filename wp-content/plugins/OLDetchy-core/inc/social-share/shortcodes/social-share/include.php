<?php

include_once ETCHY_CORE_INC_PATH . '/social-share/shortcodes/social-share/social-share.php';

foreach ( glob( ETCHY_CORE_INC_PATH . '/social-share/shortcodes/social-share/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}