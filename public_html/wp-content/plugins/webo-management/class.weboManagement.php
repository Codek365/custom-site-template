<?php
class weboManagement 
{

	private $_setting_options;
	
    public function __construct()
    {
        add_shortcode( 'webo-user-domains', array($this, 'weboUserDomainsShortcode'));
        add_shortcode( 'webo-register', array($this, 'register'));
        add_shortcode( 'webo-login', array($this, 'login'));
        // add_action('wp_login', 'register');

        /** Create New Website */
        add_action( 'webo_single_product_summary', array($this, 'createFormCreateNewWebsite'));
        add_shortcode( 'webo-create-new-website', array($this, 'weboCreateNewWebsiteShortcode'));
    }

    /**
     * set value $_POST
     */
    public function issetInputRequest($input) {
        if (isset($_REQUEST[$input])) {
            return $_REQUEST[$input];
        } else {
            return '';
        }
    }

     /**
     * Registration user
     */
    public function register()
    {
        $msg = '';
        $_REQUEST['webo_domain'] =  $this->issetInputRequest('webo_domain');
        $_REQUEST['webo_phone'] = $this->issetInputRequest('webo_phone');
        $_REQUEST['webo_email'] = $this->issetInputRequest('webo_email');
        $_REQUEST['webo_name'] = $this->issetInputRequest('webo_name');$_REQUEST['webo_domain'] =  $this->issetInputRequest('webo_domain');
        $_REQUEST['webo_phone'] = $this->issetInputRequest('webo_phone');
        $_REQUEST['webo_email'] = $this->issetInputRequest('webo_email');
        $_REQUEST['webo_name'] = $this->issetInputRequest('webo_name');
        $_REQUEST['webo_password'] = $this->issetInputRequest('webo_password');
        $_REQUEST['webo_passwordConfirm'] = $this->issetInputRequest('webo_passwordConfirm');
        $_REQUEST['webo_id'] = $this->issetInputRequest('webo_id');

        if ($this->isLogin()) {
            header("location:/");
        }
        if ($_REQUEST['webo_id'] == '') {
            return 'bạn chưa chọn mẫu website để tạo. Vui lòng chọn mâu website tại <a href="/kho-giao-dien-web">đây</a> để bắt đầu tạo!';
        }
        if (isset($_POST['webo_register'])) {
            $arr_post = $this->stripTagsInput($_POST);
            extract($arr_post);
            $webo_domain = $this->formatDomain($webo_domain);
            $webo_email = strtolower($webo_email);
            
            $is_email = $this->isEmail($webo_email);
            $is_domain = $this->isDomain($webo_domain);

            if (!empty($webo_id) && !empty($webo_domain) && !empty($webo_email)) {
                if (!$is_domain) {
                    // $msg = 'errorDomainFormat';
                    $msg = 'Tên miền không hợp lệ. Vui lòng nhập lại tên miền khác!';
                } else if (!$is_email) {
                    // $msg = 'errorEmail';
                    $msg = 'Email không hợp lệ. Vui lòng nhập đúng email!';
                } else if ($this->checkExistEmail($webo_email)) {
                    // $msg = 'errorDuplicateEmail';
                    $msg = "Email $webo_email đã được sử dụng. Vui lòng nhập email khác!";
                } else if ($this->checkDuplicate($webo_domain)) {
                    // $msg = 'errorDomain';
                    $msg = "Tên miền $webo_domain đã được sử dụng. Vui lòng nhập tên miền khác!";
                } else if ($webo_password !== $webo_passwordConfirm) {
                    // $msg = 'errorPassword';
                    $msg = 'Mật khẩu không khớp nhau. Vui lòng nhập lại!';
                } else {
                    $cookie_id = md5($webo_email.$webo_name);
                    $cookie = ['wb_security' => $cookie_id];
                    $webo_password = md5($webo_password);
                    $data_customer = array('phone' => $webo_phone, 'email' => $webo_email, 'password' => $webo_password, 'name' => $webo_name, 'cookie' => $cookie_id);
                    $user_id = $this->insert('customer_users', $data_customer);
                    if ($user_id !== false) {
                        $this->setCookieCustomerUser($cookie);

                        // //Create info file web
                                //Create info file 
                        // echo 'start';
                        // $pathInfoNewWebsite = './info_new_website/all/website20.txt';
                        // $content = '{
                        //                 "webo_domain": "website1",
                        //                 "id": "3528",
                        //                 "action": "create"
                        //             }';
                        // $this->writeFile($pathInfoNewWebsite, $content);
                        // echo 'end';
                        // die;





                        //Begin Insert info user's domain end create new Website
                        $data_web = array('domain' => $webo_domain, 'theme_id' => $webo_id, 'user_id' => $user_id);
                        if ($this->insert('khachdangkyweb', $data_web) !== false) {
                            $id = $_REQUEST['webo_id'];
                            $_REQUEST['webo_domain'] = $this->formatDomain($_REQUEST['webo_domain']);
                            $arr_post = $_REQUEST;
                            $arr_post['action'] = 'create';
                            $arr_post['id'] = $id;
                            $url_main = get_option('webo_url_main','');
                            if ($this->post_info_create_web($url_main, $arr_post) === true) {
                                header("location:/tai-khoan/");
                            } else {
                                echo 'Lỗi tạo website. Quý khách vui lòng thử lại hoặc liên hệ với chúng tôi để được hỗ trợ.';
                            }
                        }
                        //End Insert info user's domain end create new Website
                    }
                }
            } else {
                $msg = 'errorEmpty';
            }
        }

        $this->renderFormRegistration($msg, $_REQUEST);
    }

