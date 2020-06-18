<?php
class SobeManagementPublic {	
	
	public function __construct(){		
		$this->init();
	}

	public function init(){
		require_once( SOBE_MANAGEMENT_PLUGIN_DIR . 'class.sobeManagement.php' );
		new SobeManagement();
	}
}