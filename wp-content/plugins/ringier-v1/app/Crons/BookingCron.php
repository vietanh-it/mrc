<?php
namespace RVN\Crons;

use RVN\Models\Journey;

class BookingCron
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new BookingCron();
        }

        return self::$instance;
    }


    protected function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        add_action('booking_drop_reminder', [$this, 'bookingDropReminder']);
    }

    public function bookingDropReminder($cart_id = 0)
    {
        if (!empty($cart_id)) {

            // Send booking drop email reminder if 30 minutes passed and booking status is still 'cart'
            $query = "SELECT * FROM " . TBL_CART . " WHERE id = '{$cart_id}'";
            $cart = $this->_wpdb->get_row($query);

            if (!empty($cart)) {
                if ($cart->status == 'cart') {
                    // get journey
                    $m_journey = Journey::init();
                    $journey_info = $m_journey->getInfo($cart->journey_id);

                    // get user
                    $user_data = get_userdata($cart->user_id);

                    $args = [
                        'first_name'             => $user_data->data->display_name,
                        'journey_url'            => $journey_info->permalink,
                        'journey_departure_date' => date('d/m/Y', strtotime($journey_info->departure))
                    ];
                    // Send email
                    sendEmailHTML($user_data->data->user_email, 'Uncompleted booking, Reservation ID #' . $cart->booking_code, 'normal_user/booking_drop_reminder.html', $args);
                }
            }
        }

    }
}