<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 14-Aug-16
 * Time: 11:09 AM
 */

namespace RVN\Controllers;

use RVN\Models\Booking;

class AddonController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new AddonController();
        }

        return self::$instance;
    }

}