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

        if (empty($result)) {

            // Delete auto-draft
            $query_delete = "SELECT * FROM {$this->_wpdb->posts} WHERE post_type = 'tato' AND post_status = 'auto-draft' AND post_author = " . get_current_user_id();
            $delete_tatos = $this->_wpdb->get_results($query_delete);

            if (!empty($delete_tatos)) {
                foreach ($delete_tatos as $k => $v) {
                    if ($v->ID != $tato_id) {
                        $this->_wpdb->delete($this->_wpdb->posts, ['ID' => $v->ID]);
                        $this->_wpdb->delete(TBL_TATO, ['object_id' => $v->ID]);
                    }
                }
            }


            // Insert new draft tato
            $this->_wpdb->insert(TBL_TATO, ['object_id' => $tato_id]);
            $result = $this->getTaToByID($tato_id);

        }

        return $result;
    }
}
