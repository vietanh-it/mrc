<?php
namespace RVN\Controllers;

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

    
}
