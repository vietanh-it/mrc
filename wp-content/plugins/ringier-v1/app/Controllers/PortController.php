<?php
namespace RVN\Controllers;

class PortController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PortController();
        }

        return self::$instance;
    }

}
