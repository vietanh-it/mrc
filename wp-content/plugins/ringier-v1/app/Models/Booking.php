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
    private $_tbl_transactions;


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
        $this->_tbl_transactions = $this->_prefix . 'transactions';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Booking();
        }

        return self::$instance;
    }


    public function getBookingIcon($type)
    {
        $icon = '';
        switch ($type) {
            case 'twin':
                $icon = "<img class='icon-booking' style='position: absolute; width: auto; height: auto; top: 50%; left: 50%; margin-top: -14px; margin-left: -18px;' src='" . VIEW_URL . "/images/icon-booking-twin.png'>";
                break;
            case 'single':
                $icon = "<img class='icon-booking' style='position: absolute; width: auto; height: auto; top: 50%; left: 50%; margin-top: -14px; margin-left: -13px;' src='" . VIEW_URL . "/images/icon-booking-single.png'>";
                break;
            default:
                break;
        }
        return $icon;
    }


    public function getBookingDetail($booking_id)
    {
        // Cart
        $query1 = "SELECT p.ID, c.* FROM {$this->_wpdb->posts} p INNER JOIN {$this->_tbl_cart} c ON p.ID = c.id WHERE p.ID = {$booking_id}";
        $result = $this->_wpdb->get_row($query1);

        // Cart detail
        $query2 = "SELECT * FROM {$this->_tbl_cart_detail} WHERE cart_id = {$result->ID}";
        $result->cart_detail = $this->_wpdb->get_results($query2);

        // Cart addon
        $query3 = "SELECT * FROM {$this->_tbl_cart_addon} WHERE cart_id = {$result->ID} AND status = 'active'";
        $result->cart_addon = $this->_wpdb->get_results($query3);

        // Guests

        return $result;
    }


    public function getBookingLists($user_id)
    {
        $query = "SELECT * FROM {$this->_tbl_cart} WHERE user_id = {$user_id}";
        $result = $this->_wpdb->get_results($query);

        return $result;
    }


    public function getCart($user_id, $journey_id)
    {
        // $query = "SELECT * FROM {$this->_tbl_cart} WHERE user_id = {$user_id} AND journey_id = {$journey_id} AND status = 'cart'";
        $query = "SELECT * FROM {$this->_wpdb->posts} p INNER JOIN {$this->_tbl_cart} c ON p.ID = c.id" .
            " WHERE p.post_status <> 'trash' AND c.user_id = {$user_id} AND c.journey_id = {$journey_id} AND c.status = 'cart'";
        $cart = $this->_wpdb->get_row($query);

        // Nếu chưa có cart
        if (empty($cart)) {
            $cart = $this->getDefaultCart($user_id, $journey_id);
        }

        return $cart;
    }


    public function getDefaultCart($user_id, $journey_id)
    {
        $modelUser = Users::init();
        $user = $modelUser->getUserInfo($user_id);
        $code = $this->generateBookingCode();

        // Create post
        $post_id = wp_insert_post([
            'post_title' => '#' . $code,
            'post_name'  => $code,
            'post_type'  => 'booking'
        ]);

        $cart = [
            'id'           => $post_id,
            'user_id'      => $user_id,
            'journey_id'   => $journey_id,
            'booking_code' => $code,
            'status'       => 'cart',
            'created_at'   => current_time('mysql'),
            'updated_at'   => current_time('mysql')
        ];
        $this->_wpdb->insert($this->_tbl_cart, $cart);

        return $cart;
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

        // Get cart or create
        $cart = $this->getCart($user_id, $data['journey_id']);

        // Get cart items
        $cart_item = $this->getCartDetail($cart->id, $data['room_id']);

        // Get room info
        $journey_model = Journey::init();
        $room_info = $journey_model->getRoomInfo($data['room_id']);

        if ($data['type'] == 'none') {
            $this->_wpdb->delete($this->_tbl_cart_detail, ['id' => $cart_item->id]);
            $cart = $this->getCart($user_id, $data['journey_id']);

            $cart->type = 'none';
            $cart->booking_icon = '';
        }
        else {
            $quantity = $data['type'] == 'twin' ? 2 : 1;
            $room_price = $journey_model->getRoomPrice($data['room_id'], $data['journey_id'], $data['type']);

            if (!empty($cart_item)) {
                // update cart item
                $this->_wpdb->update($this->_tbl_cart_detail, [
                    'type'     => $data['type'],
                    'price'    => $room_price,
                    'total'    => $quantity * $room_price,
                    'quantity' => $quantity
                ], ['id' => $cart_item->id]);
            }
            else {
                // Create cart item
                $this->_wpdb->insert($this->_tbl_cart_detail, [
                    'cart_id'      => $cart->id,
                    'room_id'      => $room_info->room_id,
                    'room_type_id' => $room_info->room_type_id,
                    'type'         => $data['type'],
                    'price'        => $room_price,
                    'total'        => $quantity * $room_price,
                    'quantity'     => $quantity
                ]);
            }
            $cart = $this->getCart($user_id, $data['journey_id']);
            $cart->type = $data['type'];
            $cart->booking_icon = $this->getBookingIcon($data['type']);
        }

        $room_type_count = $this->getRoomTypeBookingCount($cart->id, $room_info->room_type_id);

        $cart->room_type_count = $room_type_count;
        $cart->room_id = $room_info->room_id;
        $cart->room_info = $room_info;
        $cart->booking_total = valueOrNull($this->getCartTotal($user_id, $data['journey_id']), 0);
        $cart->booking_total_text = number_format($cart->booking_total);
        $cart->stateroom_booking_total = valueOrNull($this->getCartTotal($user_id, $data['journey_id'], false), 0);
        $cart->stateroom_booking_total_text = number_format($cart->stateroom_booking_total);

        return $cart;
    }


    public function getCartItems($user_id, $journey_id, $status = 'cart')
    {
        $select = "c.id as cart_id, c.journey_id, c.user_id, c.created_at, cd.id as cart_item_id, cd.room_id, cd.type, cd.price, cd.quantity, cd.total";
        $query = "SELECT {$select} FROM {$this->_tbl_cart} c LEFT JOIN {$this->_tbl_cart_detail} cd ON c.id = cd.cart_id WHERE c.user_id = {$user_id} AND c.journey_id = {$journey_id} AND c.status = '{$status}'";
        $cart = $this->_wpdb->get_results($query);
        if (empty($cart)) {
            $cart = $this->getDefaultCart($user_id, $journey_id);
        }

        return $cart;
    }


    public function getCartTotalPeople($cart_id)
    {
        $query = "SELECT COUNT(quantity) FROM {$this->_tbl_cart_detail} WHERE cart_id = {$cart_id}";

        return $this->_wpdb->get_var($query);
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
        $cart_info = $this->getCart($user_id, $journey_id);

        $cart = $this->getCartItems($user_id, $journey_id);
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
                    }
                    elseif ($item->type == 'single') {
                        $room_type_single_count[$item->room_type_id] = valueOrNull($room_type_single_count[$item->room_type_id],
                            0);
                        $room_type_single_count[$item->room_type_id] += 1;
                    }
                }
            }
        }

        $total_twin_guests = array_sum($room_type_twin_count);
        $total_single_guests = array_sum($room_type_single_count);

        $total = $this->getCartTotal($user_id, $journey_id, true);
        $stateroom_total = $this->getCartTotal($user_id, $journey_id, false);

        // Cart addon
        $m_addon = Addon::init();
        if (!empty($cart_info->id)) {
            $cart_addon = $m_addon->getCartAddon($cart_info->id, 0, 0, '', 'active');
        }
        else {
            $cart_addon = null;
        }

        return [
            'cart'                   => $cart,
            'cart_info'              => $cart_info,
            'cart_addon'             => $cart_addon,
            'room_type_twin_count'   => $room_type_twin_count,
            'room_type_single_count' => $room_type_single_count,
            'total_twin'             => $total_twin_guests,
            'total_single'           => $total_single_guests,
            'total'                  => $total,
            'total_text'             => number_format($total),
            'stateroom_total'        => $stateroom_total,
            'stateroom_total_text'   => number_format($stateroom_total)
        ];
    }


    public function getCartTotal($user_id, $journey_id, $with_addon = true)
    {
        // Cart id
        $query = "SELECT id FROM {$this->_tbl_cart} WHERE user_id = {$user_id} AND journey_id = {$journey_id} AND status = 'cart'";
        $cart_id = $this->_wpdb->get_var($query);


        // Total = 0 if cart is empty
        if (empty($cart_id)) {
            return 0;
        }


        // Room total
        $query = "SELECT SUM(total) FROM {$this->_tbl_cart} c LEFT JOIN {$this->_tbl_cart_detail} cd ON c.id = cd.cart_id WHERE c.user_id = {$user_id} AND c.journey_id = {$journey_id} AND c.status = 'cart'";
        $room_total = $this->_wpdb->get_var($query);


        // Addon total
        $addon_total = 0;
        if ($with_addon) {
            // Addon total
            $query = "SELECT SUM(total) FROM {$this->_tbl_cart_addon} WHERE cart_id = {$cart_id} AND status = 'active'";
            $addon_total = $this->_wpdb->get_var($query);
        }


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


    public function getCartDetail($cart_id, $room_id)
    {
        $query = "SELECT * FROM {$this->_tbl_cart_detail} WHERE cart_id = {$cart_id} AND room_id = {$room_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function getBookedRoom($journey_id)
    {
        $query_cart = "SELECT * FROM {$this->_tbl_cart} c" .
            " INNER JOIN {$this->_tbl_cart_detail} cd ON c.id = cd.cart_id" .
            " WHERE c.journey_id = {$journey_id} AND c.status IN ('before-you-go', 'ready-to-onboard', 'onboard', 'finished')";
        $cart = $this->_wpdb->get_results($query_cart);

        $result = [];
        if (!empty($cart)) {
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


    public function getRoomTypeBookingCount($cart_id, $room_type_id)
    {
        $query = "SELECT type, COUNT(room_type_id) as quantity FROM {$this->_tbl_cart_detail} WHERE cart_id = {$cart_id} AND room_type_id = {$room_type_id} GROUP BY type";
        $rs = $this->_wpdb->get_results($query);

        $result = [
            'twin'   => 0,
            'single' => 0
        ];
        if (!empty($rs)) {
            foreach ($rs as $key => $item) {
                $result[$item->type] = $item->quantity;
            }
        }

        $result['twin'] = $result['twin'] * 2;

        return $result;
    }


    public function saveAdditionalInformation($cart_id, $additional_information, $billing_address)
    {
        $this->_wpdb->update($this->_tbl_cart, [
            'additional_information' => $additional_information,
            'billing_address'        => $billing_address
        ], ['id' => $cart_id]);

        return [
            'status' => 'success',
            'data'   => true
        ];
    }


    public function finishBooking($user_id, $journey_id, $params)
    {
        $result = [
            'status' => 'fail',
            'data'   => ''
        ];

        // Transaction
        $params['user_id'] = $user_id;
        $params['total'] = $this->getCartTotal($user_id, $journey_id);
        $params['created_at'] = current_time('mysql');
        $save_transaction = $this->saveTransaction($params);

        if ($save_transaction) {
            $cart = $this->getCart($user_id, $journey_id);

            if (!empty($cart)) {
                $code = $this->generateBookingCode();

                // Update cart booking_code, status
                $this->_wpdb->update($this->_tbl_cart, [
                    'status'       => 'before-you-go',
                    'booking_code' => $code,
                    'booked_date'  => current_time('mysql')
                ], ['id' => $cart->id]);

                // Update post
                wp_update_post([
                    'ID'          => $cart->id,
                    'post_title'  => '#' . $code,
                    'post_status' => 'publish'
                ]);

                // Send email
                $subject = 'Booking confirmation, booking ID #' . $code;
                $html_path = 'normal_user/booking_confirmation.html';
                $email_args = [
                    'first_name'         => 'Việt Anh',
                    'booking_detail_url' => WP_SITEURL . '/your-booking'
                ];

                sendEmailHTML('vietanh@ringier.com.vn', $subject, $html_path, $email_args);

                $result = [
                    'status' => 'success',
                    'data'   => ''
                ];
            }
        }

        return $result;
    }


    public function saveTransaction($params)
    {
        $result = false;
        if (!empty($params['vpc_TransactionNo'])) {
            $query = "SELECT * FROM {$this->_tbl_transactions} WHERE vpc_TransactionNo = '{$params['vpc_TransactionNo']}'";
            if (empty($this->_wpdb->get_row($query))) {
                if ($params['vpc_TxnResponseCode'] == 0) {
                    // Transaction successful
                    $result = $this->_wpdb->insert($this->_tbl_transactions, $params);
                }
            }
        }

        return $result;
    }


    public function randomCode($prefix = '', $length = 7)
    {
        $code = '';
        $total = 0;
        do {
            $code .= rand(0, 9);
            $total++;
        } while ($total < $length);

        return $prefix . $code;
    }


    public function generateBookingCode($prefix = '')
    {
        do {
            $code = $this->randomCode($prefix);
            $query = "SELECT * FROM {$this->_tbl_cart} WHERE booking_code = '{$code}'";
            $result = $this->_wpdb->get_row($query);
        } while (!empty($result));

        return $code;
    }

}
