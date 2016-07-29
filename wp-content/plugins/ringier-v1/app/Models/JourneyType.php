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


            $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p";
            if (!empty($join)) {
                $query .= "INNER JOIN {$join}";
            }
            $query .= " WHERE p.post_type = 'journey_type' AND p.post_status='publish'";
            if (!empty($where)) {
                $query .= " AND {$where}";
            }
            $query .= " ORDER BY $order_by  LIMIT $to, $limit";

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
            // Post
            if (is_numeric($object)) {
                $object = get_post($object);
            }

            // images, permalink
            $objImages = Images::init();
            $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured', 'full']);
            $object->permalink = get_permalink($object->ID);

            // tbl journey_type_info
            $query = 'SELECT jti.* FROM ' . $this->_tbl_journey_type_info . ' as jti 
            WHERE jti.object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);

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

            $objOffer = Offer::init();
            $offer = $objOffer->getOfferByJourneyType($object->ID);
            $object->offer = $offer;

            $objGallery = Gallery::init();
            $gallery = $objGallery->getGalleryBy($object->ID);
            $object->gallery = $gallery;

            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }
}
