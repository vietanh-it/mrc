<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class TaTo
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
            self::$instance = new TaTo();
        }

        return self::$instance;
    }


    public function getTaToByID($tato_id)
    {

        $query = "SELECT * FROM {$this->_wpdb->posts} p INNER JOIN " . TBL_TATO . " tt ON p.ID = tt.object_id WHERE p.ID = {$tato_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }
}
