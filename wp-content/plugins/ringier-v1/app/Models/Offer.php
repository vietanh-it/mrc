<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class Offer
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_journey_info;
    private $_tbl_journey_type_info;
    private $_tbl_journey_type_port;
    private $_tbl_journey_type_river;
    private $_tbl_offer_journey;
    private $_tbl_offer_info;

    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_journey_info = $this->_prefix . 'journey_info';
        $this->_tbl_journey_type_info = $this->_prefix . 'journey_type_info';
        $this->_tbl_journey_type_port = $this->_prefix . 'journey_type_port';
        $this->_tbl_offer_journey = $this->_prefix . 'offer_journey';
        $this->_tbl_offer_info = $this->_prefix . 'offer_info';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Offer();
        }

        return self::$instance;
    }

    public function getListOffer($params){
        $cacheId = __CLASS__ . 'getListOffer' . serialize($params);
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
            if (!empty($params['order_by'])) {
                $order_by = $params['order_by'];
            }

            $where = '';
            $join = '';

            $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p
            $join
            WHERE p.post_type = 'offer' AND p.post_status='publish'
            $where          
            ORDER BY $order_by  LIMIT $to, $limit
            ";

            // echo $query;
            $list = $this->_wpdb->get_results($query);
            $total = $this->_wpdb->get_var("SELECT FOUND_ROWS() as total");
            if ($list) {
                foreach ($list as $key => &$value) {
                    $value = $this->getOfferInfo($value);
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


    public function getOfferInfo($object){
        if (is_numeric($object)) {
            $cacheId = __CLASS__ . 'getOfferInfo' . $object;
        } else {
            $cacheId = __CLASS__ . 'getOfferInfo' . $object->ID;
        }

        $result = wp_cache_get($cacheId);
        if ($result == false) {

            // Post
            if (is_numeric($object)) {
                $object = get_post($object);
            }

            // images, permalink
            $objImages = Images::init();
            $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured','small']);
            $object->permalink = get_permalink($object->ID);

            $query = 'SELECT * FROM ' . $this->_tbl_offer_info . ' WHERE object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);

            $query_2 = 'SELECT * FROM ' . $this->_tbl_offer_journey . ' WHERE offer_id = ' . $object->ID;
            $list_offer_room =$this->_wpdb->get_results($query_2);
            $object->list_offer_room = $list_offer_room;

            if(!empty($list_offer_room[0])){
                $journey_id = $list_offer_room[0]->journey_id;
                $Journey = Journey::init();
                $journey_info = $Journey->getInfo($journey_id,'offer');

                $object->journey_info = $journey_info;
                if($object->journey_info->min_price){
                    $object->journey_info->min_price = intval($object->journey_info->min_price) -  intval($object->journey_info->min_price) * $object->promotion / 100;
                }
            }

            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }

    public function insertOfferRoomType($offer_id,$journey_id,$room_type_id){
        return $this->_wpdb->insert($this->_tbl_offer_journey,array(
            'offer_id' => $offer_id,
            'journey_id' => $journey_id,
            'room_type_id' => $room_type_id,
        ));
    }

    public function deleteOfferRoomType($offer_id){
        return $this->_wpdb->delete($this->_tbl_offer_journey,array(
            'offer_id' => $offer_id,
        ));
    }

    public function getOfferByJourney($jt_id){
        $rs = array();
        $query = ' SELECT * FROM '.$this->_tbl_offer_journey . ' WHERE journey_id = '.$jt_id;
        $list_jt_have_off = $this->_wpdb->get_results($query);

        if($list_jt_have_off){
            foreach ($list_jt_have_off as $v){
                $offer_info =$this->getOfferInfo($v->offer_id);
                $v->offer_info = $offer_info;
                unset($v->journey_id);
                $rs[] = $v;
            }
        }

        return $rs;
    }

}
