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
    protected $_wpdb;
    protected $_table_info;

    private static $instance;

    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_tbl_ship_info = $wpdb->prefix . "ship_info";
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Ships();
        }

        return self::$instance;
    }
}
