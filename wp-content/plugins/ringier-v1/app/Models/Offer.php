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
            $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured']);
            $object->permalink = get_permalink($object->ID);

            $query = 'SELECT * FROM ' . $this->_tbl_offer_info . ' WHERE object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);

            $query_2 = 'SELECT * FROM ' . $this->_tbl_offer_journey . ' WHERE offer_id = ' . $object->ID;
            $list_offer_room =$this->_wpdb->get_results($query_2);
            $object->list_offer_room = $list_offer_room;

            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }

    public function insertOfferRoomType($offer_id,$journey_type_id,$room_type_id){
        return $this->_wpdb->insert($this->_tbl_offer_journey,array(
            'offer_id' => $offer_id,
            'journey_type_id' => $journey_type_id,
            'room_type_id' => $room_type_id,
        ));
    }

    public function deleteOfferRoomType($offer_id){
        return $this->_wpdb->delete($this->_tbl_offer_journey,array(
            'offer_id' => $offer_id,
        ));
    }

}
