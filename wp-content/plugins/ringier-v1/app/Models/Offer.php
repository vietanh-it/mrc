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


    /**
     * Lấy list offer theo kiểu mới
     *
     * @param array $params
     * @return array|null|object
     */
    public function getOfferList($params = [])
    {
        $page = (empty($params['page'])) ? 1 : intval($params['page']);
        $limit = (empty($params['limit'])) ? 6 : intval($params['limit']);
        $to = ($page - 1) * $limit;
        $order_by = " ORDER BY p.post_date DESC ";

        $query = "SELECT * FROM {$this->_wpdb->posts} p";
        $inner_join = " INNER JOIN " . TBL_OFFER_INFO . " oi ON p.ID = oi.object_id";
        $inner_join .= " INNER JOIN " . TBL_JOURNEY_INFO . " ji ON oi.journey_id = ji.object_id";
        $group_by = " GROUP BY oi.object_id HAVING p.post_status = 'publish' AND DATE_FORMAT(ji.departure, '%Y-%m-%d') >= '" . date('Y-m-d') . "'";

        $query .= $inner_join .= $group_by . $order_by . "LIMIT $to, $limit";
        $result = $this->_wpdb->get_results($query);

        $objImage = Images::init();
        if (!empty($result)) {
            $m_journey = Journey::init();
            $m_journey_type = JourneyType::init();

            foreach ($result as $k => $v) {

                // Nếu journey gắn theo offer empty hoặc ko publish => remove offer
                $journey = get_post($v->journey_id);
                if (empty($journey) || $journey->post_status != 'publish') {
                    unset($result[$k]);
                }

                $jt = $m_journey->getJourneyTypeByJourney($v->journey_id);
                $journey = $m_journey->getInfo($v->journey_id);
                $journey_type = $journey->journey_type_info;

                $v->images = $objImage->getPostImages($v->ID, ['small', 'widescreen']);
                $v->journey_info = $journey;
                $v->permalink = $journey_type->permalink . '#j=' . $v->journey_id;

                // Journey min price
                $v->journey_min_price = $m_journey->getJourneyMinPrice($v->journey_id, true);
            }
        }

        if (!empty($params['is_paging'])) {
            $this->_set_paging($result, sizeof($result), $params['limit'], $params['page']);
        }

        return $result;
    }


    private function _set_paging($data, $total, $limit, $page)
    {
        global $wp_query;
        $wp_query->posts = $data;
        $wp_query->is_paged = ($page >= 1) ? true : false;
        $wp_query->found_posts = $total;
        $wp_query->max_num_pages = ceil($total / $limit);
    }


    public function deleteOffer($object)
    {
        if (is_numeric($object)) {
            $object = get_post($object);
        }

        // Delete offer info
        $this->_wpdb->delete($this->_tbl_offer_info, ['object_id' => $object->ID]);

        return true;
    }


    public function getOfferDetail($object)
    {
        if (is_numeric($object)) {
            $object = get_post($object);
        }

        if (!empty($object)) {
            $object->permalink = get_permalink($object);
            $objImage = Images::init();
            $object->images = $objImage->getPostImages($object->ID, ['small', 'widescreen']);

            // Journey
            $m_journey = Journey::init();
            $object->journey = $m_journey->getJourneyByOffer($object->ID);

            // Get offers
            $query = "SELECT * FROM " . TBL_OFFER_INFO . " WHERE object_id = {$object->ID}";
            $object->offers = $this->_wpdb->get_results($query);


            return $object;
        }
        else {
            return false;
        }
    }


    /**
     * Get Offer Info New
     *
     * @param $object
     * @return array|null|object
     */
    public function getOfferDetailList($object)
    {
        if (is_numeric($object)) {
            $object = get_post($object);
        }

        $query = "SELECT * FROM {$this->_tbl_offer_info} WHERE object_id = {$object->ID}";
        $result = $this->_wpdb->get_results($query);

        return $result;

    }


    /**
     * WILL BE REMOVE
     *
     * @param $object
     * @return bool|mixed|object
     */
    public function getOfferInfo($object)
    {
        if (is_numeric($object)) {
            $cacheId = __CLASS__ . 'getOfferInfo' . $object;
        }
        else {
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
            $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured', 'small', 'full']);
            $object->permalink = '#';

            $query = 'SELECT * FROM ' . $this->_tbl_offer_info . ' WHERE object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);

            $query_2 = 'SELECT * FROM ' . $this->_tbl_offer_journey . ' WHERE offer_id = ' . $object->ID;
            $list_offer_room = $this->_wpdb->get_results($query_2);
            $object->list_offer_room = $list_offer_room;

            if (!empty($list_offer_room[0])) {
                $journey_type_id = $list_offer_room[0]->journey_type_id;
                $object->journey_type_id = $journey_type_id;

                $object->permalink = get_permalink($journey_type_id);
            }

            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }


    /**
     * Get offer info by journey id
     *
     * @param $j_id
     * @return array|bool|mixed|object
     */
    public function getOfferByJourney($j_id)
    {
        $query = "SELECT object_id FROM {$this->_tbl_offer_info} WHERE journey_id = {$j_id} LIMIT 1";
        $offer_id = $this->_wpdb->get_var($query);

        $offer_info = [];
        if (!empty($offer_id)) {
            $offer_info = $this->getOfferDetail($offer_id);
        }

        return $offer_info;
    }


    public function saveOffer($args)
    {
        if (empty($args['object_id'])) {
            return false;
        }
        else {
            $query = "SELECT * FROM {$this->_tbl_offer_info} WHERE object_id = {$args['object_id']} AND room_type_id = {$args['room_type_id']}";
            $offer_info = $this->_wpdb->get_row($query);

            if (empty($offer_info)) {
                $this->_wpdb->insert($this->_tbl_offer_info, $args);
            }
            else {
                $this->_wpdb->update($this->_tbl_offer_info, $args,
                    ['object_id' => $args['object_id'], 'room_type_id' => $args['room_type_id']]);
            }

            return true;
        }
    }

}
