<?php

global $post;
$modelBooking = \RVN\Models\Booking::init();
$modelJourney = \RVN\Models\Journey::init();

$booking_detail = $modelBooking->getBookingDetail($post->ID);
$total_people = $modelBooking->getCartTotalPeople($post->ID);

$journey_detail = $modelJourney->getInfo($booking_detail->journey_id);
$total = $modelBooking->getCartTotalByID($booking_detail->id);
$total = number_format($total);

switch ($booking_detail->status) {
    case 'cart' :
        $status = 'Booking';
        break;
    case 'before-you-go':
        $status = 'Before you go';
        break;
    case 'ready-to-onboard':
        $status = 'Ready to on-board';
        break;
    case 'onboard':
        $status = 'On-board';
        break;
    case 'finished':
        $status = 'Finished';
        break;
    default:
        $status = 'Booking';
        break;
}

// var_dump($journey_detail);

get_header();
while (have_posts()) : the_post(); ?>
    <div class="container">
        <div class="row detail-your-booking">
            <div class="col-xs-12 col-sm-12 tile-main">
                Booking Detail
                <br>
                <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
                <br><span class="text">Please review your booking carefully</span>
                <a href="javascript:void(0)">
                    <img src="<?php echo VIEW_URL . '/images/icon-question.png' ?>" class="img-icon">
                </a>
            </div>
            <div class="col-xs-12 col-sm-4 col-sm-offset-4">
                <div class="row">
                    <div class="col-xs-6 col-sm-6" style="white-space: nowrap">
                        <div class="tt-left">Booking ID</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 ">
                        <b><?php echo $booking_detail->booking_code; ?></b>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 ">
                        <div class="tt-left">Booking date</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 ">
                        <?php echo date('H:i:s d/m/Y', strtotime($booking_detail->booked_date)); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 ">
                        <div class="tt-left">Departure date</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 ">
                        <?php echo date('d/m/Y', strtotime($journey_detail->departure)); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 ">
                        <div class="tt-left">From - to</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 ">
                        <?php echo $journey_detail->journey_type_info->starting_point ?>
                        - <?php echo $journey_detail->journey_type_info->destination_info->post_title ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6 col-sm-6 ">
                        <div class="tt-left">Length</div>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                        <?php echo $journey_detail->journey_type_info->nights + 1; ?> days, <?php echo $journey_detail->journey_type_info->nights; ?> nights
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6">
                        <div class="tt-left">Journey</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 ">
                        <a href="<?php echo $journey_detail->permalink; ?>"
                           target="_blank" style="color: rgb(84, 84, 84);">
                            <?php echo $journey_detail->journey_type_info->post_title . ' - ' . $journey_detail->post_title; ?>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6">
                        <div class="tt-left">Ship</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 ">
                        <a href="<?php echo $journey_detail->journey_type_info->ship_info->permalink; ?>"
                           target="_blank" style="color: rgb(84, 84, 84);">
                            <?php echo $journey_detail->journey_type_info->ship_info->post_title; ?>
                        </a>
                    </div>
                </div>
                <div class="row" style="background: #e1e1e1;font-weight: bold">
                    <div class="col-xs-6 col-sm-6 ">
                        <div class="tt-left">Travellers</div>
                    </div>
                    <div class="col-xs-6 col-sm-6 ">
                        <?php echo $total_people; ?>
                    </div>
                </div>
                <div class="row">


                    <div class="col-xs-6 col-sm-6 ">
                        <a href="#"><img src="<?php echo VIEW_URL . '/images/icon-edit-2.png' ?>" style="margin-right: 20%"></a>
                        <b>Dong Tran</b>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="line-height: 25px">
                        Passport ID: <b>B88868</b><br>
                        Date of issue: <b>24 July 2015</b><br>
                        Valid until: <b>24 July 2025</b><br>
                        Birthday: <b>20 July 1980</b><br>
                        Gender: <b>Male</b><br>
                        Address: <b>15 5th Avenue, New York, MA999595</b><br>
                        Nationality: <b>Vietnamese</b><br>
                        Country: <b>USA</b><br>
                        Special note: <b>Vegeterian. Allergic with peanut</b>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 ">
                        <div class="tt-left"><b>Thao Pham</b></div>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                        Passport ID: <b>B88868</b>
                    </div>
                </div>


                <div class="row" style="background: #d5b76e;font-weight: bold;color: white;margin-top: 10px;margin-bottom: 10px">
                    <div class="col-xs-6 col-sm-6 ">
                        <div class="tt-left">Payment</div>
                    </div>
                    <div class="col-xs-6 col-sm-6">
                        US$<?php echo $total; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 ">
                        <div class="tt-left"><b>Status</b></div>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="color: #d5b76e">
                        <?php echo $status; ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 text-center" style="margin : 50px 0">
                <p style="margin-bottom: 30px;font-weight: bold">Share your feeling to inspire people on TripAdvisor</p>
                <a href="https://www.tripadvisor.com/" rel="nofollow">
                    <img src="<?php echo VIEW_URL . '/images/icon-green-booking.png' ?>" style="">
                </a>
            </div>
        </div>
    </div>
<?php endwhile;
get_footer();