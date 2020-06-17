<?php
class WeboManagementPublic {	
	
	public function __construct(){		
		$this->init();
	}

	public function init(){
		require_once( WEBO_MANAGEMENT_PLUGIN_DIR . 'class.weboManagement.php' );
		new weboManagement();
	}
}