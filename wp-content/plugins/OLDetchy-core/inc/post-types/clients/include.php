<?php

include_once ETCHY_CORE_CPT_PATH . '/clients/helper.php';

foreach ( glob( ETCHY_CORE_CPT_PATH . '/clients/dashboard/meta-box/*.php' ) as $module ) {
	include_once $module;
}