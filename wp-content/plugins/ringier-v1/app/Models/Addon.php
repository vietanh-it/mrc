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


        if (!empty($params['journey_id'])) {
            $m_journey = Journey::init();
            $journey_type_id = $m_journey->getJourneyTypeID($params['journey_id']);

            $join .= ' INNER JOIN ' . $this->_tbl_tour_journey_type . ' as ji ON ji.tour_id = p.ID';
            $where .= ' AND ji.journey_type_id = ' . intval($journey_type_id);
        }
        elseif (!empty($params['journey_type_id'])) {
            $join .= ' INNER JOIN ' . $this->_tbl_tour_journey_type . ' as ji ON ji.tour_id = p.ID';
            $where .= ' AND ji.journey_type_id = ' . intval($params['journey_type_id']);
        }

        if (empty($params['post_type'])) {
            $where .= ' AND p.post_type IN ("addon","tour")';
        }
        else {
            $where .= ' AND p.post_type = ' . $params['post_type'];
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS p.ID, p.post_title, p.post_name, p.post_excerpt, p.post_date, p.post_author, p.post_status, p.comment_count, p.post_type,p.post_content FROM " . $this->_wpdb->posts . " as p
            $join
            WHERE p.post_status='publish'
            $where          
            ORDER BY $order_by";

        if (!empty($params['is_paging'])) {
            $query .= " LIMIT $to, $limit";
        }

        //echo $query;

        $list = $this->_wpdb->get_results($query);
        $total = $this->_wpdb->get_var("SELECT FOUND_ROWS() as total");
        if (!empty($list)) {
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


        if ($object->post_type == 'tour') {
            // Merge tour info
            $query = 'SELECT * FROM ' . $this->_tbl_tour_info . ' WHERE object_id = ' . $object->ID;
            $post_info = $this->_wpdb->get_row($query);
            $object = (object)array_merge((array)$object, (array)$post_info);

            // Default value
            $post_info->length = valueOrNull($post_info->length, 0);
            $post_info->twin_share_price = valueOrNull($post_info->twin_share_price, 0);
            $post_info->single_price = valueOrNull($post_info->single_price, 0);
        }


        if ($object->post_type == 'addon') {
            $addon_option = $this->getAddonOptions($object->ID);
            $object->addon_option = $addon_option;
            $object->type = 'addon';
        }


        return $object;
    }


    public function getAddonOptions($object_id)
    {
        $query = "SELECT * FROM {$this->_tbl_addon_options} WHERE object_id = $object_id";
        $result = $this->_wpdb->get_results($query);

        return $result;
    }


    public function getCartAddon($cart_id, $object_id = 0, $addon_option_id = 0, $type = '', $status = '')
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
            if (!empty($status)) {
                $query .= " AND status = '{$status}'";
            }

            $result = $this->_wpdb->get_results($query);
        }
        else {
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
            }
            elseif ($current_status == 'inactive') {
                $next_status = 'active';
            }
            else {
                // Cart addon null
                return [
                    'status' => 'fail',
                    'data'   => ['Please select addon services to add.']
                ];
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
        }
        else {
            return false;
        }
    }


    public function saveAddon($data)
    {
        $cart_id = $data['cart_id'];
        $object_id = $data['object_id'];
        $action_type = $data['action_type'];
        $addon_type = $data['addon_type'];
        $twin_single = '';
        $tour_addon = '';

        // Process addon type
        if ($addon_type == 'addon') {
            $tour_addon = $addon_type;
        }
        else {
            $arr = explode('-', $addon_type);
            if (!empty($arr)) {
                $twin_single = $arr[0];
                $tour_addon = $arr[1];
            }
            else {
                return false;
            }
        }

        // Quantity
        if ($twin_single == 'twin') {
            $quantity = 2;
        }
        else {
            $quantity = 1;
        }

        // Price
        $price = $this->getAddonPrice([
            'object_id'       => $data['object_id'],
            'addon_option_id' => $data['addon_option_id'],
            'twin_single'     => $twin_single
        ]);

        $query = "SELECT * FROM {$this->_tbl_cart_addon} WHERE cart_id = {$cart_id} AND object_id = {$object_id}";
        if (!empty($data['addon_option_id'])) {
            $query .= " AND addon_option_id = {$data['addon_option_id']}";
        }
        elseif (!empty($twin_single)) {
            $query .= " AND type = '{$twin_single}'";
        }

        $cart_addon = $this->_wpdb->get_row($query);

        if (empty($cart_addon)) {

            // Create cart if action is plus not minus
            if ($action_type == 'plus') {

                // Cart addon data
                $cart_addon = [
                    'status'    => $data['addon_status'],
                    'cart_id'   => $data['cart_id'],
                    'object_id' => $data['object_id'],
                    'price'     => $price,
                    'quantity'  => $quantity,
                    'total'     => $price * $quantity
                ];

                // Addon Option Id for 'addon'
                if (!empty($data['addon_option_id'])) {
                    $cart_addon['addon_option_id'] = $data['addon_option_id'];
                    $cart_addon['type'] = 'addon';
                }

                // Type for 'tour'
                if (!empty($twin_single)) {
                    $cart_addon['type'] = $twin_single;
                }

                $this->_wpdb->insert($this->_tbl_cart_addon, $cart_addon);
            }
            else {
                // return for frontend
                $cart_addon = [
                    'status'    => $data['addon_status'],
                    'cart_id'   => $data['cart_id'],
                    'object_id' => $data['object_id'],
                    'quantity'  => 0,
                    'total'     => 0
                ];
            }

        }
        else {

            // Update cart addon
            switch ($action_type) {
                case 'minus':
                    $quantity_update = $cart_addon->quantity - $quantity;
                    if ($quantity_update <= 0) {
                        $quantity_update = 0;
                        $this->_wpdb->delete($this->_tbl_cart_addon, ['id' => $cart_addon->id]);
                    }
                    else {
                        $this->_wpdb->update($this->_tbl_cart_addon, [
                            'quantity' => $quantity_update,
                            'price'    => $price,
                            'total'    => $price * $quantity_update
                        ], ['id' => $cart_addon->id]);
                    }
                    break;
                case 'plus':
                    $quantity_update = $cart_addon->quantity + $quantity;

                    $this->_wpdb->update($this->_tbl_cart_addon, [
                        'quantity' => $quantity_update,
                        'price'    => $price,
                        'total'    => $price * $quantity_update
                    ], ['id' => $cart_addon->id]);
                    break;
                default:
                    $quantity_update = $cart_addon->quantity;
                    break;
            }

            $cart_addon->quantity = $quantity_update;
            $cart_addon->total = $price * $quantity_update;
        }

        $cart = $this->_wpdb->get_row("SELECT * FROM {$this->_prefix}cart WHERE id = {$cart_id}");
        $booking = Booking::init();
        $cart_addon->cart_total = number_format($booking->getCartTotal($cart->user_id, $cart->journey_id, true));

        return $cart_addon;
    }


    /**
     * Get Addon Price
     *
     * @param array $params object_id, addon_option_id, twin_single
     * @return int|null
     */
    public function getAddonPrice($params = [])
    {
        $query = "SELECT * FROM {$this->_tbl_tour_info} t";

        // Addon hoặc Tour
        if (!empty($params['addon_option_id'])) {
            $query .= " INNER JOIN {$this->_tbl_addon_options} ao ON t.object_id = ao.object_id WHERE ao.id = {$params['addon_option_id']}";
            $result = $this->_wpdb->get_row($query);
            $price = valueOrNull($result->option_price, 0);
        }
        elseif (!empty($params['twin_single'])) {
            $query .= " WHERE t.object_id = {$params['object_id']}";
            $result = $this->_wpdb->get_row($query);

            if ($params['twin_single'] == 'twin') {
                $price = valueOrNull($result->twin_share_price, 0);
            }
            else {
                $price = valueOrNull($result->single_price, 0);
            }
        }
        else {
            $price = 0;
        }

        return $price;
    }


    public function delete($data)
    {
        $result = false;
        if ($data['object_id']) {
            $result = $this->_wpdb->delete($this->_tbl_addon_options, ['object_id' => $data['object_id']]);
        }

        return $result;
    }


    public function getAddonInfo($addon_id, $addon_option_id = 0)
    {
        $result = [];

        $query = "SELECT * FROM {$this->_wpdb->posts} p INNER JOIN {$this->_tbl_tour_info} ti ON p.ID = ti.object_id WHERE ti.object_id = {$addon_id}";
        $object = $this->_wpdb->get_row($query);
        $result['name'] = $object->post_title;

        if ($object->type == 'addon' && !empty($addon_option_id)) {
            $query = "SELECT option_name FROM {$this->_tbl_addon_options} WHERE object_id = {$addon_id} AND id = {$addon_option_id}";
            $result['extra_name'] = $this->_wpdb->get_var($query);
        }
        elseif ($object->type == 'pre-tour') {
            $result['extra_name'] = 'Pre-tour';
        }
        elseif ($object->type == 'post-tour') {
            $result['extra_name'] = 'Post-tour';
        }

        return (object)$result;
    }

}
