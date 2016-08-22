<?php
namespace RVN\Controllers;

use RVN\Models\Posts;
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

    public function getSipList()
    {
        $post = Posts::init();
        $result = $post->getList([
            'post_type' => 'ship',
        ]);

        return $result;
    }

    public function listShip(){
        $post = Posts::init();
        $page = get_query_var("paged");
        if(empty($page)) $page =1;

        $list_ship = $post->getList([
            'post_type' => 'ship',
            'posts_per_page' => '6',
            'is_paging' => 1,
            'paged' => $page,
        ]);

        view('ship/list', compact('params', 'list_ship'));
    }

    public function detailShip($id){
        $objShip = Ships::init();
        $ship_detail = $objShip->getShipDetail($id);

        view('ship/detail', compact('ship_detail'));
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


    public function ajaxSaveRoomInfo($data)
    {
        $model = Ships::init();

        $result = false;
        if (!empty($data['room_id'])) {
            $rs = $model->saveRoomInfo($data);

            if (!empty($rs)) {
                $result = [
                    'status' => 'success',
                    'data'   => $rs
                ];
            }
        } else {
            $result = [
                'status' => 'fail',
                'data'   => ['Please choose room to edit info']
            ];
        }

        return $result;
    }


    public function ajaxGetShipDetail($data)
    {
        $model = Ships::init();
        $result = [
            'status' => 'success',
            'data'   => $model->getShipDetail($data['ship_id'])
        ];

        return $result;
    }


    public function ajaxGetRoomTypePricing($data)
    {
        $this->validate->rule('required', ['room_type_id'])
            ->message('There is an error occurred, please try again.');

        if ($this->validate->validate()) {
            $model = Ships::init();
            $result = [
                'status' => 'success',
                'data'   => $model->getRoomTypePricing($data['room_type_id'])
            ];
        } else {
            $result = [
                'status' => 'fail',
                'data'   => $this->validate->errors()
            ];
        }

        return $result;
    }


    public function ajaxSaveRoomTypePricing($data)
    {
        $this->validate->rule('required',
            ['room_type_id', 'twin_high_price', 'twin_low_price', 'single_high_price', 'single_low_price'])
            ->message('There is an error occurred, please try again.');

        if ($this->validate->validate()) {
            $model = Ships::init();
            $result = [
                'status' => 'success',
                'data'   => $model->saveRoomTypePricing($data)
            ];
        } else {
            $result = [
                'status' => 'fail',
                'data'   => $this->validate->errors()
            ];
        }

        return $result;
    }


    public function getShipDetail($ship_id)
    {
        $model = Ships::init();
        $result = $model->getShipDetail($ship_id);

        return $result;
    }


    public function getShipRoomTypes($ship_id)
    {
        $model = Ships::init();
        $result = $model->getShipRoomTypes($ship_id);

        return $result;
    }


    public function getShipRooms($ship_id, $booked_rooms = [], $journey_id = 0)
    {
        $model = Ships::init();
        $result = $model->getShipRooms($ship_id, $booked_rooms, $journey_id);

        return $result;
    }
}
