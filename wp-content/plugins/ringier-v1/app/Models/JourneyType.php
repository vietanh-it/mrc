<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;
use WeDevs\ORM\WP\Post;

class JourneyType
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_ship_info;
    private $_tbl_room_types;
    private $_tbl_rooms;
    private $_tbl_journey_type_info;
    private $_tbl_journey_info;
    private $_tbl_offer_journey;
    private $_tbl_offer_info;
    private $_tbl_journey_type_port;

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
        $this->_tbl_journey_type_info = $this->_prefix . 'journey_type_info';
        $this->_tbl_journey_info = $this->_prefix . 'journey_info';
        $this->_tbl_offer_journey = $this->_prefix . 'offer_journey';
        $this->_tbl_offer_info = $this->_prefix . 'offer_info';
        $this->_tbl_journey_type_port = $this->_prefix . 'journey_type_port';

    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new JourneyType();
        }

        return self::$instance;
    }

    public function getJourneyTypeList($params)
    {
        $cacheId = __CLASS__ . 'getJourneyTypeList' . serialize($params);
        if (!empty($params['is_cache'])) {
            $result = wp_cache_get($cacheId);
        } else {
            $result = false;
        }
        if ($result == false) {
            $page = (empty($params['page'])) ? 1 : intval($params['page']);
            $limit = (empty($params['limit'])) ? 10 : intval($params['limit']);
            $to = ($page - 1) * $limit;
            $order_by = "  p.post_date DESC ";
            if (!empty($params['order_by'])) {
                $order_by = $params['order_by'];
            }

            $where = '';
            $join = '';

            $join .= ' INNER JOIN ' . $this->_tbl_journey_type_info . ' as jti ON jti.object_id = p.ID';
            $objPost = Posts::init();
            if (!empty($params['_ship'])) {
                $ship = $objPost->getPostBySlug($params['_ship'], 'ship');
                if ($ship) {
                    $where .= ' AND jti.ship = ' . $ship->ID;
                }
            }
            if (!empty($params['_destination'])) {
                $destination = $objPost->getPostBySlug($params['_destination'], 'destination');
                if ($destination) {
                    $where .= ' AND jti.destination = ' . $destination->ID;
                }
            }
            if (!empty($params['_port'])) {
                $port = $objPost->getPostBySlug($params['_port'], 'port');
                if ($port) {
                    $join .= ' INNER JOIN ' . $this->_tbl_journey_type_port . ' as jtp ON jtp.journey_type_id = p.ID';
                    $where .= ' AND jtp.port_id = ' . $port->ID;
                }
            }


            $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p
            $join
            WHERE p.post_type = 'journey_type' AND p.post_status='publish'
            $where          
            ORDER BY $order_by  LIMIT $to, $limit
            ";

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

            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }

    public function getInfo($object,$type = '')
    {
        if (is_numeric($object)) {
            $cacheId = __CLASS__ . 'getInfo' . $object;
        } else {
            $cacheId = __CLASS__ . 'getInfo' . $object->ID;
        }

        $result = wp_cache_get($cacheId);
        if ($result == false) {
            // Post
            if (is_numeric($object)) {
                $object = get_post($object);
            }

            // images, permalink
            $objImages = Images::init();
            $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured', 'full','small']);
            $object->permalink = get_permalink($object->ID);

            // tbl journey_type_info
            $query = 'SELECT jti.* FROM ' . $this->_tbl_journey_type_info . ' as jti 
            WHERE jti.object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);

            if(!empty($object->map_image)){
                $img = wp_get_attachment_image_src($object->map_image,'small');
                if($img){
                    $object->map_image = $img[0];
                }
            }

            // days, nights, duration
            $object->days = intval($object->nights) + 1;
            $object->duration = $object->days . " days " . $object->nights . " nights";

            // ship_info
            if ($object->ship) {
                $ship = Ships::init();
                $ship_detail = $ship->getShipDetail($object->ship);

                $object->ship_info = $ship_detail;
            }

            $objPost = Posts::init();
            if($object->destination){
                $destination = $objPost->getInfo($object->destination);
                $object->destination_info = $destination;
            }

            $objGallery = Gallery::init();
            $gallery = $objGallery->getGalleryBy($object->ID);
            $object->gallery = $gallery;

            $object->offer_main_info = false;
            $object->offer = false;
            if ($type != 'offer') {
                $objOffer = Offer::init();
                $offer = $objOffer->getOfferByJourneyType($object->ID);
                if(!empty($offer)){
                    $object->offer = $offer;
                    $object->offer_main_info = $object->offer[0]->offer_info;
                    $object->offer_main_info = $object->offer[0]->offer_info;
                    if(!empty($object->offer_main_info->start_date)){
                        $object->offer_main_info->month_year = date('M Y', strtotime($object->offer_main_info->start_date));
                    }
                }
            }


            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }

    public function saveJourneyTypeInfo($id,$data){

        $select = 'SELECT * FROM '.$this->_tbl_journey_type_info .' WHERE object_id  = '.$id;
        $jt_info = $this->_wpdb->get_row($select);
        if(($jt_info)){
            $this->_wpdb->update($this->_tbl_journey_type_info,$data,array('object_id' => $id));
        }else{
            $this->_wpdb->insert($this->_tbl_journey_type_info,$data);

        }
    }
}
