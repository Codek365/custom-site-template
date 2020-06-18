<?php

// echo sobe_MANAGEMENT_PLUGIN_DIR . 'admin.php'; die;
class SobeManagementAdminPublic {	
	
	public function __construct(){		
		$this->init();
	}

	public function init(){
		require_once( SOBE_MANAGEMENT_PLUGIN_DIR . 'admin/class.sobeManagementAdmin.php' );
		new SobeManagementAdmin();
    }
    
}