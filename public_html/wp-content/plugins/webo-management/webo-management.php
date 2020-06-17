<?php
/**
 * Plugin Name: Webo Management
 * Plugin URI: http://...
 * Description: Management
 * Version: 1.0
 * Author: DanhCV
 * author URI: http://...
 * license: GPLv2 or later
 */

// define('WEBO_PLUGIN_URL', plugin_dir_url(__FILE__));
// define('WEBO_PLUGIN_IMAGES_URL', WEBO_PLUGIN_URL . 'images');
// define('WEBO_PLUGIN_CSS_URL', WEBO_PLUGIN_URL . 'css');
// define('WEBO_PLUGIN_JS_URL', WEBO_PLUGIN_URL . 'js');


define('WEBOADMIN_MANAGEMENT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WEBOADMIN_MANAGEMENT_PLUGIN_IMAGES_URL', WEBOADMIN_MANAGEMENT_PLUGIN_URL . 'images');
define('WEBOADMIN_MANAGEMENT_PLUGIN_CSS_URL', WEBOADMIN_MANAGEMENT_PLUGIN_URL . 'css');
define('WEBOADMIN_MANAGEMENT_PLUGIN_JS_URL', WEBOADMIN_MANAGEMENT_PLUGIN_URL . 'js');

define('WEBO_MANAGEMENT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WEBO_MANAGEMENT_PLUGIN_VIEWS_DIR', WEBO_MANAGEMENT_PLUGIN_DIR . '/views');
define('WEBO_MANAGEMENT_PLUGIN_INCLUDES_DIR', WEBO_MANAGEMENT_PLUGIN_DIR . '/includes');

define('LINK_WEB_CUSTOMER', 'http://webo.com.vn/website_customer');
define('WEBO_MAIN_DOMAIN', 'webo.com.vn');
define('WEBO_PROTOCOL', 'http://');

if(!is_admin()){
	require_once WEBO_MANAGEMENT_PLUGIN_DIR . 'public.php';
	new WeboManagementPublic();

}else{
	require_once WEBO_MANAGEMENT_PLUGIN_DIR . 'admin.php';
	new WeboManagementAdminPublic();
}

