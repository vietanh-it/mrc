<?php
/**
 * Created by PhpStorm.
 * User: VietAnh
 * Date: 2016-30-07
 * Time: 00:27 AM
 */
namespace RVN\Models;

class Booking
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_cart;
    private $_tbl_cart_detail;
    private $_tbl_cart_addon;
    private $_tbl_booking;
    private $_tbl_booking_detail;


    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_cart = $this->_prefix . 'cart';
        $this->_tbl_cart_detail = $this->_prefix . 'cart_detail';
        $this->_tbl_cart_addon = $this->_prefix . 'cart_addon';
        $this->_tbl_booking = $this->_prefix . 'booking';
        $this->_tbl_booking_detail = $this->_prefix . 'booking_detail';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Booking();
        }

        return self::$instance;
    }


    /** Save item to cart
     * @param $data
     * @return array|mixed|null|object
     */
    public function saveCart($data)
    {
        if (!is_user_logged_in()) {
            return [
                'status' => 'error',
                'data'   => ['Please login to book room.']
            ];
        }

        $user_id = get_current_user_id();
        $cart = $this->getCartItems($user_id, $data['journey_id']);
        $cart = array_shift($cart);
        $cart_item = $this->getCartItem($cart->cart_id, $data['room_id']);

        if ($data['type'] == 'none') {
            $this->_wpdb->delete($this->_tbl_cart_detail, ['id' => $cart_item->id]);
        } else {
            $quantity = $data['type'] == 'twin' ? 2 : 1;
            $journey_model = Journey::init();
            $room_price = $journey_model->getRoomPrice($data['room_id'], $data['journey_id'], $data['type']);

            if (!empty($cart_item)) {
                // update cart item
                $this->_wpdb->update($this->_tbl_cart_detail, [
                    'type'     => $data['type'],
                    'price'    => $room_price,
                    'total'    => $quantity * $room_price,
                    'quantity' => $quantity
                ], ['id' => $cart_item->id]);
            } else {
                // Create cart item
                $this->_wpdb->insert($this->_tbl_cart_detail, [
                    'cart_id'  => $cart->cart_id,
                    'room_id'  => $data['room_id'],
                    'type'     => $data['type'],
                    'price'    => $room_price,
                    'total'    => $quantity * $room_price,
                    'quantity' => $quantity
                ]);
            }

        }

        $cart->booking_total = valueOrNull($this->getCartTotal($user_id, $data['journey_id']), 0);
        $cart->booking_total_text = number_format($cart->booking_total);

        return $cart;
    }


    /**
     * Get or create to get object cart
     *
     * @param $user_id
     * @param $journey_id
     * @return array|null|object
     */
    public function getCartItems($user_id, $journey_id)
    {
        $query = "SELECT c.id as cart_id, c.journey_id, c.user_id, c.created_at, cd.id as cart_item_id, cd.room_id, cd.type, cd.price, cd.total FROM {$this->_tbl_cart} c LEFT JOIN {$this->_tbl_cart_detail} cd ON c.id = cd.cart_id WHERE c.user_id = {$user_id} AND c.journey_id = {$journey_id}";
        $cart = $this->_wpdb->get_results($query);
        if (empty($cart)) {
            $this->_wpdb->insert($this->_tbl_cart, [
                'user_id'    => $user_id,
                'journey_id' => $journey_id,
                'created_at' => current_time('mysql')
            ]);

            $cart = $this->_wpdb->get_results($query);
        }

        return $cart;
    }


    /**
     * Get cart info when reload booking page
     *
     * @param $user_id
     * @param $journey_id
     * @return array
     */
    public function getCartInfo($user_id, $journey_id)
    {
        $cart_info = $this->_wpdb->get_row("SELECT * FROM {$this->_tbl_cart} WHERE user_id = {$user_id} AND journey_id = {$journey_id}");

        if (empty($cart_info)) {

            // Insert if not exist
            $this->_wpdb->insert($this->_tbl_cart, [
                'user_id'    => $user_id,
                'journey_id' => $journey_id,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ]);
        }

        $query = "SELECT c.id as cart_id, c.journey_id, c.user_id, c.created_at, cd.id as cart_item_id, cd.room_id, cd.type, cd.price, cd.total FROM {$this->_tbl_cart} c LEFT JOIN {$this->_tbl_cart_detail} cd ON c.id = cd.cart_id WHERE c.user_id = {$user_id} AND c.journey_id = {$journey_id}";
        $cart = $this->_wpdb->get_results($query);
        $room_type_twin_count = [];
        $room_type_single_count = [];

        if (!empty($cart)) {
            foreach ($cart as $key => $item) {
                if ($item->room_id) {
                    $query = "SELECT room_type_id FROM {$this->_prefix}rooms WHERE id = {$item->room_id}";
                    $item->room_type_id = $this->_wpdb->get_var($query);

                    if ($item->type == 'twin') {
                        $room_type_twin_count[$item->room_type_id] = valueOrNull($room_type_twin_count[$item->room_type_id],
                            0);
                        $room_type_twin_count[$item->room_type_id] += 2;
                    } elseif ($item->type == 'single') {
                        $room_type_single_count[$item->room_type_id] = valueOrNull($room_type_single_count[$item->room_type_id],
                            0);
                        $room_type_single_count[$item->room_type_id] += 1;
                    }
                }
            }
        }

        $total_twin_guests = array_sum($room_type_twin_count);
        $total_single_guests = array_sum($room_type_single_count);

        return [
            'cart'                   => $cart,
            'cart_info'              => $cart_info,
            'room_type_twin_count'   => $room_type_twin_count,
            'room_type_single_count' => $room_type_single_count,
            'total_twin'             => $total_twin_guests,
            'total_single'           => $total_single_guests,
            'total'                  => $this->getCartTotal($user_id, $journey_id),
            'total_text'             => number_format($this->getCartTotal($user_id, $journey_id))
        ];
    }


    public function getCartTotal($user_id, $journey_id)
    {
        // Cart id
        $query = "SELECT id FROM {$this->_tbl_cart} WHERE user_id = {$user_id} AND journey_id = {$journey_id}";
        $cart_id = $this->_wpdb->get_var($query);

        // Total = 0 if cart is empty
        if (empty($cart_id)) {
            return 0;
        }

        // Room total
        $query = "SELECT SUM(total) FROM {$this->_tbl_cart} c LEFT JOIN {$this->_tbl_cart_detail} cd ON c.id = cd.cart_id WHERE c.user_id = {$user_id} AND c.journey_id = {$journey_id}";
        $room_total = $this->_wpdb->get_var($query);

        // Addon total
        $query = "SELECT SUM(total) FROM {$this->_tbl_cart_addon} WHERE cart_id = {$cart_id} AND status = 'active'";
        $addon_total = $this->_wpdb->get_var($query);

        $final_total = valueOrNull($room_total, 0) + valueOrNull($addon_total, 0);

        return $final_total;
    }


    public function getCartTotalByID($cart_id)
    {
        // Room total
        $query = "SELECT SUM(total) FROM {$this->_tbl_cart} c LEFT JOIN {$this->_tbl_cart_detail} cd ON c.id = cd.cart_id WHERE c.id = {$cart_id}";
        $room_total = $this->_wpdb->get_var($query);

        // Addon total
        $query = "SELECT SUM(total) FROM {$this->_tbl_cart_addon} WHERE cart_id = {$cart_id} AND status = 'active'";
        $addon_total = $this->_wpdb->get_var($query);

        $final_total = valueOrNull($room_total, 0) + valueOrNull($addon_total, 0);

        return $final_total;
    }


    public function getCartItem($cart_id, $room_id)
    {
        $query = "SELECT * FROM {$this->_tbl_cart_detail} WHERE cart_id = {$cart_id} AND room_id = {$room_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function getBookedRoom($journey_id)
    {
        $query_cart = "SELECT * FROM {$this->_tbl_booking} b INNER JOIN {$this->_tbl_booking_detail} bd ON b.id = bd.booking_id WHERE b.journey_id = {$journey_id}";
        $cart = $this->_wpdb->get_results($query_cart);

        $result = [];
        if ($cart) {
            foreach ($cart as $key => $item) {
                $result[] = $item->room_id;
            }
        }

        return $result;
    }


    public function isRoomBooked($journey_id, $room_id)
    {
        $booked_room = $this->getBookedRoom($journey_id);
        return in_array($room_id, $booked_room);
    }

}
