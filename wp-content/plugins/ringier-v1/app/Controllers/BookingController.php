<?php
namespace RVN\Controllers;

class BookingController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

        add_action("wp_ajax_ajax_handler_booking", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_booking", [$this, "ajaxHandler"]);
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new BookingController();
        }

        return self::$instance;
    }


    public function ajaxAddToCart($data)
    {
        $model = BookingController::init();
    }

}
