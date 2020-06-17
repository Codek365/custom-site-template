<?php

class weboManagementAdmin
{

	private $_setting_options;
	
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'register') );

        /**Create form new website */
        $this->add_new_option();
    }
    
    public function register()
    {
        add_menu_page(
            'Webo Management',     // page title
            'Webo Management',     // menu title
            'manage_options',   // capability
            'webo-management',     // menu slug
            array($this, 'createFormManageAccount') // callback function
        );

        add_menu_page(
            'Form create new website',     // page title
            'Form create new website',     // menu title
            'manage_options',   // capability
            'form-create-new-website',     // menu slug
            array($this, 'createFormCreateNewWebsite') // callback function
        );
    }

    public function createFormManageAccount()
    {
        if (isset($_POST['webo-save-change'])) {
            $url_main = $_POST['webo-url-main'];
            update_option('webo_url_main', $url_main);	
            update_option('webo_url_master', $url_master);	
        } 
        
        echo '<div class="wrap">';
        echo '<h2> Chỉnh sửa địa chỉ tới trang chính</h2>';
        echo '';
        echo'
            <div class="container">     
            <p class="submit"><input type="submit" name="webo-save-change" id="webo-save-change" class="button button-primary" value="Lưu thay đổi"></p>   
                <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John</td>
                        <td>Doe</td>
                        <td>john@example.com</td>
                    </tr>
                    <tr>
                        <td>Mary</td>
                        <td>Moe</td>
                        <td>mary@example.com</td>
                    </tr>
                    <tr>
                        <td>July</td>
                        <td>Dooley</td>
                        <td>july@example.com</td>
                    </tr>
                </tbody>
                </table>
            </div>
        ';
        echo '</div>';
    }

    public function createFormCreateNewWebsite()
    {
        $url_main = get_option('webo_url_main','');
        $url_master = get_option('webo_url_master','');

        if (isset($_POST['webo-save-change'])) {
            $url_main = $_POST['webo-url-main'];
            update_option('webo_url_main', $url_main);	
            update_option('webo_url_master', $url_master);	
        } 
        
        echo '<div class="wrap">';
        echo '<h2> Chỉnh sửa địa chỉ tới trang chính</h2>';
        echo'
        <form method="POST" action="">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="email">Liên kết tới trang chính:</label></th>
                        <td>
                            <input class="regular-text all-options" type="text" name="webo-url-main" id="webo-url-main" value="'.$url_main.'">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="email">Liên kết kiểm tra tền miền:</label></th>
                        <td>
                            <input class="regular-text all-options" type="text" name="webo-url-master" id="webo-url-master" value="'.$url_master.'">
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="webo-save-change" id="webo-save-change" class="button button-primary" value="Lưu thay đổi"></p>
            </form>
        ';
        echo '</div>';
    }

    public function add_new_option(){
        add_option('webo_url_main','new.webo.vn','','yes');
        add_option('webo_url_master','','','yes');
	}

}