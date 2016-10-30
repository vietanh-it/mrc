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

            // Paging
            $page = (empty($params['page'])) ? 1 : intval($params['page']);
            $limit = (empty($params['limit'])) ? 6 : intval($params['limit']);
            $to = ($page - 1) * $limit;

            // Order by
            $order_by = "  ji.departure ASC ";
            if (!empty($params['order_by'])) {
                $order_by = $params['order_by'];
            }

            $where = '';
            $join = '';
            $not_in = '';

            $objPost = Posts::init();
            $join .= ' INNER JOIN ' . $this->_tbl_journey_info . ' as ji ON ji.object_id = p.ID
                       INNER JOIN ' . $this->_tbl_journey_series_info . ' as jsi ON jsi.object_id = ji.journey_series_id
                       INNER JOIN ' . $this->_wpdb->posts . ' as pjs ON jsi.object_id = pjs.ID
                       INNER JOIN ' . $this->_tbl_journey_type_info . ' as jti ON jti.object_id = jsi.journey_type_id';

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

            if (!empty($params['journey_series_id'])) {
                $where .= " AND jsi.object_id = " . $params['journey_series_id'];
            }

            if (!empty($params['_month'])) {
                $month = date_format(date_create_from_format("d/m/Y", '01/' . $params['_month']), "Y-m");
                $where .= " AND DATE_FORMAT(ji.departure,'%Y-%m') = '" . $month . "'";
            }

            // Loại những journey đã khởi hành
            if (empty($params['is_get_all'])) {
                $where .= " AND DATE_FORMAT(ji.departure, '%Y-%m-%d') >= '" . date('Y-m-d') . "'";
            }


            // Loại những journey đã có offer
            if (!empty($params['is_exclude_offered'])) {
                $query = "SELECT journey_id FROM " . TBL_OFFER_INFO . " GROUP BY journey_id";
                $journey_offers = $this->_wpdb->get_results($query);
                if (!empty($journey_offers)) {
                    $not_exclude = valueOrNull($params['not_exclude']);

                    $not_in_ctn = '';
                    foreach ($journey_offers as $k => $v) {

                        // Không loại journey của offer hiện tại
                        if ($v->journey_id != $not_exclude) {
                            $not_in_ctn .= $v->journey_id . ',';
                        }

                    }

                    // trim
                    $not_in_ctn = trim($not_in_ctn, ',');
                    if (!empty($not_in_ctn)) {
                        $not_in .= ' AND p.ID NOT IN(' . $not_in_ctn . ')';
                    }

                }
            }

            $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p
            $join
            WHERE p.post_type = 'journey' AND p.post_status='publish' AND pjs.post_status = 'publish' 
            $where $not_in
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
        if (is_numeric($object)) {
            $cacheId = __CLASS__ . 'getInfo' . $object;
        }
        else {
            $cacheId = __CLASS__ . 'getInfo' . $object->ID;
        }

        $result = wp_cache_get($cacheId);
        if ($result == false) {

            if (is_numeric($object)) {
                $object = get_post($object);
            }
            $object->permalink = get_permalink($object->ID);

            $post_info = $this->getJourneySeriesInfoByJourney($object->ID);
            $object = (object)array_merge((array)$object, (array)$post_info);


            // Current season
            $current_season = $this->getJourneySeason($object->ID);
            $object->current_season = $current_season;
            $object->is_offer = false;


            // Journey Type info
            if (!empty($object->journey_type_id)) {
                $journeyType = JourneyType::init();
                $journey_type_info = $journeyType->getInfo($object->journey_type_id);
                $object->journey_type_info = $journey_type_info;

                // Room Prices
                $room_price = $journey_type_info->room_price;
                foreach ($room_price as $k => $v) {
                    $offer = $this->getJourneyOffer($object->ID, $v->id);
                    $remain = 100 - $offer;

                    $v->current_season = $current_season;
                    $v->offer = floatval($offer);
                    $v->twin_price = ($v->{"twin_" . $current_season . "_season_price"} * $remain) / 100;
                    $v->single_price = ($v->{"single_" . $current_season . "_season_price"} * $remain) / 100;
                }
                $object->room_price = $room_price;
            }


            // Journey min price with offer
            $min_price = $this->getJourneyMinPrice($object->ID, true);
            $object->min_price = $min_price->min_price_offer;


            if (!empty($journey_type_info)) {

                // days, nights, duration
                $object->departure_fm = date("j F Y", strtotime($object->departure));
                $arrive = date('Y-m-d',
                    strtotime($object->departure) + (intval($journey_type_info->nights) * 24 * 60 * 60));
                $object->arrive = $arrive;

            }

            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }


    public function getJourneySeriesInfoByJourney($journey_id)
    {
        $query = 'SELECT ji.*, jsi.* FROM ' . $this->_tbl_journey_info . ' as ji
                  INNER JOIN ' . $this->_tbl_journey_series_info . ' as jsi ON jsi.object_id = ji.journey_series_id
                  WHERE ji.object_id =  "' . $journey_id . '"';
        return $this->_wpdb->get_row($query);
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
                  FROM ' . $this->_tbl_journey_info . ' as ji WHERE ji.departure > CURDATE() GROUP BY month';

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
        $query = "SELECT promotion FROM " . TBL_OFFER_INFO . " WHERE journey_id = {$journey_id} AND room_type_id = {$room_type_id}";
        $promotion = $this->_wpdb->get_var($query);

        return valueOrNull($promotion, 0);
    }


    public function getJourneyInfoByID($journey_id)
    {
        $query = "SELECT j.object_id as journey_id, j.*, jt.object_id as journey_type_id, jt.* FROM {$this->_tbl_journey_info} j 
                  INNER JOIN {$this->_tbl_journey_series_info} jsi ON j.journey_series_id = jsi.object_id
                  INNER JOIN {$this->_tbl_journey_type_info} jt ON jsi.journey_type_id = jt.object_id
                  WHERE j.object_id = {$journey_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function getJourneyTypeByJourney($journey_id)
    {
        $query = "SELECT jsi.*,jt.*,j.* FROM {$this->_tbl_journey_info} j
 INNER JOIN {$this->_tbl_journey_series_info} jsi ON j.journey_series_id = jsi.object_id
 INNER JOIN {$this->_tbl_journey_type_info} jt ON  jsi.journey_type_id = jt.object_id
  WHERE j.object_id = '{$journey_id}'";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function getJourneySeason($journey_id)
    {
        $query = "SELECT  ji.*,jsi.*, jti.* FROM {$this->_tbl_journey_info} ji 
INNER JOIN {$this->_tbl_journey_series_info} jsi ON jsi.object_id = ji.journey_series_id
INNER JOIN {$this->_tbl_journey_type_info} jti ON jsi.journey_type_id = jti.object_id
 WHERE ji.object_id = '{$journey_id}'";
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

    /*public function saveJourney($data){
        $result = false;
        if($data['object_id']){
            $object_id = $data['object_id'];
            $journey_series_info = $this->getJourneySeriesInfo($object_id);
            if(($journey_series_info)){
                unset($data['object_id']);
                $result = $this->_wpdb->update($this->_tbl_journey_series_info,$data,array('object_id'=>$object_id));
            }else{
                $result = $this->_wpdb->insert($this->_tbl_journey_series_info,$data);
            }

        }

        return $result;
    }*/


    public function insertJourneyDetail($data)
    {
        $result = false;
        if ($data['journey_series_id']) {
            $result = $this->_wpdb->insert($this->_tbl_journey_info, $data);
        }

        return $result;
    }


    public function deleteJourneyDetail($journey_series_id)
    {
        $result = false;

        if ($journey_series_id) {
            $result = $this->_wpdb->delete($this->_tbl_journey_info, ['journey_series_id' => $journey_series_id]);
        }

        return $result;
    }


    public function getJourneyDetailByJourneySeries($journey_series_id)
    {
        $select = "SELECT ji.* FROM " . $this->_tbl_journey_info . " as ji 
        INNER JOIN " . $this->_wpdb->posts . " as p ON p.ID = ji.object_id
        WHERE p.post_status = 'publish' AND ji.journey_series_id = " . $journey_series_id;
        return $this->_wpdb->get_results($select);
    }


    public function insertJourney($data)
    {
        // $this->_wpdb->insert($this->_wpdb->posts, $data);
        wp_insert_post($data);
        $result = $this->_wpdb->insert_id;
        return $result;
    }


    public function deleteJourney($journey_id)
    {
        $result = false;
        if ($journey_id) {
            $result = $this->_wpdb->update($this->_wpdb->posts,array('post_status'=>'draft'), ['ID' => $journey_id]);
        }

        return $result;
    }


    /**
     * Lấy journey_type_id của journey
     *
     * @param $journey_id
     * @return null|string
     */
    public function getJourneyTypeID($journey_id)
    {
        // Get journey_type_id of journey
        $query = "SELECT jsi.journey_type_id FROM {$this->_tbl_journey_info} ji INNER JOIN {$this->_tbl_journey_series_info} jsi ON ji.journey_series_id = jsi.object_id WHERE ji.object_id = {$journey_id}";
        $result = $this->_wpdb->get_var($query);

        return $result;
    }


    /**
     * Get Journey info by Offer
     *
     * @param $offer_id
     * @return bool|mixed|object
     */
    public function getJourneyByOffer($offer_id)
    {
        // Get journey id
        $query = "SELECT journey_id FROM " . TBL_OFFER_INFO . " WHERE object_id = {$offer_id} LIMIT 1";
        $journey_id = $this->_wpdb->get_var($query);

        $journey_info = $this->getInfo($journey_id);

        return $journey_info;
    }


    /**
     * Get journey min price
     *
     * @param $journey_id
     * @param bool $is_offer
     * @return object
     */
    public function getJourneyMinPrice($journey_id, $is_offer = false)
    {
        $result = [
            'room_type_id'    => 0,
            'min_price'       => 0,
            'min_price_offer' => 0,
            'type'            => ''
        ];

        // Get journey type id by journey id
        $journey_type_id = $this->getJourneyTypeID($journey_id);
        if (!empty($journey_type_id)) {

            // Get current season
            $current_season = $this->getJourneySeason($journey_id);

            // Get room type prices
            $query = "SELECT * FROM {$this->_prefix}journey_type_price WHERE journey_type_id = {$journey_type_id}";
            $rt_prices = $this->_wpdb->get_results($query);

            // Get min price
            if (!empty($rt_prices)) {

                // Initialize value
                $min = $rt_prices[0]->{'twin_' . $current_season . '_season_price'};

                $result = [
                    'room_type_id'    => $rt_prices[0]->room_type_id,
                    'min_price'       => $min,
                    'min_price_offer' => $min,
                    'type'            => 'twin'
                ];

                foreach ($rt_prices as $k => $v) {

                    // Calculate original price
                    $twin_price = ($v->{'twin_' . $current_season . '_season_price'});
                    $single_price = $v->{'single_' . $current_season . '_season_price'};

                    if ($is_offer) {

                        // Get offer for room type
                        $offer = $this->getJourneyOffer($journey_id, $v->room_type_id);

                        // With offer
                        if (!empty($offer)) {

                            // Offer 10% => remain = 90%
                            $remain = 100 - $offer;

                            $twin_price = ($twin_price * $remain) / 100;
                            $single_price = ($single_price * $remain) / 100;
                        }

                    }

                    // Twin
                    if ($twin_price < $min) {
                        $min = $twin_price;

                        $result = [
                            'room_type_id'    => $v->room_type_id,
                            'min_price'       => ($v->{'twin_' . $current_season . '_season_price'}),
                            'min_price_offer' => $min,
                            'type'            => 'twin'
                        ];
                    }

                    // Single
                    if ($single_price < $min) {
                        $min = $single_price;

                        $result = [
                            'room_type_id'    => $v->room_type_id,
                            'min_price'       => $v->{'single_' . $current_season . '_season_price'},
                            'min_price_offer' => $min,
                            'type'            => 'single'
                        ];
                    }

                }

            }
        }

        return (object)$result;
    }


    /**
     * Get journey list by journey series
     *
     * @param $j_series_id
     * @return array|null|object
     */
    public function getJourneyListBySeries($j_series_id)
    {
        $query = "SELECT * FROM {$this->_tbl_journey_info} WHERE journey_series_id = {$j_series_id}";
        $result = $this->_wpdb->get_results($query);

        return $result;
    }


    public function getRoomTypes($journey_id)
    {
        $journey_type = $this->getJourneyTypeByJourney($journey_id);

        $m_ship = Ships::init();
        $result = $m_ship->getShipRoomTypes($journey_type->ship);

        return $result;
    }


    public function getAvailableRooms($args)
    {
        $m_booking = Booking::init();

        $journey_type = $this->getJourneyTypeByJourney($args['journey_id']);
        $rooms = $m_booking->getBookedRoom($args['journey_id']);

        $query = "SELECT r.id, r.room_name, r.room_type_id, rt.room_type_name FROM " . TBL_ROOMS . " r INNER JOIN " . TBL_ROOM_TYPES . " rt ON r.room_type_id = rt.id WHERE rt.ship_id = {$journey_type->ship}";

        // Get by room type id
        if (!empty($args['room_type_id'])) {
            if ($args['room_type_id'] != 'all') {
                $query .= " AND rt.id = {$args['room_type_id']}";
            }
        }

        if (!empty($rooms)) {
            $not_in = '';
            foreach ($rooms as $room) {
                $not_in .= $room . ",";
            }
            $not_in = trim($not_in, ',');
            $query .= " AND r.id NOT IN ({$not_in})";
        }

        $result = $this->_wpdb->get_results($query);

        return $result;
    }

}
