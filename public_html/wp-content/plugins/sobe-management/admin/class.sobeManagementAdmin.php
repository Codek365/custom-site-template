<?php

class sobeManagementAdmin
{

	private $_setting_options;
	
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'register') );

        /**Create form new website */
        // $this->add_new_option();
    }
    
    public function register()
    {
        // add_menu_page(
        //     'sobe Management',     // page title
        //     'sobe Management',     // menu title
        //     'manage_options',   // capability
        //     'sobe-management',     // menu slug
        //     array($this, 'createFormManageAccount') // callback function
        // );

        add_menu_page(
            'Sobe Dash Board',     // page title
            'Sobe Dash Board',     // menu title
            'manage_options',   // capability
            'form-create-new-website',     // menu slug
            array($this, 'set_first') // callback function
        );
    }

    public function fs_get_wp_config_path()
    {
        $base = dirname(__FILE__);
        $path = false;

        if (@file_exists(dirname(dirname($base))."/wp-config.php"))
        {
            $path = dirname(dirname($base))."/wp-config.php";
        }
        else
        if (@file_exists(dirname(dirname(dirname($base)))."/wp-config.php"))
        {
            $path = dirname(dirname(dirname($base)))."/wp-config.php";
        }
        else
            $path = false;

        if ($path != false)
        {
            $path = str_replace("\\", "/", $path);
        }
        return $path;
    }

    public function api_check()
    {
        $domain = 'khoa2.sobew.net';
        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'http://id.afterz.net/api/v1/domains/khoa2.sobew.net');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // // curl_setopt($ch, CURLOPT_POSTFIELDS, "domain=" . $domain);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // // curl_setopt($ch, CURLOPT_USERPWD, 'sobevn' . ':' . 'WBtech2020');

        // $headers = array();
        // $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $result = json_decode(curl_exec($ch), true);

        // var_dump($result);
        // // echo '<br>';
        
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);

        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, 'http://id.afterz.net/api/v1/domains/khoa2.sobew.net');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $phoneList = curl_exec($cURLConnection);

        curl_close($cURLConnection);

        $jsonArrayResponse = json_decode($phoneList, true);

        var_dump($jsonArrayResponse);

        return $jsonArrayResponse;
    }

    public function set_first()
    {

        $result = $this->api_check()['data'];
        

        if ($result['status'] == 1) {
            $theme_data = $result['theme'];

            $data_url = 'https://data.sobew.net/' . $theme_data . '.wpress';

            echo $data_url;
            $import_out = shell_exec(
                "wp ai1wm url restore $data_url --yes"
            );
            // echo "$import_out";
        }
        // } else {
        //     $theme_name = $result['theme_name'];
        //     $theme_url = $result['theme_url'];

        //     $theme_check = shell_exec(
        //         "wp theme is-active $theme_name && echo $?"
        //     );
        //     $theme_install = shell_exec(
        //         // "curl -s $theme_url > $path_theme_name &&" . 
        //         "wp theme install $theme_url --activate"
        //     );
        //     echo "$theme_check";
        //     if ($theme_check == 1) {
        //         echo "$theme_install";
        //     }
            
            
        // }
        

    }
}