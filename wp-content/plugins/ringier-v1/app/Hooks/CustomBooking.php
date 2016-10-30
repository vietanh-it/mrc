<?php
namespace RVN\Hooks;

use RVN\Controllers\ShipController;
use RVN\Models\Booking;
use RVN\Models\JourneyType;
use RVN\Models\Posts;
use RVN\Models\Ships;

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
        add_meta_box('switch_booking', 'Change Booking Status', [$this, 'show'], 'booking', 'normal', 'high');

    }


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

                if ($_POST['booking_status'] == 'finished') {
                    $booking_detail = $m_booking->getBookingDetail($post->ID);
                    $user = get_user_by('ID', $booking_detail->user_id);

                    $args = [
                        'first_name' => $user->data->display_name,
                        'review_url' => 'https://www.tripadvisor.com/'
                    ];
                    sendEmailHTML($user->data->user_email, 'Thank you for going with us',
                        'normal_user/thank_you_email.html', $args);
                }

            }
        }

    }


}
