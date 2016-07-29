<?php

get_header();
global $post;

$journey_ctrl = \RVN\Controllers\JourneyController::init();
$journey_detail = $journey_ctrl->getJourneyDetail($post->ID);
$ship_info = $journey_detail->journey_type_info->ship_info;
$current_season = $journey_detail->current_season;
?>

    <div class="journey-detail">

        <div class="nav-bar">
            <div class="container container-big">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h3 class="title-main white"><?php the_title(); ?></h3>
                        <p>From Saigon to Siem Reap, <?php echo $journey_detail->duration; ?>, departure on
                            <b><?php echo date('d M Y', strtotime($journey_detail->departure)); ?></b></p>
                    </div>
                    <div class="col-xs-12 col-sm-6 right">
                        <span class="total-price">Total: US$8,250</span>
                        <a href="javascript:void(0)" class="btn-menu-jn"><img
                                src="<?php echo VIEW_URL . '/images/icon-menu-1.png' ?>" class=""></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-booking">
            <div class="container container-big">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 "><p class="text-tt">Check availability and book online <span>Please select guests and starterooms
</span></p></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-7">
                        <div class="img-ship">
                            <p><?php echo $ship_info->post_title; ?> Deck Plan</p>
                            <img src="<?php echo VIEW_URL . '/images/booking-info-bar.png'; ?>"
                                 style="display: block; margin-left: auto; margin-right: auto; margin-bottom: 15px;">

                            <div class="ship_map" style="position: relative;">
                                <img src="<?php echo $ship_info->map; ?>"
                                     alt="<?php echo $ship_info->post_title; ?>"
                                     style="width: 100%;">

                                <?php foreach ($ship_info->rooms as $key => $room) {
                                    echo $room->html;
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-5">
                        <div class="booking-info">
                            <form>
                                <div class="bk-box " style="padding: 20px 30px">
                                    <span style="text-transform: uppercase;font-weight: bold">Stateroom Prices</span>
                                    (per person, including any discount)
                                </div>


                                <?php foreach ($ship_info->room_types as $key => $room_type) { ?>
                                    <div class="bk-box bk-box-gray">
                                        <span class="text"><?php echo $room_type->room_type_name ?> Twin Share</span>
                                        <span class="price">
                                            <span class="big">
                                                <?php if ($current_season == 'high') {
                                                    echo "US$" . number_format($room_type->twin_high_season_price);
                                                } else {
                                                    echo "US$" . number_format($room_type->twin_low_season_price);
                                                } ?>
                                            </span>
                                            <!--<br>-->
                                            <!--US$3,250-->
                                        </span>
                                    </div>

                                    <div class="bk-box ">
                                        <span class="text">
                                            <?php echo $room_type->room_type_name ?> Single Use
                                        </span>
                                        <span class="price">
                                            <span class="big">
                                                <?php if ($current_season == 'high') {
                                                    echo "US$" . number_format($room_type->single_high_season_price);
                                                } else {
                                                    echo "US$" . number_format($room_type->single_low_season_price);
                                                } ?>
                                            </span>
                                            <!--<br> US$3,250-->
                                        </span>
                                    </div>
                                <?php } ?>


                                <div class="bk-box bk-box-2" style="background: #d5b76e;margin-top: 50px">
                                <span
                                    style="text-transform: uppercase;font-weight: bold">Your stateroom selection</span>
                                    <span class="price-2">Total: <b>US$8,250</b></span>
                                </div>

                                <?php foreach ($ship_info->room_types as $key => $room_type) { ?>

                                    <div class="bk-box bk-box-2">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <span class="text">
                                                    <?php echo $room_type->room_type_name ?> Twin Share
                                                </span>
                                            </div>
                                            <div class="col-xs-12 col-sm-3">
                                                <span class="room_type_<?php echo $room_type->id; ?>_twin">0</span>
                                                persons
                                            </div>
                                            <div class="col-xs-12 col-sm-3">
                                                <span class="price-2">
                                                    <?php if ($current_season == 'high') {
                                                        echo "US$<b>" . number_format($room_type->twin_high_season_price) . '</b>';
                                                    } else {
                                                        echo "US$<b>" . number_format($room_type->twin_low_season_price) . '</b>';
                                                    } ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bk-box bk-box-gray bk-box-2">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6">
                                                <span class="text"><?php echo $room_type->room_type_name ?>
                                                    Single Use</span>
                                            </div>
                                            <div class="col-xs-12 col-sm-3">
                                                <span class="room_type_<?php echo $room_type->id; ?>_single">0</span>
                                                persons
                                            </div>
                                            <div class="col-xs-12 col-sm-3">
                                                <span class="price-2">
                                                <?php if ($current_season == 'high') {
                                                    echo "US$<b>" . number_format($room_type->single_high_season_price) . "</b>";
                                                } else {
                                                    echo "US$<b>" . number_format($room_type->single_low_season_price) . "</b>";
                                                } ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                                <div class="text-center btt-box">
                                    <a href="#" class="back">Back</a>
                                    <button type="submit">Continue</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var $ = jQuery.noConflict();
        $(document).ready(function () {

        });
    </script>

<?php get_footer();
