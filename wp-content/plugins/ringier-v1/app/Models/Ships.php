<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

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
        }

        return $result;
    }


    public function getShipRoomTypes($ship_id)
    {
        $query = "SELECT * FROM {$this->_tbl_room_types} WHERE ship_id = {$ship_id}";
        $result = $this->_wpdb->get_results($query);

        return $result;
    }


    public function getShipRooms($ship_id)
    {
        $query = "SELECT rt.ship_id, rt.room_type_name, rt.background, r.* FROM {$this->_tbl_rooms} r INNER JOIN {$this->_tbl_room_types} rt ON r.room_type_id = rt.id WHERE rt.ship_id = {$ship_id}";
        $result = $this->_wpdb->get_results($query);

        if (!empty($result)) {
            foreach ($result as $key => $item) {
                $item->html = "<div data-roomid='{$item->id}' style='position: absolute; top: {$item->top}; left: {$item->left}; width: {$item->width}; height: {$item->height}; background: {$item->background}; cursor: pointer;'><b>{$item->room_name}</b></div>";
            }
        }

        return $result;
    }


    public function getRoomInfo($room_id)
    {
        $query = "SELECT rt.ship_id, rt.room_type_name, rt.background, r.* FROM {$this->_tbl_rooms} r INNER JOIN {$this->_tbl_room_types} rt ON r.room_type_id = rt.id WHERE r.id = {$room_id}";
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
}