    /**
     * validate email
     */
    public function isEmail($email) {
        $pattern_email = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/u';
        $is_email = preg_match($pattern_email, $email, $matches);
        return $is_email;
    }

    /**
     * Insert user's customer into wp_custommer_users
     */
    public function insert($tableNoPrefix, $data)
    {
        global $wpdb;
        $table = $wpdb->prefix . $tableNoPrefix;

        $wpdb->insert($table, $data);
        $lastid = $wpdb->insert_id;
        if (is_int($lastid)) {
            return $lastid;
        } else {
            return false;
        }
    }

    /**
     * Update customer user into wp_customer_users
     */
    public function update($tableNoPrefix, $data, $id) {
        global $wpdb;
        $table = $wpdb->prefix . $tableNoPrefix;
        $where = ['id' => $id];

        $resUpdate = $wpdb->update($table, $data, $where);
        
        if( $resUpdate === false ){
            return false;
        } else {
            return $id;
        }
    }


    /**
     * Render form registration
     */
    public function renderFormRegistration($msg = '', $req = '')
    {
        // if ($req != '') {
            extract($req);
        // } else {
        //     $webo_name = '';
        // }
        echo '
        <div class="logregform two">       
            <div class="title">	
                <h3>Đăng ký</h3>			
                <p>
                    Đã có tài khoản? 
                    &nbsp;<a href="/dang-nhap/">Đăng nhập.</a></p>		
            </div>
            
            <div class="feildcont">	
                <form id="webo_registration_form" class="king-form" name="weboregisterform" method="post" action="/dang-ky/" novalidate="novalidate">
                
                    <label>Họ và tên</label>
                    <input name="webo_name" value="'.$webo_name.'" type="text">

                    <label>Tên website <em>*</em></label>
                    <input type="text" name="webo_domain" value="'.$webo_domain.'" placeholder="">
                    
                    <label>Số điện thoại <em>*</em></label>
                    <input type="email" name="webo_phone" value="'.$webo_phone.'" placeholder="">

                    <label>Email <em>*</em></label>
                    <input type="email" name="webo_email" value="'.$webo_email.'" placeholder="">
                    
                    <div class="one_half">
                        <label>Mật khẩu <em>*</em></label>
                        <input type="password" name="webo_password" value="'.$webo_password.'" placeholder="" id="password">
                    </div>
                    
                    <div class="one_half last">
                        <label>Xác nhận mật khẩu <em>*</em></label>
                        <input type="password" name="webo_passwordConfirm" value="'.$webo_passwordConfirm.'" placeholder="">
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="checkbox">
                        <input type="checkbox" name="argee" checked="checked">
                        <label>Tôi đồng ý với<a href="#">quy định sử dụng</a>  &<a href="#">chính sách bảo mật</a> của webo</label>
                    </div>
                    <p class="status">'.$msg.'</p>
                    <input type="hidden" name="webo_id" value="'.$webo_id.'">
                    <input type="hidden" name="action" value="register_create">
                    <button type="submit" name="webo_register" class="fbut btn-register">Đăng ký</button>	
                </form>	
            </div>
        </div>
        ';
    }

