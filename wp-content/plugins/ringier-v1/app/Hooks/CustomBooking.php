<?php
namespace RVN\Hooks;

use RVN\Controllers\ShipController;
use RVN\Models\Addon;
use RVN\Models\Booking;
use RVN\Models\Journey;
use RVN\Models\JourneyType;
use RVN\Models\Location;
use RVN\Models\Posts;
use RVN\Models\Ships;
use RVN\Models\TaTo;
use RVN\Models\Users;

/**
 * Created by PhpStorm.
 *
 * Date: 2/9/2015
 * Time: 9:44 AM
 */
class CustomBooking
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomBooking();
        }

        return self::$instance;
    }


    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'metabox']);
        add_action('save_post', [$this, 'save']);
    }


    public function metabox()
    {
        if (empty($_GET['type'])) {
            add_meta_box('switch_booking', 'Change Booking Status', [$this, 'show'], 'booking', 'normal', 'high');
            add_meta_box('booking_info', 'Booking Information', [$this, 'showInfo'], 'booking', 'normal', 'high');
            remove_meta_box('submitdiv', 'booking', 'side');
        }
    }


    public function showInfo()
    {
        global $post;
        $m_booking = Booking::init();
        $m_user = Users::init();
        $m_location = Location::init();
        $m_ship = Ships::init();
        $m_addon = Addon::init();
        $m_journey = Journey::init();

        $booking_detail = $m_booking->getBookingDetail($post->ID);
        $user_info = $m_user->getUserInfo($booking_detail->user_id);
        $journey_info = $m_journey->getInfo($booking_detail->journey_id);
        $country = $m_location->getCountryName($user_info->country);
        ?>

        <section>
            <div class="row">
                <div class="col-xs-12">
                    <h3>Journey Info</h3>
                    <div>
                        Journey name: <b><?php echo $journey_info->post_title; ?></b> <br/><br/>
                        Departure date: <b><?php echo $journey_info->departure_fm; ?></b> <br/><br/>
                        Duration: <b><?php echo $journey_info->journey_type_info->duration; ?></b> <br/><br/>
                        Starting point: <b><?php echo $journey_info->journey_type_info->starting_point; ?></b>
                        <br/><br/>
                        Destination:
                        <b><?php echo $journey_info->journey_type_info->destination_info->post_title; ?></b> <br/><br/>
                    </div>
                </div>
            </div>

            <div class="row" style="border-top: 1px dashed #dddddd;">
                <div class="col-xs-6">
                    <h3>Customer info</h3>
                    <div class="col-xs-12">
                        Name: <b><?php echo $user_info->display_name ?></b> <br/><br/>
                        Email: <b><?php echo $user_info->user_email ?></b> <br/><br/>
                        Gender: <b><?php echo $user_info->gender ?></b> <br/><br/>
                        Address: <b><?php echo $user_info->address . ' ' . $country ?></b>
                    </div>
                </div>
                <?php if (!empty($booking_detail->is_tato)) {
                    $m_tato = TaTo::init();
                    $tato = $m_tato->getTaToByID($booking_detail->tato_id); ?>
                    <div class="col-xs-6">
                        <h3>TA/TO info</h3>
                        <div class="col-xs-12">
                            Name: <b><?php echo $tato->post_title ?></b> <br/><br/>
                            Company Name: <b><?php echo $tato->company_name ?></b> <br/><br/>
                            Company Email: <b><?php echo $tato->company_email ?></b> <br/><br/>
                            Commission Rate: <b><?php echo $tato->commission_rate ?></b> <br/><br/>
                            Mobile Phone: <b><?php echo $tato->mobile_phone ?></b>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <h3>Room Info</h3>
                    <div class="col-xs-12">
                        <?php foreach ($booking_detail->cart_detail as $k => $v) {
                            $room_info = $m_ship->getRoomInfo($v->room_id); ?>
                            <div style="border-top: 1px dashed #dddddd; padding-top: 10px;">
                                Deck plan: <b><?php echo $room_info->deck_plan; ?></b> <br/><br/>
                                Room type: <b><?php echo $room_info->room_type_name; ?></b> <br/><br/>
                                Room name: <b><?php echo $room_info->room_name; ?></b> <br/><br/>
                                Twin - Single: <b><?php echo $v->type; ?></b> <br/><br/>
                                Quantity: <b><?php echo $v->quantity; ?></b> <br/><br/>
                                Price: <b>$<?php echo number_format($v->price); ?></b> <br/><br/>
                                Total: <b>$<?php echo number_format($v->total); ?></b>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (!empty($booking_detail->cart_addon)) { ?>
                    <div class="col-xs-6">
                        <h3>Addon - Services Info</h3>
                        <div class="col-xs-12">
                            <?php foreach ($booking_detail->cart_addon as $k => $v) {
                                $addon_info = $m_addon->getAddonInfo($v->object_id, $v->addon_option_id); ?>

                                <div style="border-top: 1px dashed #dddddd; padding-top: 10px;">
                                    Addon - service name: <b><?php echo $addon_info->name; ?>
                                        - <?php echo $addon_info->extra_name; ?></b> <br/><br/>
                                    Price: <b>$<?php echo number_format($v->price); ?></b> <br/><br/>
                                    Quantity:
                                    <b><?php echo empty($v->quantity) ? intval($v->total / $v->price) : $v->quantity; ?></b>
                                    <br/><br/>
                                    Total: <b>$<?php echo number_format($v->total); ?></b> <br/><br/>
                                </div>

                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

            </div>
            <div class="row">
                <div class="col-xs-12"
                     style="border-top: 1px dashed #dddddd; padding-top: 20px; margin-top: 20px; text-align: center; padding-bottom: 20px; font-size: 20px;">
                    TOTAL: <b>$<?php echo number_format($m_booking->getCartTotalByID($booking_detail->id)); ?></b>
                </div>
            </div>
        </section>

    <?php }


    public function show()
    {
        global $post;
        $m_booking = Booking::init();

        $booking_detail = $m_booking->getBookingDetail($post->ID);
        ?>

        <style>
            .text-center {
                text-align: center;
            }

            #normal-sortables .postbox .submit {
                text-align: center;
                float: none;
                margin-top: 20px;
            }
        </style>

        <section>
            <div class="row">
                <div class="col-xs-offset-4 col-xs-4 text-center">

                    <select name="booking_status" id="booking_status" class="select2">
                        <?php if (!empty($booking_detail->is_tato)) { ?>
                            <option value="tato">TA/TO - Wait for deposit</option>
                            <option value="tato-deposited">TA/TO - Deposited</option>
                            <option value="tato-full">TA/TO - Full payment</option>
                        <?php }
                        else { ?>
                            <option value="cart">In Cart</option>
                            <option value="before-you-go">Before You Go</option>
                        <?php } ?>
                        <option value="ready-to-onboard">Ready to on-board</option>
                        <option value="on-board">On-board</option>
                        <option value="finished">Finished</option>
                        <option value="cancel">Cancel</option>
                    </select>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-offset-4 col-xs-4 text-center">
                    <?php submit_button('Change'); ?>
                </div>
            </div>
        </section>


        <script>
            var $ = jQuery.noConflict();
            jQuery(document).ready(function ($) {
                <?php if (!empty($booking_detail)) { ?>
                $('#booking_status').find('option[value="<?php echo $booking_detail->status ?>"]').attr('selected', true);
                <?php } ?>
            });

        </script>
        <?php
    }


    public function save()
    {
        global $post;

        if (!empty($post) && $post->post_type == 'booking') {
            if (!empty($_POST['booking_status'])) {
                $m_booking = Booking::init();
                $m_booking->changeBookingStatus($post->ID, $_POST['booking_status']);
                $booking_detail = $m_booking->getBookingDetail($post->ID);
                $user = get_user_by('ID', $booking_detail->user_id);

                switch ($_POST['booking_status']) {
                    case 'finished':
                        $args = [
                            'first_name' => $user->data->display_name,
                            'review_url' => 'https://www.tripadvisor.com/'
                        ];
                        sendEmailHTML($user->data->user_email, 'Thank you for going with us',
                            'normal_user/thank_you_email.html', $args);
                        break;
                    case 'tato-deposited':
                        $args = [
                            'first_name'   => $user->data->display_name,
                            'booking_code' => $booking_detail->booking_code
                        ];
                        sendEmailHTML($user->data->user_email,
                            'Deposit confirmation, booking ID #' . $booking_detail->booking_code,
                            'ta_to/deposit_confirmation_for_ta_to.html', $args);
                        break;
                    case 'tato-full':
                        $args = [
                            'first_name'   => $user->data->display_name,
                            'booking_code' => $booking_detail->booking_code
                        ];
                        sendEmailHTML($user->data->user_email,
                            'Full-payment confirmation, booking ID #' . $booking_detail->booking_code,
                            'ta_to/full_payment_confirmation_for_ta_to.html', $args);
                        break;
                }

            }
        }

    }


}
