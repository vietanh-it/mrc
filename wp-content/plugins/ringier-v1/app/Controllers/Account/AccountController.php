<?php
namespace RVN\Controllers\Account;

use RVN\Controllers\_BaseController;
use RVN\Models\Location;
use RVN\Models\Users;

class AccountController extends _BaseController
{
    private static $instance;

    protected function __construct()
    {
        parent::__construct();

        add_action("wp_ajax_ajax_handler_account", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_account", [$this, "ajaxHandler"]);
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new AccountController();
        }
        return self::$instance;
    }

    public function loginForm($template,$theme_my_login)
    {

        return view('account/login',compact('template','theme_my_login'));
    }


    public function register($template,$theme_my_login)
    {
        $Location = Location::init();
        $country_list = $Location->getCountryList();

        return view('account/register',compact('template','theme_my_login','country_list'));
    }

    public function resetpass($template)
    {

        return view('account/resetpass',compact('template'));
    }


    public function lostpassword($template,$theme_my_login)
    {

        return view('account/lost-password',compact('template','theme_my_login'));
    }

    public function profile($template,$profileuser)
    {

        return view('account/profile',compact('template','profileuser'));
    }

    public function ajaxConnectEmail($data){

        if(!empty($data['c_email'])) {
            if(is_email($data['c_email'])){

                $name = !empty($data['c_name']) ? $data['c_name'] : $data['c_email'];
                $args_sub= array(
                    'user_email' => $data['c_email'],
                    'display_name' => $name,
                );

                subscribeSendy($args_sub);

                // Add user to newsletter list
                global $wpdb;
                if (empty($wpdb->get_row("SELECT * FROM {$wpdb->prefix}newsletter WHERE email = '{$data['c_email']}'"))) {
                    $wpdb->insert($wpdb->prefix . 'newsletter', [
                        'name'       => $name,
                        'email'      => $data['c_email'],
                        'created_at' => current_time('mysql')
                    ]);
                }

                $hash_email = mrcEncrypt($data['c_email']);
                $args_mail = [
                    'first_name'      => $name,
                    'url_web'         => WP_SITEURL,
                    'url_unsubscribe' => WP_SITEURL . '/unsubscribe?p=' . $hash_email,
                ];
                sendEmailHTML($data['c_email'],'Thank you for your newsletter sign-up
','normal_user/newsletter_sign_up.html',$args_mail);

                $result = array(
                    'status' => 'success',
                    'message' => 'Connect email success.',
                );
            }else{
                $result = array(
                    'status' => 'error',
                    'message' => array('Invalid email.'),
                );
            }
        }else{
            $result = array(
                'status' => 'error',
                'message' => array('Please enter your email.'),
            );
        }

        return $result;
    }

    public function ajaxReferFriend($data){
        if(is_user_logged_in()){
            if($data['email_friend']){
                if(is_email($data['email_friend'])){
                    if(email_exists($data['email_friend'])){
                        $result = array(
                            'status' => 'error',
                            'message' => array('This email is already a member.'),
                        );
                    }else{
                        $list_email_refer = get_user_meta(get_current_user_id(),'email_refer');
                        if(in_array($data['email_friend'],$list_email_refer)){
                            $result = array(
                                'status' => 'error',
                                'message' => array('You invited this email already..'),
                            );
                        }else{
                            $email = $data['email_friend'];
                            $code = md5(time()).'_'.get_current_user_id();

                            $url = wp_registration_url().'/?email='.$email.'&code='.$code.'&id='.get_current_user_id();

                            $args_mail = array(
                                'first_name' => $data['email_friend'],
                                'url_register' => $url,
                            );
                            sendEmailHTML($data['email_friend'],'Register invitation
','normal_user/refer_friend.html',$args_mail);

                            add_user_meta(get_current_user_id(),'email_refer',$data['email_friend']);
                            add_user_meta(get_current_user_id(),'code_refer',$code);

                            $result = array(
                                'status' => 'success',
                                'message' => 'Refer friend success.',
                                'url' => $url,
                            );
                        }
                    }

                }else{
                    $result = array(
                        'status' => 'error',
                        'message' => array('Email not email.'),
                    );
                }
            }else{
                $result = array(
                    'status' => 'error',
                    'message' => array('Please enter your email.'),
                );
            }
        }else{
            $result = array(
                'status' => 'error',
                'message' => array('Please login or register.'),
            );
        }


        return $result;

    }

    public function userInfo($user_id){
        $objUser = Users::init();
        $return = array();
        $data = array();

        if(!empty($_POST) && is_user_logged_in()){
            $data['user_id'] = get_current_user_id();
            $data['update_at'] = current_time('mysql');
            if(isset($_POST['first_name']) && isset($_POST['last_name'])){
                if(empty($_POST['first_name']) && empty($_POST['last_name'])){
                    $return['status'] = 'error';
                    $return['message'][] = 'Please enter first name or last name.';
                }else{
                    $display_name = $_POST['first_name'] . ' '. $_POST['last_name'];
                    wp_update_user(array(
                        "ID" => $data['user_id'],
                        "first_name" => $_POST['first_name'],
                        "last_name" => $_POST['last_name'],
                        "display_name" => $display_name,
                    ));
                }
            }
            if(isset($_POST['birthday'])){
                $data['birthday'] = date('Y-m-d',strtotime($_POST['birthday']));
            }
            if(isset($_POST['country'])){
                $data['country'] = $_POST['country'];
            }
            if(isset($_POST['address'])){
                $data['address'] = $_POST['address'];
            }
            if(isset($_POST['gender'])){
                $data['gender'] = $_POST['gender'];
            }

            if(isset($_POST['passport_id'])){
                $data['passport_id'] = $_POST['passport_id'];
            }
            if(isset($_POST['valid_until'])){
                $data['valid_until'] =  date('Y-m-d',strtotime($_POST['valid_until'])); ;
            }
            if(isset($_POST['date_of_issue'])){
                $data['date_of_issue'] =   date('Y-m-d',strtotime($_POST['date_of_issue']));;
            }
            if(isset($_POST['nationality'])){
                $data['nationality'] = $_POST['nationality'];
            }

            if(isset($_POST['a_email']) && isset($_POST['a_password'])){
                $ags["ID"] = $data['user_id'];
                if(empty($_POST['a_email'])){
                    $return['status'] = 'error';
                    $return['message'][] = 'Please enter your email.';
                }else{
                    if(!is_email($_POST['a_email'])){
                        $return['status'] = 'error';
                        $return['message'][] = 'Email not match.';
                    }else{
                        $user_current = wp_get_current_user();

                        if($_POST['a_email'] != $user_current->user_email && email_exists($_POST['a_email'])){
                            $return['status'] = 'error';
                            $return['message'][] = 'This email already exists.';
                        }else{
                            $ags["user_email" ] = $_POST['a_email'];
                            $ags["user_login" ] = $_POST['a_email'];
                            $ags["user_nicename" ] = sanitize_title($_POST['a_email']);
                        }
                    }
                }

                if(empty($_POST['a_password'])){
                    $return['status'] = 'error';
                    $return['message'][] = 'Please enter your password.';
                }else{
                    $ags['user_pass'] = $_POST['a_password'];
                }

                $update_ac = wp_update_user($ags);
                if(!$update_ac){
                    $return['status'] = 'error';
                    $return['message'][] = 'An error, please try again.';
                }
            }

            if($return['status'] != 'error' ){
                $update = $objUser->saveUserInfo($data);
                if($update){
                    $return['status'] = 'success';
                    $return['message'] = 'Update profile success.';
                }else{
                    $return['status'] = 'error';
                    $return['message'][] = 'An error, please try again.';
                }
            }

        }

        $Location = Location::init();
        $country_list = $Location->getCountryList();
        $user_info = $objUser->getUserInfo($user_id);

        return view('account/profile',compact('user_info','country_list','return'));
    }

}

?>