    /**
     * Check exist email in wp_customer_users
     */
    public function checkExistEmail($email)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'customer_users';
        $emails = $wpdb->get_results( "SELECT email FROM $table" );
        if (count($emails) > 0) {
            foreach ($emails as $item) {
                if ($item->email == $email) {
                   return true;
                } 
            }
        } 
        return false;
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

    /**
     * Customer user login
     */
    public function login()
    {
        if ($this->isLogin()) {
            header("location:/");
        }
        global $wpdb;
        $msg = '';
        $_REQUEST['webo_email'] = $this->issetInputRequest('webo_email');
        $_REQUEST['webo_password'] = $this->issetInputRequest('webo_password');
        if (isset($_REQUEST['action'])) {

            switch ($_REQUEST['action']) {
                case 'webo_login':
                    $this->stripTagsInput($_REQUEST);
                    $arr_request= $this->stripTagsInput($_REQUEST);
                    extract($arr_request);
        
                    $webo_password = md5($webo_password);
        
                    $table = $wpdb->prefix . 'customer_users';
                    $query = "SELECT * FROM $table WHERE email = '$webo_email' AND password = '$webo_password'";
                    $user = $wpdb->get_row($query);
            
                    if ($user !== null) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $cookie_id = md5($user->email.$ip.$user->id);
                        $cookie = ['wb_security' => $cookie_id];
                        $dataCustomer = array('cookie' => $cookie_id);
                        $resUpdate = $this->update('customer_users', $dataCustomer, intval($user->id));
                        if ($resUpdate !== false) {
                            $this->setCookieCustomerUser($cookie);
                            header('location: /tai-khoan/');
                        }
                    } else {
                        $msg = 'Email hoặc mật khẩu không đúng. Thử lại!';
                    }
                    break;
                default:
                    echo 'Error';
                    break;
            }

        }
        $this->renderFormLogin($msg, $_REQUEST);
    }

    /**
     * Remove tag
     */
    public function stripTagsInput(&$arr) {
        foreach ($arr as $key => $value) {
            $arr[$key] = strip_tags($value);
        }
        return $arr;
    }

    /**
     * Render form login
     */
    public function renderFormLogin($msg='', $req = '')
    {
        extract($req);
        echo '
        <div class="logregform">        
            <div class="title">        
                <h3>Đăng nhập tài khoản</h3>        		         
            </div>
            
            <div class="feildcont">        
                <form id="webo_login_form" method="post" name="loginform" action="" class="king-form" novalidate="novalidate">      
                    <label><i class="fa fa-user"></i> Email</label>       
                    <input type="text" name="webo_email" value="'.$webo_email.'">
                    
                    <label><i class="fa fa-lock"></i> Mật khẩu</label>
                    <input type="password" name="webo_password" value="'.$webo_password.'">
                    
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="webo_rememberme">
                        </label>
                        <label>Remember Me</label>
                        <label>
                                <a href="http://webo.vn/?action=forgot">
                                    <strong>Quên mật khẩu?</strong>
                                </a>
                        </label>
                    </div>
                    
                    <p class="status">'.$msg.'</p>
                    
                    <button type="submit" class="fbut btn-login">Đăng nhập</button>  
        
                    <input type="hidden" name="action" value="webo_login">
                    <input type="hidden" id="security" name="security" value="5d5ea93065"><input type="hidden" name="_wp_http_referer" value="/login-form/">		</form>        
            </div>  
        </div>
        ';
    }

   
  
    
    /**
     * Get customer id
     */
    public function getCustomerInfo() {
        global $wpdb;
        $table = $wpdb->prefix . 'customer_users';

        $cookie = isset($_COOKIE['wb_security'])?$_COOKIE['wb_security']:'';
        $cookie = strip_tags($cookie);
        if($cookie != '') {
            $query = "SELECT * FROM $table WHERE cookie = '$cookie'";
            $user = $wpdb->get_row($query);
            if ( null !== $user ) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Count user's domain
     */
    public function countUserDomains($current_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'khachdangkyweb';
        
        $domains = $wpdb->get_results( "SELECT domain FROM $table WHERE user_id = $current_id" );

        return count($domains);
    }

    /**
     * 
     */
    public function listUserDomains($current_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'khachdangkyweb';
        
        $domains = $wpdb->get_results( "SELECT domain FROM $table WHERE user_id = $current_id" );

        return $domains;
    }


    /**
     * create record domains user
     */
    public function createRecordUserDomains($domains)
    {
        $resultHtml = '';
        $i = 0;
        foreach ($domains as $value) {
            $resultHtml .= '<tr>';
            $resultHtml .= '<td class="text-center">'.++$i.'</td>';
            $resultHtml .= '<td><a href="'.WEBO_PROTOCOL.$value->domain.'.'.WEBO_MAIN_DOMAIN.'">'.$value->domain.'</a></td>';
            $resultHtml .= '<td class="text-center"><input name="delete" class="btn btn-secondary" type="submit" value="Delelte"></td>';
            $resultHtml .= '</tr>';
        }
        return $resultHtml;
    }


    /**
     * ///////////////////////////////////////////////////////////////////////////////////
     */
    function weboCreateNewWebsiteShortcode($args, $content)
    {
        $this->createFormCreateNewWebsite();
    }

    /**
     * Display Infomation new website
     */
    public function show() 
    {
        global $wpdb;
        $table = $wpdb->prefix . 'khachdangkyweb';
        var_dump($newWebsite);
    }

    /**
     * Generate token
     */
    public function generateToken()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $uniqid = uniqid(mt_rand(), true);
       //Return the hash
        return md5($ip . $uniqid);
    }

    /**
     * Generate token
     */
    public function outputToken($tokenName)
    {
        $token = $this->generateToken();
        return '<input name='.$tokenName.' id="'.$tokenName.'" value="'.$token.'" type="hidden"/>';
    }

    /**
     * Render form to input website's infomation
     */
    public function renderFormCreateNewWebsite($msg = '', $url='') 
    {
        global $wp;

        if (get_the_ID() !== false) {
            $domain = isset($_POST['webo_domain']) ? $_POST['webo_domain'] : '';
            $email = isset($_POST['webo_email']) ? $_POST['webo_email'] : '';
            $id = get_the_ID();
            //'.$this->outputToken('webo_new_website_token').'
            echo'
                <div id="webo_module_new_website" class="webo-createform-new-website">
                    <form action="'.$url.'" method="post">
                        <input type="hidden" name="webo_id" value="'.$id.'"></input>
                        <div class="form-group">
                            <label for="domain">Tên miền</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="domain" name="webo_domain" value="'.$domain.'" placeholder="Nhập tên website" aria-describedby="basic-addon2">
                                <span class="input-group-addon" id="basic-addon2">.webo.vn</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="webo_email" value="'.$email.'" class="form-control" placeholder="Nhập email">
                        </div>
                        <div class="webo-alert"> '.$msg.'</div>
                        <input type="hidden" name="action" value="login_create"></input>
                        <input name="create" class="btn btn-create" type="submit" value="Tạo Website">
                    </form>
                </div>
                ';
        }
    }

    /**
     * Post info to create new website with curl
     */
    public function post_info_create_web($url= '', $param = '')
    {   
        // $cURL = curl_init();
        // $options = [
        //     CURLOPT_URL => $url,
        //     CURLOPT_HEADER => false,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTREDIR => 3,
        //     CURLOPT_POST => count($param),
        //     CURLOPT_POSTFIELDS => $param
        // ];
        // curl_setopt_array($cURL, $options);
        // $response = curl_exec($cURL);
        // $errno = curl_errno($cURL);
        // $err = curl_error($cURL);
        
        // if ($errno) {
        //     echo "cURL Error #:" . $err;
        //     $info = curl_getinfo($ch);
        //     echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
        //     return $err;
        // } else {
        //     $res = json_decode($response);
        //     if ($res->status == 'done') {
        //         return true;
        //     }
        // }
        // curl_close($cURL);
        // return false;

        $domain = 'test123';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://103.92.25.15:3333/CMD_API_SUBDOMAINS');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "json=yes&domain=webo.vn&action=create&subdomain=".$domain);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, 'webovn' . ':' . 'WBtech2020');

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        echo "<pre>" . $result . "</pre>";
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
    }


    public function createFormCreateNewWebsite() 
    {
        
        $msg = '';
        $customerInfo = $this->getCustomerInfo();
        if (wc_get_product() === false) {
            echo 'Bạn chưa chọn mẫu website để tạo. Vui lòng chọn mẫu website tại <a href="/kho-giao-dien-web">đây</a> để bắt đầu tạo!';
        } else {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'login_create':
                        $arr_post = $this->stripTagsInput($_POST);
                        extract($arr_post);
                        $webo_domain = $this->formatDomain($webo_domain);
                        if ($this->isLogin()) {
                            if ($customerInfo !== false) {
                                $product = wc_get_product();
                                $id = $product->get_id();
                                $token = $this->generateToken();
                                $notification = $this->insertCustomerRegisterWebsite($token);
                                $_POST['webo_domain'] = $this->formatDomain($_POST['webo_domain']);
                                $arr_post = $_POST;
                                $arr_post['action'] = 'create';
                                $arr_post['id'] = $id;
                                $arr_post['token'] = $token;
                    
                                switch ($notification) {
                                    case 'errorEmail':
                                        $msg = 'Email không đúng. Quý khách vui lòng nhập đúng email';
                                        break;
                                    case 'errorDomain':
                                        $msg = 'Domain đã tồn tại';
                                        break;
                                    case 'errorEmpty':
                                        $msg = 'Quý khách vui lòng nhập đầy đủ thông tin';
                                        break;
                                    case 'success':
                                        if ($this->countUserDomains($customerInfo->id) < $customerInfo->quantity) {
                                            $msg = 'Đăng ký thành công.'; 
                                            $url_main = get_option('webo_url_main','');
                                            if ($this->post_info_create_web($url_main, $arr_post) === true) {
                                                echo '$msg = '.$msg;
                                                header("location:/tai-khoan/");
                                            } else {
                                                echo 'Lỗi tạo website. Quý khách vui lòng thử lại hoặc liên hệ với chúng tôi để được hỗ trợ.';
                                            }
                                        } else {
                                            header("location:/tai-khoan/");
                                        }
                                        break;
                                    default:
                                        $msg = '';
                                        break;
                                }
                            } else {
                                echo 'Đã login';
                                header("location:/tai-khoan/");
                            }
                            
                        } else {
                            echo 'Nhận post từ form tạo web';
                            echo 'Chưa login';
                            $product = wc_get_product();
                            $id = $product->get_id();
                            header("location:/dang-ky/?webo_email=".$_POST['webo_email']."&webo_domain=".$_POST['webo_domain']."&webo_id=".$id);
                        }
                        break;
                    
                    default:
                        # code...
                        break;
                }
        }

        }
        $url = get_option('webo_url_master','');
        $this->renderFormCreateNewWebsite($msg, $url);
    }

    /**
     * Set cookie
     */
    public function setCookieCustomerUser($arr) {
        foreach ($arr as $key => $value) {
            setcookie($key, $value, time() + (86400*30),'/');
        }
    }

    /**
     * Delete cookie
     */
    public function deleteCookieCustomerUser($arr) {
        foreach ($arr as $key => $value) {
            setcookie($key, $value, time() - (86400*30),'/');
        }
    }

    /**
     * Check exists domain
     */
    public function checkDuplicate($domain)
    {
        // $newDomain = $_POST['domain'];
        global $wpdb;
        $table = $wpdb->prefix . 'khachdangkyweb';
        
        $domains = $wpdb->get_results( "SELECT domain FROM $table" );
        if (count($domains) > 0) {
            foreach ($domains as $item) {
                if ($item->domain == $domain) {
                   return true;
                } 
            }
            
        } return false;
    }

    /** 
     * Get infomation website that just created
     */
    public function insertCustomerRegisterWebsite($token)
    {
        global $wpdb;
        $msg = '';
        // $current_user = wp_get_current_user();
        // $id = $product->get_id();
        $arr_post = $this->stripTagsInput($_POST);
        extract($arr_post);

        $webo_domain = $this->formatDomain($webo_domain);
        $webo_email = strtolower($webo_email);

        $is_email = $this->isEmail($webo_email);
        $is_domain = $this->isDomain($webo_domain);

        $customerInfo = $this->getCustomerInfo();
        $customerId = $this->getCustomerInfo()->id;
        if (isset($_POST['create']) && !empty($webo_id) && !empty($webo_domain) && !empty($webo_email)) {
            if (!$is_email) {
                return 'errorEmail';
            } 
            if ($this->checkDuplicate($webo_domain)) {
                return 'errorDomain';
            } else if ($this->countUserDomains($customerInfo->id) < $customerInfo->quantity){
                $data_web = array('domain' => $webo_domain, 'theme_id' => $webo_id, 'user_id' => $customerId);
                if ($this->insert('khachdangkyweb', $data_web) !== false) {
                    return 'success';
                }
            } else {
                header("location:/tai-khoan/");
            }
        } else {
            return 'errorEmpty';
        }
        
    }

    /**
     * Replace unicode
     */

    public function replaceUnicode ($text) {
        $text= strtolower($text);
        $text = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
        $text = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
        $text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);
        $text = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $text);
        $text = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);
        $text = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);
        $text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
        $text = preg_replace("/(đ)/", 'd', $text);
        $text = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);
        $text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);
        $text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);
        $text = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);
        $text = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);
        $text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
        $text = preg_replace("/(Đ)/", 'D', $text);

        $pattern = '/[^a-zA-Z0-9]/u';
        
        $cleared = preg_replace($pattern, '', (string)$text);

        return $cleared;
    }

    /**
     * Format domain
     */
    public function formatDomain($domain) {
        return $this->replaceUnicode($domain);
    }

    /**
     * Validate domain
     */
    public function isDomain($domain) {
        $pattern = '/[a-zA-Z0-9]$/u';
        $is_domain = preg_match($pattern, $domain, $matches);
        return $is_domain;
    }

    public function writeFile($path, $content)
    {
        $fp = @fopen($path, "w");
        if (!$fp) {
            echo 'Error! Cannot open file';
            return false;
        } else {
            fwrite($fp, $content);
            fclose($fp);
            return true;
        }
    }


    public function iniAsStr(array $a)
    { 
        return array_reduce(array_keys($a), function($str, $sectionName) use ($a) { 
         $sub = $a[$sectionName]; 
         return $str . "[$sectionName]" . PHP_EOL . 
          array_reduce(array_keys($sub), function($str, $key) use($sub) { 
           return $str . $key . '=' . $sub[$key] . PHP_EOL; 
          }) . PHP_EOL; 
         }); 
    } 
}