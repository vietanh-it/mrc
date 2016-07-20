<?php
namespace RVN\Controllers\Home;

use RVN\Controllers\_BaseController;

class HomeController extends _BaseController
{
    private static $instance;

    protected function __construct()
    {
        parent::__construct();
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new HomeController();
        }
        return self::$instance;
    }

    public function index()
    {
        if (!is_user_logged_in()) {
            wp_redirect(wp_login_url());
        }

        if (!user_can(wp_get_current_user(), 'administrator')) {
            die('Website is under construction.');
        }

        return view('home/home');
    }
}

?>