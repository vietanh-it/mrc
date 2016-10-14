<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class Ports
{
    private static $instance;

    private $_wpdb;
    private $_prefix;


    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Ports();
        }

        return self::$instance;
    }


    public function getPortHaveJourney()
    {
        $query = "SELECT * FROM " . TBL_JOURNEY_TYPE_PORT . " GROUP BY port_id";
        $ports = $this->_wpdb->get_results($query);

        $result = [];
        if (!empty($ports)) {
            foreach ($ports as $k => $v) {
                $info = get_post($v->port_id);
                $info->permalink = get_permalink($info);
                $result[$v->port_id] = $info;
            }
        }

        return $result;
    }
}
