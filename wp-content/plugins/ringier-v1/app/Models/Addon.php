<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class Addon
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_journey_type_info;
    private $_tbl_tour_info;
    private $_tbl_tour_journey_type;
    private $_tbl_addon_options;
    private $_tbl_cart_addon;

    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_tour_info = $this->_prefix . 'tour_info';
        $this->_tbl_tour_journey_type = $this->_prefix . 'tour_journey_type';
        $this->_tbl_journey_type_info = $this->_prefix . 'journey_type_info';
        $this->_tbl_addon_options = $this->_prefix . 'addon_options';
        $this->_tbl_cart_addon = $this->_prefix . 'cart_addon';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Addon();
        }

        return self::$instance;
    }


    public function getList($params)
    {
        $page = (empty($params['page'])) ? 1 : intval($params['page']);
        $limit = (empty($params['limit'])) ? 6 : intval($params['limit']);
        $to = ($page - 1) * $limit;
        $order_by = "  p.post_date DESC ";
        if (!empty($params['order_by'])) {
            $order_by = $params['order_by'];
        }

        $where = ' ';
        $join = '';


        if (!empty($params['journey_type_id'])) {
            $join .= ' INNER JOIN ' . $this->_tbl_tour_journey_type . ' as ji ON ji.tour_id = p.ID';
            $where .= ' AND ji.journey_type_id = ' . intval($params['journey_type_id']);
        }

        if (empty($params['post_type'])) {
            $where .= ' AND p.post_type IN ("addon","tour")';
        } else {
            $where .= ' AND p.post_type = ' . $params['post_type'];
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p
            $join
            WHERE p.post_status='publish'
            $where          
            ORDER BY $order_by  LIMIT $to, $limit";

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

        return $result;

    }


    public function getInfo($object, $type = '')
    {
        // Post
        if (is_numeric($object)) {
            $object = get_post($object);
        }

        // images, permalink
        $objImages = Images::init();
        $object->images = $objImages->getPostImages($object->ID, ['thumbnail', 'featured', 'small', 'full']);
        $object->permalink = get_permalink($object->ID);

        if ($object->post_type = 'tour') {
            $query = 'SELECT * FROM ' . $this->_tbl_tour_info . ' WHERE object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);

            // Default value
            $post_info->length = valueOrNull($post_info->length, 0);
            $post_info->twin_share_price = valueOrNull($post_info->twin_share_price, 0);
            $post_info->single_price = valueOrNull($post_info->single_price, 0);

            $object = (object)array_merge((array)$object, (array)$post_info);
        }

        if ($object->post_type = 'addon') {
            $addon_option = $this->getAddonOptions($object->ID);
            $object->addon_option = $addon_option;
        }

        $result = $object;

        return $result;
    }


    public function getAddonOptions($object_id)
    {
        $query = "SELECT * FROM {$this->_tbl_addon_options} WHERE object_id = $object_id";
        $result = $this->_wpdb->get_results($query);

        return $result;
    }


    public function getCartAddon($cart_id, $object_id = 0, $addon_option_id = 0, $type = '')
    {
        if (!empty($cart_id)) {
            $query = "SELECT * FROM {$this->_tbl_cart_addon} WHERE cart_id = {$cart_id}";

            if (!empty($object_id)) {
                $query .= " AND object_id = {$object_id}";
            }
            if (!empty($addon_option_id)) {
                $query .= " AND addon_option_id = {$addon_option_id}";
            }
            if (!empty($type)) {
                $query .= " AND type = '{$type}'";
            }

            $result = $this->_wpdb->get_results($query);
        } else {
            $result = [];
        }

        return $result;
    }


    public function switchAddonStatus($cart_id, $object_id)
    {
        if (!empty($cart_id) && !empty($object_id)) {
            $current_status = $this->_wpdb->get_var("SELECT status FROM {$this->_tbl_cart_addon} WHERE cart_id = {$cart_id} AND object_id = {$object_id} LIMIT 1");
            if ($current_status == 'active') {
                $next_status = 'inactive';
            } elseif ($current_status == 'inactive') {
                $next_status = 'active';
            } else {
                // Cart addon null
                return false;
            }

            $this->_wpdb->update($this->_tbl_cart_addon, ['status' => $next_status], [
                'cart_id'   => $cart_id,
                'object_id' => $object_id
            ]);

            $booking_model = Booking::init();
            $cart_total = $booking_model->getCartTotalByID($cart_id);
            $result['cart_total'] = $cart_total;
            $result['cart_total_text'] = number_format($cart_total);
            $result['current_status'] = $next_status;

            return (object)$result;
        } else {
            return false;
        }
    }


    public function saveAddon($data)
    {
        $insert = false;
        if ($data['object_id']) {
            $insert = $this->_wpdb->insert($this->_tbl_addon_options, $data);
        }

        return $insert;
    }

    public function delete($data)
    {
        $result = false;
        if ($data['object_id']) {
            $result = $this->_wpdb->delete($this->_tbl_addon_options, ['object_id' => $data['object_id']]);
        }

        return $result;
    }

}
