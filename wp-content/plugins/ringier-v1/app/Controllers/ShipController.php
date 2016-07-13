<?php
namespace RVN\Controllers;

class ShipController extends _BaseController
{
    private static $instance;

    protected function __construct()
    {
        parent::__construct();
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShipController();
        }

        return self::$instance;
    }

    public function getShipDetail($ship_id)
    {

    }
}
