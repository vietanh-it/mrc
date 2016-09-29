<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 08-Sep-16
 * Time: 11:08 PM
 */

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
    exit;
}

$modelBooking = \RVN\Models\Booking::init();
$modelJourney = \RVN\Models\Journey::init();

$user_id = get_current_user_id();
$booking_list = $modelBooking->getBookingLists($user_id);


get_header(); ?>

    <div class="container">
        <div class="row detail-your-booking">
            <h1 class="col-xs-12 col-sm-12 tile-main">Booking Detail
                <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
            </h1>

            <div class="col-xs-12 col-sm-12">
                <div class="ctn-list-journey" style="padding-top: 0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-center">Booking ID</th>
                            <th>Departure date</th>
                            <th>From - to</th>
                            <th>Journey</th>
                            <th>Ship</th>
                            <th>Travellers</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Ask for help</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($booking_list as $key => $value) {

                            $booking_permalink = get_permalink($value->id);

                            $journey_detail = $modelJourney->getInfo($value->journey_id);
                            $total = $modelBooking->getCartTotalByID($value->id);
                            $total = number_format($total);
                            $total_people = $modelBooking->getCartTotalPeople($value->id);
                            $status = $modelBooking->getBookingStatusText($value->status); ?>

                            <tr>
                                <td>
                                    <a href="<?php echo $booking_permalink; ?>" style="color: #545454;">
                                        <b>
                                            <?php echo $value->booking_code; ?>
                                        </b>
                                    </a>
                                </td>
                                <td><?php echo $journey_detail->departure_fm; ?></td>
                                <td>
                                    <?php echo $journey_detail->journey_type_info->starting_point ?>
                                    - <?php echo $journey_detail->journey_type_info->destination_info->post_title ?> <?php echo $journey_detail->journey_type_info->nights; ?>
                                    nights
                                </td>
                                <td style="text-decoration: underline">
                                    <a href="<?php echo $journey_detail->permalink; ?>"
                                       target="_blank" style="color: rgb(84, 84, 84);">
                                        <?php echo $journey_detail->journey_type_info->post_title . ' - ' . $journey_detail->post_title; ?>
                                    </a>
                                </td>
                                <td style="text-decoration: underline">
                                    <a href="<?php echo $journey_detail->journey_type_info->ship_info->permalink; ?>"
                                       target="_blank" style="color: rgb(84, 84, 84);">
                                        <?php echo $journey_detail->journey_type_info->ship_info->post_title; ?>
                                    </a>
                                </td>
                                <td style="text-align: center;">
                                    <?php echo $total_people; ?>
                                </td>
                                <td>
                                    <b style="color: black;font-size: 17px;text-transform: uppercase">US$<?php echo $total; ?></b>
                                </td>
                                <td>
                                    <span style="color: #e4a611">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td style="text-align: center">
                                    <a href="javascript:void(0)">
                                        <img src="<?php echo VIEW_URL . '/images/icon-question.png' ?>"
                                             class="img-icon">
                                    </a>
                                </td>
                            </tr>

                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 text-center" style="margin : 50px 0">
                <p style="margin-bottom: 30px;font-weight: bold">Share your feeling to inspire people on TripAdvisor</p>
                <img src="<?php echo VIEW_URL . '/images/icon-green-booking.png' ?>" style="">
            </div>
        </div>
    </div>

<?php get_footer();