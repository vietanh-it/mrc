<?php
namespace RVN\Controllers;

use RVN\Models\Offer;
use RVN\Models\Posts;
use RVN\Models\Ships;

class PageController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PageController();
        }

        return self::$instance;
    }

    public function defaultPage(){

        view('page/default');
    }
}
