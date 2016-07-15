<?php
namespace RVN\Controllers;

class ShipController extends _BaseController
{
    private static $instance;
    private        $_wpdb;
    private        $_prefix;
    private        $_tbl_ship_info;
    private        $_tbl_room_types;
    private        $_tbl_rooms;

    protected function __construct()
    {
        parent::__construct();
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
            self::$instance = new ShipController();
        }

        return self::$instance;
    }

    public function getShipDetail($ship_id)
    {
        $query = "SELECT * FROM {$this->_wpdb->posts} p INNER JOIN {$this->_tbl_ship_info} si WHERE p.ID = {$ship_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }
}
