<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class Addon
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_journey_type_info;
    private $_tbl_tour_info;
    private $_tbl_tour_journey_type;

    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_tour_info = $this->_prefix . 'tour_info';
        $this->_tbl_tour_journey_type = $this->_prefix . 'tour_journey_type';
        $this->_tbl_journey_type_info = $this->_prefix . 'journey_type_info';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Addon();
        }

        return self::$instance;
    }


    public function getList($params)
    {
        $page = (empty($params['page'])) ? 1 : intval($params['page']);
        $limit = (empty($params['limit'])) ? 6 : intval($params['limit']);
        $to = ($page - 1) * $limit;
        $order_by = "  p.post_date DESC ";
        if (!empty($params['order_by'])) {
            $order_by = $params['order_by'];
        }

        $where = ' ';
        $join = '';


        if (!empty($params['journey_type_id'])) {
            $join .= ' INNER JOIN ' . $this->_tbl_tour_journey_type . ' as ji ON ji.tour_id = p.ID';
            $where .= ' AND ji.journey_type_id = ' . intval($params['journey_type_id']);
        }

        if (empty($params['post_type'])) {
            $where .= ' AND p.post_type IN ("addon","tour")';
        } else {
            $where .= ' AND p.post_type = ' . $params['post_type'];
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p
            $join
            WHERE p.post_status='publish'
            $where          
            ORDER BY $order_by  LIMIT $to, $limit";

        //echo $query;

        $list = $this->_wpdb->get_results($query);
        $total = $this->_wpdb->get_var("SELECT FOUND_ROWS() as total");
        if ($list) {
            foreach ($list as $key => &$value) {
                $value = $this->getInfo($value);
            }
        }

        $result = [
            'data'  => $list,
            'total' => $total,
        ];

        return $result;

    }


    public function getInfo($object, $type = '')
    {
        // Post
        if (is_numeric($object)) {
            $object = get_post($object);
        }

        // images, permalink
        $objImages = Images::init();
        $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured', 'small', 'full']);
        $object->permalink = get_permalink($object->ID);

        // journey_info
        $query = 'SELECT * FROM ' . $this->_tbl_tour_info . ' WHERE object_id = ' . $object->ID;
        $post_info = $this->_wpdb->get_row($query);
        $object = (object)array_merge((array)$object, (array)$post_info);

        $result = $object;

        return $result;
    }
}
