<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Controllers\DestinationController;
use RVN\Library\Images;

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
    private $_tbl_journey_type_price;
    private $_tbl_journey_type_itinerary;

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
        $this->_tbl_journey_type_price = $this->_prefix . 'journey_type_price';
        $this->_tbl_journey_type_itinerary = $this->_prefix . 'journey_type_itinerary';

    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new JourneyType();
        }

        return self::$instance;
    }

    public function getJourneyTypeList($params = [])
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
            // if (!empty($params['offer_id'])) {
            //     $join .= ' INNER JOIN ' . $this->_tbl_offer_journey . ' as oj ON oj.journey_type_id = p.ID';
            //     $where .= ' AND oj.offer_id = ' . intval($params['offer_id']);
            // }


            $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p
            $join
            WHERE p.post_type = 'journey_type' AND p.post_status='publish'
            $where  
            GROUP BY p.ID
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

        if (!empty($params['is_paging'])) {
            $this->_set_paging($result['data'], $result['total'], $params['limit'], $params['page']);
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
            $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured', 'full', 'small']);
            $object->permalink = get_permalink($object->ID);

            // tbl journey_type_info
            $query = 'SELECT jti.* FROM ' . $this->_tbl_journey_type_info . ' as jti 
            WHERE jti.object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);

            if (!empty($object->map_image)) {
                $img = wp_get_attachment_image_src($object->map_image, 'small');
                if ($img) {
                    $object->map_image = $img[0];
                }
            }

            // days, nights, duration
            $object->days = intval($object->nights) + 1;
            $object->duration = $object->days . " days " . intval($object->nights) . " nights";

            // ship_info
            if ($object->ship) {
                $ship = Ships::init();
                $ship_detail = $ship->getShipDetail($object->ship);

                $object->ship_info = $ship_detail;
            }

            // Desitnation
            $objPost = Posts::init();
            if ($object->destination) {
                $destination = $objPost->getInfo($object->destination);
                $object->destination_info = $destination;
            }

            // Initerary
            $object->itinerary = $this->getJourneyTypeItinerary($object->ID);


            // Gallery
            $objGallery = Gallery::init();
            $gallery = $objGallery->getGalleryBy($object->ID);
            $object->gallery = $gallery;

            $room_price = $this->getJourneyTypePrice($object->ID);
            $object->room_price = $room_price;

            // $object->offer_main_info = false;
            // $object->offer = false;
            // if ($type != 'offer') {
            //     $objOffer = Offer::init();
            //     $offer = $objOffer->getOfferByJourneyType($object->ID);
            //     if (!empty($offer)) {
            //         $object->offer = $offer;
            //         $object->offer_main_info = $object->offer[0]->offer_info;
            //         $object->offer_main_info = $object->offer[0]->offer_info;
            //         if (!empty($object->offer_main_info->start_date)) {
            //             $object->offer_main_info->month_year = date('M Y', strtotime($object->offer_main_info->start_date));
            //         }
            //     }
            // }

            $result = $object;
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }

    public function getJourneyMinPrice($journey_type_id, $type = '')
    {
        $result = false;
        $objJourney = Journey::init();
        $list_journey = $objJourney->getJourneyList([
            'journey_type_id' => $journey_type_id,
            'limit'           => 9999,
        ]);
        if (!empty($list_journey['data'])) {
            $min_price = 99999999999999999;
            $journey_min_price = [];

            if ($type == 'offer') {
                foreach ($list_journey['data'] as $journey) {
                    if (!empty($journey->is_offer)) {
                        $min_price_journey = $journey->min_price_offer;
                        if ($min_price_journey < $min_price) {
                            $min_price = $min_price_journey;
                            $journey_min_price = $journey;
                        }
                    }
                }
            } else {
                foreach ($list_journey['data'] as $journey) {
                    $min_price_journey = $journey->min_price;
                    if (!empty($journey->min_price_offer)) {
                        $min_price_journey = $journey->min_price_offer;
                    }
                    if ($min_price_journey < $min_price) {
                        $min_price = $min_price_journey;
                        $journey_min_price = $journey;
                    }
                }
            }

            $result = $journey_min_price;

        }

        return $result;
    }

    public function saveJourneyTypeInfo($id, $data)
    {

        $select = 'SELECT * FROM ' . $this->_tbl_journey_type_info . ' WHERE object_id  = ' . $id;
        $jt_info = $this->_wpdb->get_row($select);
        if (($jt_info)) {
            $this->updateJourneyTypeInfo($id, $data);
        } else {
            $this->_wpdb->insert($this->_tbl_journey_type_info, $data);
        }
    }

    public function updateJourneyTypeInfo($id, $data)
    {
        $result = false;
        if ($id) {
            $result = $this->_wpdb->update($this->_tbl_journey_type_info, $data, ['object_id' => $id]);
        }

        return $result;
    }

    public function getJourneyTypePrice($journey_type_id)
    {
        $select = 'SELECT jtp.*,rt.* FROM ' . $this->_tbl_journey_type_price . ' jtp
         INNER JOIN  ' . $this->_tbl_room_types . ' as rt ON jtp.room_type_id = rt.id 
         WHERE jtp.journey_type_id  = ' . $journey_type_id;

        $result = $this->_wpdb->get_results($select);

        return $result;
    }

    public function saveJourneyTypePrice($data)
    {
        $result = false;
        if (!empty($data['journey_type_id'])) {

            $result = $this->_wpdb->insert($this->_tbl_journey_type_price, $data);
        }

        return $result;
    }

    public function deleteJourneyTypePrice($journey_type_id)
    {
        $result = false;
        if (!empty($journey_type_id)) {
            $result = $this->_wpdb->delete($this->_tbl_journey_type_price, ['journey_type_id' => $journey_type_id]);

        }

        return $result;
    }

    public function getJourneyTypeItinerary($journey_type_id)
    {
        $select = 'SELECT jtp.* FROM ' . $this->_tbl_journey_type_itinerary . ' jtp
         WHERE jtp.journey_type_id  = ' . $journey_type_id;

        $result = $this->_wpdb->get_results($select);
        if ($result) {
            foreach ($result as &$v) {
                $location_info = [];
                if (!empty($v->location)) {
                    if (is_serialized($v->location)) {
                        $lc = unserialize($v->location);
                    } else {
                        $lc = [$v->location];
                    }
                    if (!empty($lc)) {
                        foreach ($lc as $l) {
                            $objPost = Posts::init();
                            $location_info[] = $objPost->getInfo($l);
                        }
                    }
                }
                $v->location_info = $location_info;
            }
        }

        return $result;
    }

    public function saveJourneyTypeItinerary($data)
    {
        $result = false;
        if (!empty($data['journey_type_id'])) {

            $result = $this->_wpdb->insert($this->_tbl_journey_type_itinerary, $data);
        }

        return $result;
    }

    public function deleteJourneyTypeItinerary($journey_type_id)
    {
        $result = false;
        if (!empty($journey_type_id)) {
            $result = $this->_wpdb->delete($this->_tbl_journey_type_itinerary, ['journey_type_id' => $journey_type_id]);

        }

        return $result;
    }


    public function getJourneyTypeMinPrice($jt_id)
    {
        $m_journey = Journey::init();
        $journeys = $m_journey->getJourneyList(['journey_type_id' => $jt_id]);

        $min = 0;
        if (!empty($journeys['data'])) {
            foreach ($journeys['data'] as $k => $v) {

                // Get journey min price, compare to get minimum
                // k = 0 => set minimum
                $journey_min_price = $m_journey->getJourneyMinPrice($v->ID, true);
                if (($k == 0) || ($journey_min_price->min_price_offer < $min)) {
                    $min = $journey_min_price->min_price_offer;
                }

            }
        }

        return $min;
    }


    public function isJourneyTypeHaveJourney($jt_id)
    {
        $query = "SELECT object_id FROM {$this->_prefix}journey_series_info WHERE journey_type_id = {$jt_id}";
        $journey_series = $this->_wpdb->get_results($query);

        // Flag = false : ko có journey
        // Flah = true : có journey
        $flag = false;
        foreach ($journey_series as $k => $v) {
            if (!$flag) {
                $query = "SELECT * FROM {$this->_tbl_journey_info} WHERE journey_series_id = {$v->object_id} AND departure >= CURDATE() LIMIT 1";
                $is_exist = $this->_wpdb->get_row($query);
                if ($is_exist) {
                    $flag = true;
                    break;
                }
            } else {
                break;
            }
        }

        return $flag;
    }


    public function getMonthsHaveJourney($destination)
    {
        $m_post = Posts::init();
        // $query = "SELECT * FROM {$this->_wpdb->posts} WHERE post_name = '{$destination}' AND post_type='destination'";
        // $dest = $this->_wpdb->get_row($query);
        if(is_numeric($destination)) {
            $dest = get_post($destination);
        } else {
            $dest = $m_post->getPostBySlug($destination, 'destination');
        }

        // Get list journey type have destination id
        $query = "SELECT * FROM {$this->_tbl_journey_type_info} WHERE destination = {$dest->ID}";
        $jt = $this->_wpdb->get_results($query);

        $result = [];
        foreach ($jt as $k => $v) {

            if (!empty($v->object_id)) {
                // Get journey series of journey type
                $query1 = "SELECT * FROM {$this->_prefix}journey_series_info jsi INNER JOIN {$this->_wpdb->posts} p ON jsi.object_id = p.ID WHERE jsi.journey_type_id = {$v->object_id} AND p.post_status = 'publish'";
                $series = $this->_wpdb->get_results($query1);

                // Select months from journey info
                foreach ($series as $key => $value) {
                    if (!empty($value->object_id)) {

                        $query2 = "SELECT DATE_FORMAT(departure, '%m/%Y') as jt_month FROM {$this->_tbl_journey_info} WHERE journey_series_id = {$value->object_id} AND departure >= CURDATE() GROUP BY DATE_FORMAT(departure, '%m/%Y')";
                        $month_rs = $this->_wpdb->get_results($query2);

                        foreach ($month_rs as $month) {
                            if (!empty($month->jt_month)) {
                                $result[$month->jt_month] = $month->jt_month;
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }


    // Get ports, excursion
    public function getPorts($data)
    {
        $result = [];

        // Destination
        $m_post = Posts::init();
        if(is_numeric($data['destination'])) {
            $dest = get_post($data['destination']);
        } else {
            $dest = $m_post->getPostBySlug($data['destination'], 'destination');
        }

        // Get journey_series_id
        $query = "SELECT * FROM {$this->_tbl_journey_info} ji INNER JOIN {$this->_prefix}journey_series_info jsi ON ji.journey_series_id = jsi.object_id INNER JOIN {$this->_wpdb->posts} p ON jsi.object_id = p.ID INNER JOIN {$this->_tbl_journey_type_info} jti ON jsi.journey_type_id = jti.object_id WHERE ji.departure >= CURDATE() AND DATE_FORMAT(ji.departure, '%m/%Y') = '{$data['month']}' AND jti.destination = {$dest->ID} AND p.post_status = 'publish' GROUP BY ji.journey_series_id";
        $rs = $this->_wpdb->get_results($query);

        if (!empty($rs)) {

            foreach ($rs as $k => $v) {
                $itinary_list = $this->getJourneyTypeItinerary($v->object_id);

                foreach ($itinary_list as $key => $val) {
                    if (!empty($val->location)) {
                        $locations = unserialize($val->location);
                        if (!empty($locations)) {
                            foreach ($locations as $k2 => $v2) {
                                $excursion = get_post($v2);
                                $result[$excursion->post_name] = $excursion->post_title;
                            }
                        }
                    }

                }

            }
        }

        return $result;
    }


    // Get ships by dest, month, port
    public function getShips($data)
    {
        $result = [];

        $m_post = Posts::init();
        $m_ship = Ships::init();

        // Destination
        if(is_numeric($data['dest'])) {
            $dest = get_post($data['dest']);
        } else {
            $dest = $m_post->getPostBySlug($data['dest'], 'destination');
        }

        // Port
        if(is_numeric($data['port'])) {
            $port_excursion = get_post($data['port']);
        } else {
            $port_excursion = $m_post->getPostBySlug($data['port'], ['port', 'excursion']);
        }

        // Get journey_series_id
        $query = "SELECT * FROM {$this->_tbl_journey_info} ji 
INNER JOIN {$this->_prefix}journey_series_info jsi ON ji.journey_series_id = jsi.object_id 
INNER JOIN {$this->_tbl_journey_type_info} jti ON jsi.journey_type_id = jti.object_id 
INNER JOIN {$this->_wpdb->posts} p ON jsi.object_id = p.ID
WHERE ji.departure >= CURDATE() 
AND DATE_FORMAT(ji.departure, '%m/%Y') = '{$data['month']}' 
AND jti.destination = {$dest->ID}
 AND p.post_status = 'publish'
GROUP BY ji.journey_series_id";
        $rs = $this->_wpdb->get_results($query);

        if (!empty($rs)) {

            foreach ($rs as $k => $v) {
                $itinary_list = $this->getJourneyTypeItinerary($v->journey_type_id);

                foreach ($itinary_list as $key => $val) {
                    if (!empty($val->location)) {
                        $locations = unserialize($val->location);
                        if (!empty($locations)) {
                            foreach ($locations as $k2 => $v2) {
                                $excursion = get_post($v2);
                                if ($excursion->ID == $port_excursion->ID) {
                                    $ship = $m_ship->getShipDetail($v->ship);
                                    $result[$ship->post_name] = $ship->post_title;
                                }
                            }
                        }
                    }

                }

            }
        }

        return $result;
    }
}
