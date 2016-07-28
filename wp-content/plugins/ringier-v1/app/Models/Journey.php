<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class Journey
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_journey_info;
    private $_tbl_journey_type_info;
    private $_tbl_journey_type_port;
    private $_tbl_journey_type_river;

    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_journey_info = $this->_prefix . 'journey_info';
        $this->_tbl_journey_type_info = $this->_prefix . 'journey_type_info';
        $this->_tbl_journey_type_port = $this->_prefix . 'journey_type_port';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Journey();
        }

        return self::$instance;
    }


    public function getJourneyList($params)
    {
        $cacheId = __CLASS__ . 'getJourneyList' . serialize($params);
        if (!empty($params['is_cache'])) {
            $result = wp_cache_get($cacheId);
        } else {
            $result = false;
        }
        if ($result == false) {
            $page = (empty($params['page'])) ? 1 : intval($params['page']);
            $limit = (empty($params['limit'])) ? 6 : intval($params['limit']);
            $to = ($page - 1) * $limit;
            $order_by = "  p.post_date DESC ";
            if ($params['order_by']) {
                $order_by = $params['order_by'];
            }

            $where = '';
            $join = '';

            $objPost = Posts::init();
            $join .= ' INNER JOIN '.$this->_tbl_journey_info .' as ji ON ji.object_id = p.ID
                       INNER JOIN '.$this->_tbl_journey_type_info .' as jti ON jti.object_id = ji.journey_type';


            if($params['_ship']){
                $ship = $objPost->getPostBySlug($params['_ship'],'ship');
                if($ship){
                    $where .= ' AND jti.ship = '.$ship->ID ;
                }
            }
            if($params['_destination']){
                $destination = $objPost->getPostBySlug($params['_destination'],'destination');
                if($destination){
                    $where .= ' AND jti.destination = '.$destination->ID ;
                }
            }
            if($params['_port']){
                $port = $objPost->getPostBySlug($params['_port'],'port');
                if($port){
                    $join .= ' INNER JOIN '.$this->_tbl_journey_type_port . ' as jtp ON jtp.journey_type_id = jti.object_id';
                    $where .= ' AND jtp.port_id = '.$port->ID ;
                }
            }

            if($params['_month']){
                $month = date_format(date_create_from_format("d/m/Y", '01/'.$params['_month']), "Y-m");
                $where .= " AND DATE_FORMAT(ji.departure,'%Y-%m') = '".$month."'";
            }

            $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type FROM " . $this->_wpdb->posts . " as p
            $join
            WHERE p.post_type = 'journey' AND p.post_status='publish'
            $where          
            ORDER BY $order_by  LIMIT $to, $limit
            ";

           // echo $query;

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

            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;

    }


    public function getInfo($object)
    {
        if (is_numeric($object)) {
            $cacheId = __CLASS__ . 'getInfo' . $object;
        } else {
            $cacheId = __CLASS__ . 'getInfo' . $object->ID;
        }

        $result = wp_cache_get($cacheId);
        if ($result == false) {
            if (is_numeric($object)) {
                $object = get_post($object);
            }

            $objImages = Images::init();
            $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured']);
            $object->permalink = get_permalink($object->ID);

            $query = 'SELECT * FROM ' . $this->_tbl_journey_info . ' WHERE object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);

            // Calculate duration
            $departure = date_create($object->departure);
            $arrive = date_create($object->arrive);
            $duration = date_diff($departure, $arrive);
            $object->nights = $duration->days;
            $object->days = $duration->days + 1;
            $object->duration = ($duration->days + 1) . " days " . $duration->days . " nights";

            if ($object->journey_type) {
                $journeyType = JourneyType::init();
                $journey_type_info = $journeyType->getInfo($object->journey_type);
                $object->journey_type_info = $journey_type_info;
            }

            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }

}
