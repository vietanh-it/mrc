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
    private $_tbl_journey_series_info;

    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_journey_info = $this->_prefix . 'journey_info';
        $this->_tbl_journey_type_info = $this->_prefix . 'journey_type_info';
        $this->_tbl_journey_type_port = $this->_prefix . 'journey_type_port';
        $this->_tbl_journey_series_info = $this->_prefix . 'journey_series_info';
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
        }
        else {
            $result = false;
        }
        if ($result == false) {
            $page = (empty($params['page'])) ? 1 : intval($params['page']);
            $limit = (empty($params['limit'])) ? 6 : intval($params['limit']);
            $to = ($page - 1) * $limit;
            $order_by = "  ji.departure";
            if (!empty($params['order_by'])) {
                $order_by = $params['order_by'];
            }

            $where = '';
            $join = '';

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
                    $join .= ' INNER JOIN ' . $this->_tbl_journey_type_port . ' as jtp ON jtp.journey_type_id = jti.object_id';
                    $where .= ' AND jtp.port_id = ' . $port->ID;
                }
            }

            if (!empty($params['journey_type_id'])) {
                $where .= " AND jsi.journey_type_id = " . $params['journey_type_id'];
            }

            if (!empty($params['_month'])) {
                $month = date_format(date_create_from_format("d/m/Y", '01/' . $params['_month']), "Y-m");
                $where .= " AND DATE_FORMAT(ji.departure,'%Y-%m') = '" . $month . "'";
            }

            $query = "SELECT SQL_CALC_FOUND_ROWS ji.journey_code, ji.journey_series_id, ji.departure,ji.navigation,jsi.object_id, jsi.journey_type_id, jsi.prefix FROM " . $this->_tbl_journey_info . " as ji
            INNER JOIN ". $this->_tbl_journey_series_info ." as jsi ON jsi.object_id = ji.journey_series_id
            INNER JOIN ". $this->_tbl_journey_type_info ." as jti ON jti.object_id = jsi.journey_type_id
            $join
            WHERE 1=1
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

        if (!empty($params['is_paging'])) {
            $this->_set_paging($result['data'], $result['total'], $params['limit'], $params['page']);
        }

        return $result;

    }


    public function getInfo($object, $type = '')
    {
        if (is_string($object)) {
            $cacheId = __CLASS__ . 'getInfo' . $object;
        }
        else {
            $cacheId = __CLASS__ . 'getInfo' . $object->journey_code;
        }

        $result = wp_cache_get($cacheId);
        if ($result == false) {

            if (is_string($object)) {
                $query = 'SELECT ji*, jsi.* FROM ' . $this->_tbl_journey_info . ' as ji
                INNER JOIN ' . $this->_tbl_journey_series_info . ' as jsi ON jsi.object_id = ji.journey_series_id
                WHERE journey_code =  "'. $object .'"';
                $object = $this->_wpdb->get_row($query);
            }
            $object->permalink = '';

            if (!empty($object->journey_type_id)) {
                $journeyType = JourneyType::init();
                $journey_type_info = $journeyType->getInfo($object->journey_type_id);
                $object->journey_type_info = $journey_type_info;
            }

            $current_season = $this->getJourneySeason($object->journey_code);
            $object->current_season = $current_season;
            $object->is_offer = false;

            if(!empty($journey_type_info)){
                // days, nights, duration
                $departure_fm = date("j F Y", strtotime($object->departure));
                $object->departure_fm = $departure_fm;
                $departure = date('Y-m-d', strtotime($object->departure));
                $arrive = date('Y-m-d', strtotime($object->departure) + (intval($journey_type_info->nights) * 24 * 60 * 60));
                $object->arrive = $arrive;

                if (!empty($journey_type_info->room_price)) {
                    $min_price = 9999999999999999999999999;
                    $promotion = 0;
                    $list_room_offer = [];

                    if ($journey_type_info->offer_main_info) {
                        $offer_main_info = $journey_type_info->offer_main_info;
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
                        if (!empty($journey_type_info->offer)) {
                            foreach ($journey_type_info->offer as $o) {
                                $list_room_offer[] = $o->room_type_id;
                            }
                        }
                    }
                    foreach ($journey_type_info->room_price as $k => &$v) {
                        if (in_array($v->id, $list_room_offer) && !empty($promotion)) {
                            $v->twin_high_season_price_offer = intval($v->twin_high_season_price) - intval($v->twin_high_season_price) * $promotion / 100;
                            $v->single_high_season_price_offer = intval($v->single_high_season_price) - intval($v->single_high_season_price) * $promotion / 100;
                            $v->twin_low_season_price_offer = intval($v->twin_low_season_price) - intval($v->twin_low_season_price) * $promotion / 100;
                            $v->single_low_season_price_offer = intval($v->single_low_season_price) - intval($v->single_low_season_price) * $promotion / 100;
                        }

                        if ($current_season == 'low') {
                            $price_sub = $v->single_low_season_price;
                        }
                        else {
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


    private function _set_paging($data, $total, $limit, $page)
    {
        global $wp_query;
        $wp_query->posts = $data;
        $wp_query->is_paged = ($page >= 1) ? true : false;
        $wp_query->found_posts = $total;
        $wp_query->max_num_pages = ceil($total / $limit);
    }


    public function getMonthHaveJourney()
    {
        $query = ' SELECT DATE_FORMAT(ji.departure,\'%Y-%m\') as month
                  FROM ' . $this->_tbl_journey_info . ' as ji  GROUP BY month';

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
        // $query = "SELECT rt.{$type}_{$journey_season}_season_price as raw_price, rt.id as room_type_id FROM {$this->_prefix}rooms r
        // INNER JOIN {$this->_prefix}journey_type_price rt ON r.room_type_id = rt.id
// WHERE r.id = {$room_id}";
        $journey = $this->getJourneyInfoByID($journey_id);
        $room = $this->getRoomInfo($room_id);

        $query = "SELECT jtp.{$type}_{$journey_season}_season_price as raw_price, jtp.room_type_id as room_type_id FROM {$this->_prefix}journey_type_price jtp WHERE journey_type_id = {$journey->journey_type_id} AND room_type_id = {$room->room_type_id}";
        $result = $this->_wpdb->get_row($query);

        if (!empty($result)) {
            $price = $result->raw_price;

            // Get journey offer
            $offer = $this->getJourneyOffer($journey_id, $result->room_type_id);

            // Price - offer(%)
            $price = $price - (($price * $offer) / 100);

            return $price;
        }
        else {
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


    public function getJourneyTypeByJourney($journey_code)
    {
        $query = "SELECT jt.*,j.* FROM {$this->_tbl_journey_info} j
 INNER JOIN {$this->_tbl_journey_series_info} jsi ON j.journey_series_id = jsi.object_id
 INNER JOIN {$this->_tbl_journey_type_info} jt ON  jsi.journey_type_id = jt.object_id
  WHERE j.journey_code = '{$journey_code}'";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function getJourneySeason($journey_code)
    {
        $query = "SELECT  ji.*,jsi.*, jti.* FROM {$this->_tbl_journey_info} ji 
INNER JOIN {$this->_tbl_journey_series_info} jsi ON jsi.object_id = ji.journey_series_id
INNER JOIN {$this->_tbl_journey_type_info} jti ON jsi.journey_type_id = jti.object_id
 WHERE ji.journey_code = '{$journey_code}'";
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
