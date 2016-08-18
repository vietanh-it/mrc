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
            if (!empty($params['order_by'])) {
                $order_by = $params['order_by'];
            }

            $where = '';
            $join = '';

            $objPost = Posts::init();
            $join .= ' INNER JOIN ' . $this->_tbl_journey_info . ' as ji ON ji.object_id = p.ID
                       INNER JOIN ' . $this->_tbl_journey_type_info . ' as jti ON jti.object_id = ji.journey_type';


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
                    $join .= ' INNER JOIN ' . $this->_tbl_journey_type_port . ' as jtp ON jtp.journey_type_id = jti.object_id';
                    $where .= ' AND jtp.port_id = ' . $port->ID;
                }
            }

            if (!empty($params['journey_type_id'])) {
                $where .= " AND ji.journey_type = " . $params['journey_type_id'];
            }

            if (!empty($params['_month'])) {
                $month = date_format(date_create_from_format("d/m/Y", '01/' . $params['_month']), "Y-m");
                $where .= " AND DATE_FORMAT(ji.departure,'%Y-%m') = '" . $month . "'";
            }

            $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p
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


    public function getInfo($object, $type = '')
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
            $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured', 'small', 'full']);
            $object->permalink = get_permalink($object->ID);

            // journey_info
            $query = 'SELECT * FROM ' . $this->_tbl_journey_info . ' WHERE object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);


            $departure_fm = date("j F Y", strtotime($object->departure));
            $object->departure_fm = $departure_fm;

            $object->is_offer = false;
            // journey_type_info
            if (!empty($object->journey_type)) {
                $journeyType = JourneyType::init();
                $journey_type_info = $journeyType->getInfo($object->journey_type);
                $object->journey_type_info = $journey_type_info;

                // days, nights, duration
                $departure = date('Y-m-d', strtotime($object->departure));
                $arrive = date('Y-m-d', strtotime($object->departure) + (intval($journey_type_info->nights) * 24 * 60 * 60));
                $object->arrive = $arrive;

                $ship_detail = $journey_type_info->ship_info;
                $current_season = 'low';
                $high_season_from = date('Y-m-d', strtotime($ship_detail->high_season_from));
                $high_season_to = date('Y-m-d', strtotime($ship_detail->high_season_to));
                if ((($high_season_from <= $departure) && ($high_season_to >= $departure)) or (($high_season_from <= $arrive) && ($high_season_to >= $arrive))) {
                    $current_season = 'high';
                }
                if (($departure <= $high_season_from) && ($arrive >= $high_season_from)) {
                    $current_season = 'high';
                }

                $object->current_season = $current_season;

                if (!empty($object->journey_type_info->ship_info->room_types)) {
                    $min_price = 9999999999999999999999999;
                    $promotion = 0;
                    $list_room_offer = [];

                    if ($object->journey_type_info->offer_main_info) {
                        $offer_main_info = $object->journey_type_info->offer_main_info;
                        $is_offer = false;
                        $offer_start = $offer_main_info->start_date;
                        $offer_end = $offer_main_info->end_date;
                        if ((($offer_start <= $departure) && ($offer_end >= $departure)) or (($offer_start <= $arrive) && ($offer_end >= $arrive))) {
                            $is_offer = true;
                        }
                        if (($departure <= $offer_start) && ($arrive >= $offer_start)) {
                            $is_offer = true;
                        }
                        //kiem tra xem jouney nay co nằm trong khoang thoi gian có offer k
                        $object->is_offer = $is_offer;
                        if ($is_offer == true) {
                            $promotion = $offer_main_info->promotion;
                        }

                        // lấy promtion và list room đc offer
                        if (!empty($object->journey_type_info->offer)) {
                            foreach ($object->journey_type_info->offer as $o) {
                                $list_room_offer[] = $o->room_type_id;
                            }
                        }
                    }
                    foreach ($object->journey_type_info->ship_info->room_types as $k => &$v) {
                        if (in_array($v->id, $list_room_offer) && !empty($promotion)) {
                            $v->twin_high_season_price_offer = intval($v->twin_high_season_price) - intval($v->twin_high_season_price) * $promotion / 100;
                            $v->single_high_season_price_offer = intval($v->single_high_season_price) - intval($v->single_high_season_price) * $promotion / 100;
                            $v->twin_low_season_price_offer = intval($v->twin_low_season_price) - intval($v->twin_low_season_price) * $promotion / 100;
                            $v->single_low_season_price_offer = intval($v->single_low_season_price) - intval($v->single_low_season_price) * $promotion / 100;
                        }

                        if ($current_season == 'low') {
                            $price_sub = $v->single_low_season_price;
                        } else {
                            $price_sub = $v->single_high_season_price;
                        }
                        if ($price_sub < $min_price) {
                            $min_price = $price_sub;
                        }
                    }

                    $object->min_price = intval($min_price);
                    $object->min_price_offer = intval($min_price) - intval($min_price) * $promotion / 100;
                    $object->min_price_fm = number_format($object->min_price);
                    $object->min_price_offer_fm = number_format($object->min_price_offer);
                }
            }

            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }


    public function getMonthHaveJourney()
    {
        $query = ' SELECT DATE_FORMAT(ji.departure,\'%Y-%m\') as month FROM ' . $this->_wpdb->posts . ' as p INNER JOIN ' . $this->_tbl_journey_info . ' as ji ON ji.object_id = p.ID WHERE p.post_status = "publish" GROUP BY month';

        $result = $this->_wpdb->get_results($query);
        if ($result) {
            foreach ($result as &$v) {
                $v->month = date_format(date_create_from_format("Y-m", $v->month), "m/Y");
            }
        }

        return $result;
    }


    public function getRoomInfo($room_id)
    {
        $query = "SELECT r.id as room_id, r.*, rt.* FROM {$this->_prefix}rooms r INNER JOIN {$this->_prefix}room_types rt ON r.room_type_id = rt.id WHERE r.id = {$room_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function getRoomPrice($room_id, $journey_id, $type)
    {
        // Only type twin & single acceptable
        if (!in_array($type, ['twin', 'single'])) {
            return 0;
        }

        $journey_season = $this->getJourneySeason($journey_id);
        $query = "SELECT rt.{$type}_{$journey_season}_season_price as raw_price, rt.id as room_type_id FROM {$this->_prefix}rooms r INNER JOIN {$this->_prefix}room_types rt ON r.room_type_id = rt.id WHERE r.id = {$room_id}";
        $result = $this->_wpdb->get_row($query);

        if (!empty($result)) {
            $price = $result->raw_price;

            // Get journey offer
            $offer = $this->getJourneyOffer($journey_id, $result->room_type_id);

            // Price - offer(%)
            $price = $price - (($price * $offer) / 100);

            return $price;
        } else {
            return 0;
        }
    }


    public function getJourneyOffer($journey_id, $room_type_id)
    {
        if (!empty($journey_id) && !empty($room_type_id)) {
            $journey_type = $this->getJourneyTypeByJourney($journey_id);

            $query = "SELECT * FROM {$this->_prefix}offer_info oi INNER JOIN {$this->_prefix}offer_journey oj ON oi.object_id = oj.offer_id WHERE oj.journey_type_id = {$journey_type->journey_type_id} AND oj.room_type_id = {$room_type_id}";
            $result = $this->_wpdb->get_row($query);
            $promotion = 0;

            if (!empty($result)) {

                // Check if promotion start date & end date is suitable
                $journey = $this->getJourneyInfoByID($journey_id);
                $departure = strtotime($journey->departure);
                $arrive = strtotime('+ ' . $journey->nights . ' days', $departure);

                $offer_start = strtotime($result->start_date);
                $offer_end = strtotime($result->end_date);

                // offer start <= departure <= offer end
                if (($offer_start <= $departure) && ($departure <= $offer_end)) {
                    $promotion = $result->promotion;
                }

                // offer start <= arrive <= offer end
                if (($offer_start <= $arrive) && ($arrive <= $offer_end)) {
                    $promotion = $result->promotion;
                }
            }

            if (is_numeric($promotion)) {
                return intval($promotion);
            }
        }

        return false;
    }


    public function getJourneyInfoByID($journey_id)
    {
        $query = "SELECT j.object_id as journey_id, j.*, jt.object_id as journey_type_id, jt.* FROM {$this->_tbl_journey_info} j INNER JOIN {$this->_tbl_journey_type_info} jt ON j.journey_type = jt.object_id WHERE j.object_id = {$journey_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function getJourneyTypeByJourney($journey_id)
    {
        $query = "SELECT jt.object_id as journey_type_id, jt.*, j.object_id as journey_id, j.* FROM {$this->_tbl_journey_info} j INNER JOIN {$this->_tbl_journey_type_info} jt ON j.journey_type = jt.object_id WHERE j.object_id = {$journey_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function getJourneySeason($journey_id)
    {
        $query = "SELECT ji.object_id as journey_id, jti.object_id as journey_type_id, ji.*, jti.* FROM {$this->_tbl_journey_info} ji INNER JOIN {$this->_tbl_journey_type_info} jti ON ji.journey_type = jti.object_id WHERE ji.object_id = {$journey_id}";
        $journey_info = $this->_wpdb->get_row($query);

        $ship_model = Ships::init();
        $ship = $ship_model->getShipDetail($journey_info->ship);
        $high_season_from = strtotime($ship->high_season_from);
        $high_season_to = strtotime($ship->high_season_to);

        $departure = strtotime($journey_info->departure);
        $arrive = strtotime('+ ' . $journey_info->nights . ' days', strtotime($journey_info->departure));

        $season = 'low';
        if ((($high_season_from <= $departure) && ($departure <= $high_season_to)) or (($high_season_from <= $arrive) && ($high_season_to >= $arrive))) {
            $season = 'high';
        }
        if (($departure <= $high_season_from) && ($arrive >= $high_season_from)) {
            $season = 'high';
        }

        return $season;
    }

}
