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
        return view('home/home');
    }
}

?>