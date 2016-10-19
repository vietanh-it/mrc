<?php
namespace RVN\Controllers;

use RVN\Models\Addon;
use RVN\Models\Booking;
use RVN\Models\Location;
use RVN\Models\TaTo;

class BookingController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

        add_action("wp_ajax_ajax_handler_booking", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_booking", [$this, "ajaxHandler"]);
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new BookingController();
        }

        return self::$instance;
    }


    public function ajaxSaveCart($data)
    {
        $this->validate->rule('required', ['journey_id', 'room_id', 'type']);

        if ($this->validate->validate()) {
            $model = Booking::init();
            $rs = $model->saveCart($data);
            if (!empty($rs)) {
                $result = [
                    'status' => 'success',
                    'data'   => $rs
                ];
            }
        }
        else {
            $result = [
                'status' => 'fail',
                'data'   => ['Error occurred, please try again.']
            ];
        }

        return valueOrNull($result, []);
    }


    public function ajaxSwitchAddonStatus($data)
    {
        $addon_model = Addon::init();
        $rs = $addon_model->switchAddonStatus($data['cart_id'], $data['object_id']);

        if (!empty($rs)) {
            if (!is_array($rs)) {
                $result = [
                    'status' => 'success',
                    'data'   => $rs
                ];
            }
            else {
                $result = $rs;
            }
        }
        else {
            $result = [
                'status' => 'fail',
                'data'   => [$rs]
            ];
        }

        return $result;
    }


    public function ajaxSaveAddon($data)
    {
        $this->validate->rule('required', ['addon_type', 'action_type', 'object_id']);

        if ($this->validate->validate()) {
            $model = Addon::init();
            $rs = $model->saveAddon($data);
            if (!empty($rs)) {
                $result = [
                    'status' => 'success',
                    'data'   => $rs
                ];
            }
        }
        else {
            $result = [
                'status' => 'fail',
                'data'   => ['Error occurred, please try again.']
            ];
        }

        return valueOrNull($result, []);
    }


    public function ajaxCheckCartEmpty($data)
    {
        $model = Booking::init();
        $rs = $model->getCartInfo($data['user_id'], $data['journey_id']);

        if (!empty($rs['total'])) {
            return [
                'status' => 'success',
                'data'   => $rs
            ];
        }
        else {
            return [
                'status' => 'fail',
                'data'   => ['Please select room to continue.']
            ];
        }
    }


    public function ajaxGetCart($data)
    {
        $model = Booking::init();
        $rs = $model->getCartInfo($data['user_id'], $data['journey_id']);

        return [
            'status' => 'success',
            'data'   => $rs
        ];
    }


    public function ajaxSaveAdditionalInformation($data)
    {
        $model = Booking::init();
        $rs = $model->saveAdditionalInformation($data['cart_id'], $data['additional_information'],
            $data['billing_address']);

        return [
            'status' => 'success',
            'data'   => $rs
        ];
    }


    public function ajaxGetRoomBookingInfo($args)
    {
        $m_booking = Booking::init();
        $rs = $m_booking->getRoomBookingInfo($args);

        return [
            'status' => 'success',
            'data'   => $rs
        ];
    }


    public function ajaxGetTaToInfo($args)
    {
        $m_tato = TaTo::init();
        $rs = $m_tato->getTaToByID($args['tato_id']);

        return [
            'status' => 'success',
            'data'   => $rs
        ];
    }


    public function getCartItems($user_id, $journey_id)
    {
        $model = Booking::init();
        $result = $model->getCartItems($user_id, $journey_id);

        return $result;
    }


    public function getBookedRoom($journey_id)
    {
        $model = Booking::init();
        $result = $model->getBookedRoom($journey_id);

        return $result;
    }


    public function isRoomBooked($journey_id, $room_id)
    {
        $model = Booking::init();
        $result = $model->isRoomBooked($journey_id, $room_id);

        return $result;
    }


    public function bookingAddOn()
    {

        view('booking/booking_addon');
    }


    public function bookingReview()
    {

        view('booking/review');
    }


    public function bookingPaymentReturn()
    {
        view('booking/return');
    }


    public function yourBooking()
    {
        view('booking/your_booking');
    }


    public function bookingDetail()
    {
        view('booking/booking_detail');
    }


    public function tato()
    {
        view('booking/tato');
    }

    public function beforeYouGo($booking_id)
    {
        $objBooking = Booking::init();

        if ($_POST) {
            if (!empty($_POST['first_name']) && is_array($_POST['first_name'])) {
                $a = [];

                $objBooking->deleteGuestAddonByBookingId($booking_id);
                foreach ($_POST['guest_id'] as $m => $n) {
                    $a = [
                        'id'                                 => $n,
                        'first_name'                         => $_POST['first_name'][$m],
                        'last_name'                          => $_POST['last_name'][$m],
                        'middle_name'                        => $_POST['middle_name'][$m],
                        'nickname'                           => $_POST['nickname'][$m],
                        'gender'                             => $_POST['gender'][$m],
                        'birthday'                           => !empty($_POST['birthday'][$m]) ? date('Y-m-d', strtotime($_POST['birthday'][$m])) : '',
                        'country'                            => $_POST['country'][$m],
                        'nationality'                        => $_POST['nationality'][$m],
                        'passport_no'                        => $_POST['passport_no'][$m],
                        'passport_issue_date'                => !empty($_POST['passport_issue_date'][$m]) ? date('Y-m-d', strtotime($_POST['passport_issue_date'][$m])) : '',
                        'passport_expiration_date'           => !empty($_POST['passport_expiration_date'][$m]) ? date('Y-m-d', strtotime($_POST['passport_expiration_date'][$m])) : '',
                        'country_of_birth'                   => $_POST['country_of_birth'][$m],
                        'issued_in'                          => $_POST['issued_in'][$m],
                        'is_visa'                            => $_POST['is_visa'][$m],
                        'travel_insurance'                   => $_POST['travel_insurance'][$m],
                        'occasion_note'                      => $_POST['occasion_note'][$m],
                        'diet_note'                          => $_POST['diet_note'][$m],
                        'medical_note'                       => $_POST['medical_note'][$m],
                        'speacial_assistant_note'            => $_POST['speacial_assistant_note'][$m],
                        'room_no'                            => $_POST['room_no'][$m],
                        'bedding_note'                       => $_POST['bedding_note'][$m],
                        'embarkation_date'                   => !empty($_POST['embarkation_date'][$m]) ? date('Y-m-d', strtotime($_POST['embarkation_date'][$m])) : '',
                        'embarkation_city'                   => $_POST['embarkation_city'][$m],
                        'last_inbound_flight_no'             => $_POST['last_inbound_flight_no'][$m],
                        'last_inbound_originating_airport'   => $_POST['last_inbound_originating_airport'][$m],
                        'last_inbound_date'                  => !empty($_POST['last_inbound_date'][$m]) ? date('Y-m-d', strtotime($_POST['last_inbound_date'][$m])) : '',
                        'last_inbound_arrival_time'          => $_POST['last_inbound_arrival_time'][$m],
                        'debarkation_date'                   => !empty($_POST['debarkation_date'][$m]) ? date('Y-m-d', strtotime($_POST['debarkation_date'][$m])) : '',
                        'debarkation_city'                   => $_POST['debarkation_city'][$m],
                        'first_outbound_flight_no'           => $_POST['first_outbound_flight_no'][$m],
                        'first_outbound_destination_airport' => $_POST['first_outbound_destination_airport'][$m],
                        'first_outbound_date'                => !empty($_POST['first_outbound_date'][$m]) ? date('Y-m-d', strtotime($_POST['first_outbound_date'][$m])) : '',
                        'first_outbound_departure_time'      => !empty($_POST['first_outbound_departure_time'][$m]) ? $_POST['first_outbound_departure_time'][$m] : '',
                        'user_id'                            => get_current_user_id(),
                        'updated_at'                         => current_time('mysql'),
                        'booking_id'                         => $booking_id,
                    ];


                    if (!empty($a['id'])) {
                        $guest_id = $a['id'];
                        $kq = $objBooking->updateGuest($a);
                    }
                    else {
                        $kq = $objBooking->insertGuest($a);
                        $guest_id = $kq;
                    }

                    $addon_id = $_POST['service_addon'][$m];
                    $objBooking->insertGuestAddon([
                        'guest_id'   => $guest_id,
                        'addon_id'   => $addon_id,
                        'booking_id' => $booking_id,
                    ]);

                    if (!$kq) {
                        $return = [
                            'status'  => 'error',
                            'message' => 'An error, please try again.'
                        ];
                    }
                    else {
                        $return = [
                            'status'  => 'success',
                            'message' => 'Save info success.'
                        ];
                    }

                }

            }
        }

        $booking_code = '';
        $total_guest = 1;
        if (empty($booking_id)) {
            $return = [
                'status'  => 'error',
                'message' => 'An error, please check again.'
            ];
        }
        else {
            $booking_detail = $objBooking->getBookingDetail($booking_id);
            $total_guest = $objBooking->getCartTotalPeople($booking_id);
            if ($booking_detail) {
                $booking_code = $booking_detail->booking_code;
            }
            $list_guest = $objBooking->getGuestByBookingId($booking_id);
        }

        return view('booking/before-you-go', compact('list_guest', 'country_list', 'return', 'list_service_addon', 'total_guest', 'booking_code'));
    }
    
}
