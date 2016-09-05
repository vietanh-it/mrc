<?php
namespace RVN\Controllers\Account;

use RVN\Controllers\_BaseController;

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

        return view('account/register',compact('template','theme_my_login'));
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

        if($data['c_email']){
            if(is_email($data['c_email'])){

                $args= array(
                    'email' => $data['c_email'],
                    'name' => !empty($data['c_name']) ? $data['c_name'] : $data['c_email'],
                );
                //TODO :  add to sendy

                //

                $result = array(
                    'status' => 'success',
                    'message' => 'Connect email success.',
                );
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

        return $result;
    }

    public function ajaxReferFriend($data){
        if(is_user_logged_in()){
            if($data['email_friend']){
                if(is_email($data['email_friend'])){
                    $email = $data['email_friend'];
                    $code = md5(time()).'_'.get_current_user_id();


                    $result = array(
                        'status' => 'success',
                        'message' => 'Connect email success.',
                    );
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
    
    
}

?>