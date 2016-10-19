<?php
$booking = \RVN\Models\Booking::init();
$booking_ctrl = \RVN\Controllers\BookingController::init();

if(!empty($_GET['booking_id'])) {

    $booking_id = $_GET['booking_id'];

    global $post;
    $journey_id = $post->ID;
    $user_id = get_current_user_id();
    $booking_detail = $booking->getBookingDetail($booking_id);

    $journey_ctrl = \RVN\Controllers\JourneyController::init();
    $journey_detail = $journey_ctrl->getJourneyDetail($journey_id);


    $addon_model = \RVN\Models\Addon::init();
    $addon_list = $addon_model->getList(['journey_type_id' => $journey_detail->journey_type_info->ID]);

//var_dump($addon_list);
    get_header();
    ?>

    <div class="journey-detail">

        <?php //view('blocks/booking-topbar', ['journey_id' => $booking_detail->journey_id]);
        ?>

        <div class="content-booking">
            <div class="container container-big">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 ">
                        <p class="text-tt">
                            Check availability and book online <span>Would you like extension and service addons?</span>
                        </p>
                    </div>
                </div>

                <!--List-->
                <div class="row">

                    <?php
                    if (!empty($addon_list['data'])) {
                        view('blocks/list-addon', [
                            'list_addon' => $addon_list['data'],
                            'cart_id' => $booking_id
                        ]);
                    } ?>

                    <div class="text-center btt-box">
                        <?php $url = strtok($_SERVER["REQUEST_URI"], '?'); ?>

                        <a href="<?php echo $journey_detail->permalink; ?>" class="back">Back</a>
                        <a href="<?php echo $journey_detail->permalink . '?step=booking-review'; ?>" class="btn-main">Continue</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    get_footer();
}
?>