<?php

class StandardMobileHeader extends EtchyCoreMobileHeader {
	private static $instance;

	public function __construct() {
		$this->slug = 'standard';
		$this->default_header_height = 70;

		parent::__construct();
	}
	
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
}