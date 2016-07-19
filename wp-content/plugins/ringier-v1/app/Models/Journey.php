<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

class Journey
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_journey_info;


    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_journey_info = $this->_prefix . 'journey_info';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Journey();
        }

        return self::$instance;
    }


    public function getList($params){

    }

}
