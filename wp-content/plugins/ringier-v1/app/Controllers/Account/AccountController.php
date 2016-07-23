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

    public function loginForm()
    {

        return view('account/login');
    }


    public function register()
    {

        return view('account/register');
    }

    public function resetpass()
    {

        return view('account/resetpass');
    }


    public function lostpassword()
    {

        return view('account/lost-password');
    }

    public function profile()
    {

        return view('account/profile');
    }
}

?>