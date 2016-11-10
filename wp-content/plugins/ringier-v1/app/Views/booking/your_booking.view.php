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
$booking_list = $modelBooking->getBookingLists($user_id, false);


get_header(); ?>

    <div class="container">
        <div class="row detail-your-booking">
            <h1 class="col-xs-12 col-sm-12 tile-main">Booking Detail
                <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
            </h1>

            <div class="col-xs-12 col-sm-12">
                <div class="ctn-list-journey hide-on-med-and-down" style="padding-top: 0">
                    <table class="table table-striped table-padding">
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

                        <?php
                        if (!empty($booking_list)) {
                            foreach ($booking_list as $key => $value) {

                                $booking_permalink = get_permalink($value->id);

                                $journey_detail = $modelJourney->getInfo($value->journey_id);
                                $total = $modelBooking->getCartTotalByID($value->id);

                                // Chỉ hiện booking nào có total > 0
                                if ($total > 0) {
                                    $total = number_format($total);
                                    $total_people = $modelBooking->getCartTotalPeople($value->id);
                                    $status = $modelBooking->getBookingStatusText($value->status);

                                    if ($value->status == 'cart') {
                                        // journey permalink for cart
                                        $booking_permalink = $journey_detail->permalink . '?step=booking-review';
                                    } ?>

                                    <tr>
                                        <td style="white-space: nowrap; padding-right: 5px;">
                                            <a href="<?php echo $booking_permalink; ?>" style="color: #545454;">
                                                <b>
                                                    <?php echo $value->booking_code; ?>
                                                </b>
                                            </a>
                                        </td>
                                        <td><?php echo date('j M Y', strtotime($journey_detail->departure)); ?></td>
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
                                            <a href="<?php echo WP_SITEURL.'/contact-us/' ?>">
                                                <img src="<?php echo VIEW_URL . '/images/icon-question.png' ?>"
                                                     class="img-icon">
                                            </a>
                                        </td>
                                    </tr>

                                <?php }
                            }
                        }
                        else { ?>
                            <tr>
                                <td colspan="9" class="text-center">No result</td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>

                <div class="ctn-list-journey-mb hide-on-med-and-up">
                    <?php
                    if (!empty($booking_list)) {
                        foreach ($booking_list as $key => $value) {

                            $booking_permalink = get_permalink($value->id);

                            $journey_detail = $modelJourney->getInfo($value->journey_id);
                            $total = $modelBooking->getCartTotalByID($value->id);
                            $total = number_format($total);
                            $total_people = $modelBooking->getCartTotalPeople($value->id);
                            $status = $modelBooking->getBookingStatusText($value->status);

                            if ($value->status == 'cart') {
                                // journey permalink for cart
                                $booking_permalink = $journey_detail->permalink . '?step=booking-review';
                            }
                            ?>

                            <div class="panel-heading" role="tab" id="heading_<?php echo $key + 1 ?>">
                                <div class="panel-title be-travel">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion"
                                       href="#collapse_<?php echo $key + 1 ?>" aria-expanded="true"
                                       aria-controls="collapseOne">
                                        <?php echo $value->booking_code . ': ' . $status; ?>
                                    </a>
                                </div>
                            </div>

                            <div id="collapse_<?php echo $key + 1 ?>"
                                 class="panel-collapse collapse  ctn-show-hide-traveller" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <b>Booking ID </b>:
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="<?php echo $booking_permalink; ?>">
                                                <?php echo $value->booking_code; ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <b>Departure </b>
                                        </div>
                                        <div class="col-xs-8">
                                            <?php echo date('j M Y', strtotime($journey_detail->departure)); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <b>From - to </b>
                                        </div>
                                        <div class="col-xs-8">
                                            <?php echo $journey_detail->journey_type_info->starting_point ?>
                                            - <?php echo $journey_detail->journey_type_info->destination_info->post_title ?> <?php echo $journey_detail->journey_type_info->nights; ?>
                                            nights
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <b>Journey </b>
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="<?php echo $journey_detail->permalink; ?>"
                                               target="_blank" style="#e4a611">
                                                <?php echo $journey_detail->journey_type_info->post_title . ' - ' . $journey_detail->post_title; ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <b>Ship </b>
                                        </div>
                                        <div class="col-xs-8">
                                            <a href="<?php echo $journey_detail->journey_type_info->ship_info->permalink; ?>"
                                               target="_blank" style="color: #e4a611">
                                                <?php echo $journey_detail->journey_type_info->ship_info->post_title; ?>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <b>Travellers </b>
                                        </div>
                                        <div class="col-xs-8">
                                            <?php echo $total_people; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <b>Payment </b>
                                        </div>
                                        <div class="col-xs-8">
                                            <b style="color: black;font-size: 17px;text-transform: uppercase">US$<?php echo $total; ?></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <b>Status </b>
                                        </div>
                                        <div class="col-xs-8">
                                            <?php echo $status; ?>
                                        </div>
                                    </div>
                                    <div class="select-mb">
                                        <a href="<?php echo $booking_permalink ?>" class="bnt-jn">View</a>
                                    </div>
                                </div>

                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 text-center" style="margin : 50px 0">
                <p style="margin-bottom: 30px;font-weight: bold">Share your feeling to inspire people on TripAdvisor</p>
                <img src="<?php echo VIEW_URL . '/images/icon-green-booking.png' ?>" style="">
            </div>
        </div>
    </div>

<?php get_footer();