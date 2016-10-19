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
    private $_tbl_guest;
    private $_tbl_guest_addon;


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
        $this->_tbl_guest = $this->_prefix . 'guests';
        $this->_tbl_guest_addon = $this->_prefix . 'guest_addon';
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

        if (!empty($result)) {
            // Cart detail
            $query2 = "SELECT * FROM {$this->_tbl_cart_detail} WHERE cart_id = {$result->ID}";
            $result->cart_detail = $this->_wpdb->get_results($query2);

            // Cart addon
            $query3 = "SELECT * FROM {$this->_tbl_cart_addon} WHERE cart_id = {$result->ID} AND status = 'active'";
            $result->cart_addon = $this->_wpdb->get_results($query3);
        }

        // Guests

        return $result;
    }


    public function getBookingLists($user_id, $is_get_cart = true)
    {
        $query = "SELECT * FROM {$this->_tbl_cart} WHERE user_id = {$user_id}";
        if (empty($is_get_cart)) {
            $query .= " AND status NOT IN ('cart', 'tato')";
        }
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
        global $post, $wpdb;
        $m_journey = Journey::init();
        $journey = $m_journey->getJourneyInfoByID($journey_id);
        $code = $this->generateBookingCode($journey->journey_code);

        $wpdb->update($wpdb->posts, [
            'post_title'  => $code,
            'post_status' => 'publish',
            'post_name'   => sanitize_title($code)
        ], ['ID' => $post->ID]);

        $cart = [
            'id'           => $post->ID,
            'user_id'      => $user_id,
            'journey_id'   => $journey_id,
            'booking_code' => $code,
            'status'       => 'cart',
            'created_at'   => current_time('mysql'),
            'updated_at'   => current_time('mysql')
        ];
        $this->_wpdb->insert($this->_tbl_cart, $cart);

        return (object)$cart;
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
        $query = "SELECT SUM(quantity) FROM {$this->_tbl_cart_detail} WHERE cart_id = {$cart_id}";
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
            " WHERE c.journey_id = {$journey_id} AND c.status IN ('before-you-go', 'ready-to-onboard', 'onboard', 'finished', 'tato')";
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
        if (!empty($params['q'])) {
            unset($params['q']);
        }
        $save_transaction = $this->saveTransaction($params);

        if ($save_transaction) {
            $cart = $this->getCart($user_id, $journey_id);

            if (!empty($cart)) {
                // Update cart booking_code, status
                $this->_wpdb->update($this->_tbl_cart, [
                    'status'      => 'before-you-go',
                    'booked_date' => current_time('mysql')
                ], ['id' => $cart->id]);

                // Update post
                wp_update_post([
                    'ID'          => $cart->id,
                    'post_status' => 'publish'
                ]);

                // Send email
                $subject = 'Booking confirmation, booking ID #' . $cart->booking_code;
                $html_path = 'normal_user/booking_confirmation.html';
                $email_args = [
                    'first_name'         => 'Việt Anh',
                    'booking_detail_url' => WP_SITEURL . '/before-you-go/?id=' . $cart->id
                ];

                $user = wp_get_current_user();

                sendEmailHTML($user->data->user_email, $subject, $html_path, $email_args);

                $result = [
                    'status' => 'success',
                    'data'   => ''
                ];

                wp_redirect(WP_SITEURL . '/booking/' . $cart->booking_code);
                exit;
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
        $i = 1;
        do {
            // $code = $this->randomCode($prefix);
            // Sample Code: MP160816-1
            $code = $prefix . date('dmy') . '-' . $i;
            $query = "SELECT * FROM {$this->_tbl_cart} WHERE booking_code = '{$code}'";
            $result = $this->_wpdb->get_row($query);

            $i++;
        } while (!empty($result));

        return $code;
    }


    public function getBookingStatusText($status_code)
    {
        switch ($status_code) {
            case 'cart':
                $result = 'Booking';
                break;
            case 'tato':
                $result = 'TA/TO - Wait for deposit';
                break;
            case 'before-you-go':
                $result = 'Before you go';
                break;
            case 'ready-to-onboard':
                $result = 'Ready to on-board';
                break;
            case 'onboard':
                $result = 'On-board';
                break;
            case 'finished':
                $result = 'Finished';
                break;
            case 'cancel':
                $result = 'Cancelled';
                break;
            default:
                $result = 'Booking';
                break;
        }

        return $result;
    }


    public function getCartByID($cart_id)
    {
        $query = "SELECT * FROM " . TBL_CART . " WHERE id = {$cart_id}";
        $result = $this->_wpdb->get_row($query);

        return $result;
    }


    public function switchBookingStatus($cart_id, $status)
    {
        if (!empty($cart_id)) {
            $cart = $this->getCartByID($cart_id);
        }
    }


    public function getGuestByBookingId($booking_id)
    {

        $query = 'SELECT g.*,ga.addon_id FROM ' . $this->_tbl_guest . ' as g INNER JOIN  ' . $this->_tbl_guest_addon . ' as ga ON ga.guest_id = g.id WHERE g.booking_id = ' . $booking_id;

        return $this->_wpdb->get_results($query);

    }

    public function getServiceAddonByBookingId($booking_id)
    {
        $query = 'SELECT ca.*,p.post_title as addon_name FROM ' . $this->_tbl_cart_addon . ' as ca 
        INNER JOIN  ' . $this->_wpdb->posts . ' as p ON p.ID = ca.object_id
        WHERE ca.cart_id = ' . $booking_id;

        return $this->_wpdb->get_results($query);
    }

    public function getRoomByBookingId($booking_id)
    {
        $query = 'SELECT ca.*,r.room_name FROM ' . $this->_tbl_cart_detail . ' as ca 
        INNER JOIN  ' . $this->_prefix . 'rooms as r ON r.id = ca.room_id
        WHERE ca.cart_id = ' . $booking_id;

        return $this->_wpdb->get_results($query);
    }

    public function insertGuest($data)
    {
        unset($data['id']);
        $isr = $this->_wpdb->insert($this->_tbl_guest, $data);
        if ($isr) {
            return $this->_wpdb->insert_id;
        }
        else {
            return false;
        }
    }


    public function updateGuest($data)
    {
        if (empty($data['id'])) {
            return false;
        }
        $id = $data['id'];
        unset($data['id']);
        return $this->_wpdb->update($this->_tbl_guest, $data, ['id' => $id]);
    }

    public function getGuestAddon($guest_id, $addon_id)
    {

        $query = 'SELECT * FROM ' . $this->_tbl_guest_addon . ' WHERE guest_id = ' . $guest_id . ' AND addon_id =' . $addon_id;

        return $this->_wpdb->get_row($query);

    }

    public function insertGuestAddon($data)
    {
        $isr = $this->_wpdb->insert($this->_tbl_guest_addon, $data);
        if ($isr) {
            return $this->_wpdb->insert_id;
        }
        else {
            return false;
        }
    }

    public function deleteGuestAddonByBookingId($booking_id)
    {
        return $this->_wpdb->delete($this->_tbl_guest_addon, ['booking_id' => $booking_id]);
    }

    public function getRoomBookingInfo($args)
    {
        $result = [];

        if (!empty($args['room_id'])) {
            $m_ship = Ships::init();
            $m_journey = Journey::init();

            foreach ($args['room_id'] as $k => $v) {
                $room_info = $m_ship->getRoomInfo($v);

                $item = [
                    'room_id'        => $v,
                    'room_name'      => $room_info->room_name,
                    'room_type_id'   => $room_info->room_type_id,
                    'room_type_name' => $room_info->room_type_name,
                    'twin_price'     => $m_journey->getRoomPrice($v, $args['journey_id'], 'twin'),
                    'single_price'   => $m_journey->getRoomPrice($v, $args['journey_id'], 'single')
                ];
                $result[$k] = $item;
            }
        }

        return $result;
    }
}
