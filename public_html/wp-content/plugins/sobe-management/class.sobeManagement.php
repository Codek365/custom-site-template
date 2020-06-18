<?php
class sobeManagement 
{

	private $_setting_options;
	
    public function __construct()
    {
        // add_shortcode( 'sobe-user-domains', array($this, 'sobeUserDomainsShortcode'));
        // add_shortcode( 'sobe-register', array($this, 'register'));
        // add_shortcode( 'sobe-login', array($this, 'login'));
        // // add_action('wp_login', 'register');

        // /** Create New Website */
        // add_action( 'sobe_single_product_summary', array($this, 'createFormCreateNewWebsite'));
        // add_shortcode( 'sobe-create-new-website', array($this, 'sobeCreateNewWebsiteShortcode'));
    }

    /**
     * Check customer login
     */
    public function isLogin()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'customer_users';

        $cookie = isset($_COOKIE['wb_security'])?$_COOKIE['wb_security']:'';
        if ($cookie != '') {
            $query = "SELECT * FROM $table WHERE cookie = '$cookie'";
            $user = $wpdb->get_row($query);
            if ( null !== $user ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}