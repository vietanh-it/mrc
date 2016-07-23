<?php
namespace RVN\Controllers\Account;

use RVN\Controllers\_BaseController;

class AccountController extends _BaseController
{
    private static $instance;

    protected function __construct()
    {
        parent::__construct();
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
}

?>