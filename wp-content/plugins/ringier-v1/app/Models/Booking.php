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


    public function saveCart($data)
    {
        if (!is_user_logged_in()) {
            return [
                'status' => 'error',
                'data'   => ['Please login to book room.']
            ];
        }

        $user_id = get_current_user_id();
        $cart = $this->getCart($user_id);
        $cart_item = $this->getCartItem($cart->id, $data['room_id']);

        if (!empty($cart_item)) {
            $this->_wpdb->update($this->_tbl_cart_detail, [
                ''
            ]);
        }


        return $cart;
    }


    public function getCart($user_id)
    {
        $query = "SELECT * FROM {$this->_tbl_cart} c LEFT JOIN {$this->_tbl_cart_detail} cd ON c.id = cd.cart_id WHERE c.user_id = {$user_id}";
        $cart = $this->_wpdb->get_results($query);
        if (empty($cart)) {
            $this->_wpdb->insert($this->_tbl_cart, [
                'user_id'    => $user_id,
                'created_at' => current_time('mysql')
            ]);

            $cart = $this->_wpdb->get_row($query);
        }

        return $cart;
    }


    public function getCartItem($cart_id, $journey_id, $room_id)
    {
        $query = "SELECT * FROM {$this->_tbl_cart_detail} WHERE cart_id = {$cart_id} AND journey_id = {$journey_id} AND room_id = {$room_id}";
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
