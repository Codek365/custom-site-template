<?php

// echo WEBO_MANAGEMENT_PLUGIN_DIR . 'admin.php'; die;
class WeboManagementAdminPublic {	
	
	public function __construct(){		
		$this->init();
	}

	public function init(){
		require_once( WEBO_MANAGEMENT_PLUGIN_DIR . 'admin/class.weboManagementAdmin.php' );
		new WeboManagementAdmin();
    }
    
}