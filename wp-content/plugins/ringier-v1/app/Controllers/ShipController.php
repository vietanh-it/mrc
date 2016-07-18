<?php
namespace RVN\Controllers;

use RVN\Models\Ships;

class ShipController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

        add_action("wp_ajax_ajax_handler_ship", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_ship", [$this, "ajaxHandler"]);
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShipController();
        }

        return self::$instance;
    }


    public function ajaxGetRoomInfo($data)
    {
        $model = Ships::init();

        $result = false;
        if (!empty($data['room_id'])) {
            $rs = $model->getRoomInfo($data['room_id']);

            if (!empty($rs)) {
                $result = [
                    'status' => 'success',
                    'data'   => $rs
                ];
            }
        }

        return $result;
    }


    public function getShipDetail($ship_id)
    {
        $model = Ships::init();
        $result = $model->getShipDetail($ship_id);

        return $result;
    }


    public function getShipRooms($ship_id)
    {
        $model = Ships::init();
        $result = $model->getShipRooms($ship_id);

        return $result;
    }
}
