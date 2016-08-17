<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class Ships
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_ship_info;
    private $_tbl_room_types;
    private $_tbl_rooms;


    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_ship_info = $this->_prefix . 'ship_info';
        $this->_tbl_room_types = $this->_prefix . 'room_types';
        $this->_tbl_rooms = $this->_prefix . 'rooms';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Ships();
        }

        return self::$instance;
    }


    public function getShipDetail($ship_id)
    {
        $query = "SELECT * FROM {$this->_wpdb->posts} p INNER JOIN {$this->_tbl_ship_info} si WHERE p.ID = {$ship_id}";
        $result = $this->_wpdb->get_row($query);

        if (!empty($result)) {
            $result->map = VIEW_URL . '/images/ship_maps/' . $result->map;
            $result->rooms = $this->getShipRooms($ship_id);
            $result->room_types = $this->getShipRoomTypes($ship_id);
            $result->permalink = get_permalink($result->ID);
            $objImages = Images::init();
            $result->images = $objImages->getPostImages($result->ID, ['thumbnail', 'featured', 'full','small']);

            $objGallery = Gallery::init();
            $gallery = $objGallery->getGalleryBy($result->ID);
            $result->gallery = $gallery;
        }

        return $result;
    }


    public function getShipRoomTypes($ship_id)
    {
        $query = "SELECT * FROM {$this->_tbl_room_types} WHERE ship_id = {$ship_id}";
        $result = $this->_wpdb->get_results($query);

        return $result;
    }


    public function getShipRooms($ship_id, $booked_rooms = [], $journey_id = 0)
    {
        $query = "SELECT rt.id as room_type_id, rt.ship_id, rt.room_type_name, rt.deck_plan, r.* FROM {$this->_tbl_rooms} r INNER JOIN {$this->_tbl_room_types} rt ON r.room_type_id = rt.id WHERE rt.ship_id = {$ship_id}";
        $result = $this->_wpdb->get_results($query);

        if (!empty($result)) {
            foreach ($result as $key => $item) {
                if (empty($booked_rooms)) {
                    // Unbooked rooms
                    $item->html = "<div data-roomid='{$item->id}' data-roomtypeid='{$item->room_type_id}' data-type='none' style='overflow: hidden; position: absolute; top: {$item->top}; left: {$item->left}; width: {$item->width}; height: {$item->height}; cursor: pointer;'><img src='" . VIEW_URL . "/images/rooms/" . $ship_id . "/" . $item->room_name . ".png'/></div>";
                } elseif (!in_array($item->id, $booked_rooms)) {
                    // Unbooked rooms
                    $item->html = "<div data-roomid='{$item->id}' data-roomtypeid='{$item->room_type_id}' data-type='none' style='overflow: hidden; position: absolute; top: {$item->top}; left: {$item->left}; width: {$item->width}; height: {$item->height}; cursor: pointer;'><img src='" . VIEW_URL . "/images/rooms/" . $ship_id . "/" . $item->room_name . ".png'/></div>";
                } else {
                    // Booked rooms
                    $item->html = "<div style='overflow: hidden; position: absolute; top: {$item->top}; left: {$item->left}; width: {$item->width}; height: {$item->height}; cursor: no-drop;'>" .
                        "<img style='position: absolute; width: auto; height: auto; top: 50%; left: 50%; margin-top: -17px; margin-left: -11px;' src='" . VIEW_URL . "/images/icon-booking-locked.png'/>" .
                        "<img src='" . VIEW_URL . "/images/rooms/" . $ship_id . "/" . $item->room_name . ".png'/>" .
                        "</div>";
                }
            }
        }

        return $result;
    }


    public function getRoomInfo($room_id)
    {
        $query = "SELECT rt.ship_id, rt.room_type_name, rt.deck_plan, r.* FROM {$this->_tbl_rooms} r INNER JOIN {$this->_tbl_room_types} rt ON r.room_type_id = rt.id WHERE r.id = {$room_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function saveRoomInfo($data)
    {
        $this->_wpdb->update($this->_tbl_rooms, [
            'room_name'    => $data['room_name'],
            'room_type_id' => $data['room_type_id']
        ], ['id' => $data['room_id']]);

        $result = $this->getRoomInfo($data['room_id']);

        return $result;
    }


    public function saveRoomTypePricing($data)
    {
        $info = [
            'twin_high_season_price'   => $data['twin_high_price'],
            'twin_low_season_price'    => $data['twin_low_price'],
            'single_high_season_price' => $data['single_high_price'],
            'single_low_season_price'  => $data['single_low_price']
        ];
        $rs = $this->_wpdb->update($this->_tbl_room_types, $info, ['id' => $data['room_type_id']]);

        return $rs;
    }


    public function getRoomTypePricing($room_type_id)
    {
        $query = "SELECT * FROM {$this->_tbl_room_types} WHERE id = {$room_type_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }
}
