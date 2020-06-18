<?php
/**
 * Plugin Name: Sobe Management
 * Plugin URI: http://repo.afterz.net/wp/sobe.1.0.zip
 * Description: Management
 * Version: 1.0
 * Author: KhoaDolphin
 * author URI: http://afterz.net
 * license: GPLv2 or later
 */

define('SOBEADMIN_MANAGEMENT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SOBEADMIN_MANAGEMENT_PLUGIN_IMAGES_URL', SOBEADMIN_MANAGEMENT_PLUGIN_URL . 'images');
define('SOBEADMIN_MANAGEMENT_PLUGIN_CSS_URL', SOBEADMIN_MANAGEMENT_PLUGIN_URL . 'css');
define('SOBEADMIN_MANAGEMENT_PLUGIN_JS_URL', SOBEADMIN_MANAGEMENT_PLUGIN_URL . 'js');

define('SOBE_MANAGEMENT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SOBE_MANAGEMENT_PLUGIN_VIEWS_DIR', SOBE_MANAGEMENT_PLUGIN_DIR . '/views');
define('SOBE_MANAGEMENT_PLUGIN_INCLUDES_DIR', SOBE_MANAGEMENT_PLUGIN_DIR . '/includes');

define('LINK_WEB_CUSTOMER', 'http://SOBE.com.vn/website_customer');
define('SOBE_MAIN_DOMAIN', 'sobe.com.vn');
define('SOBE_PROTOCOL', 'http://');

if(!is_admin()){
	require_once SOBE_MANAGEMENT_PLUGIN_DIR . 'public.php';
	new SobeManagementPublic();

}else{
	require_once SOBE_MANAGEMENT_PLUGIN_DIR . 'admin.php';
	new SobeManagementAdminPublic();
}

