<?php
class FullscreenSearch extends EtchyCoreSearch {
	private static $instance;

	public function __construct() {
		parent::__construct();
		add_action('etchy_action_page_footer_template', array($this, 'load_template'), 11); //after footer
	}
	
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}

	public function load_template() {
		if(is_active_widget(false,false,'etchy_core_search_opener')) {
			etchy_core_template_part('search/layouts/' . $this->search_layout, 'templates/' . $this->search_layout);
		}
	}
}