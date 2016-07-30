<?php

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
    exit;
}

get_header();
global $post;

$booking_ctrl = \RVN\Controllers\BookingController::init();
$journey_ctrl = \RVN\Controllers\JourneyController::init();
$ship_ctrl = \RVN\Controllers\ShipController::init();

$journey_detail = $journey_ctrl->getJourneyDetail($post->ID);
$ship_info = $journey_detail->journey_type_info->ship_info;
$current_season = $journey_detail->current_season;

$booked_rooms = $booking_ctrl->getBookedRoom($post->ID);
$rooms_html = $ship_ctrl->getShipRooms($ship_info->ID, $booked_rooms);
?>

    <div class="journey-detail">

        <div class="nav-bar">
            <div class="container container-big">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h3 class="title-main white"><?php the_title(); ?></h3>
                        <p>From <?php echo $journey_detail->journey_type_info->starting_point ?>
                            , <?php echo $journey_detail->duration; ?>, departure on
                            <b><?php echo date('d M Y', strtotime($journey_detail->departure)); ?></b></p>
                    </div>
                    <div class="col-xs-12 col-sm-6 right">
                        <span class="total-price">Total: US$<span class="booking-total">0</span></span>
                        <a href="javascript:void(0)" class="btn-menu-jn"><img
                                src="<?php echo VIEW_URL . '/images/icon-menu-1.png' ?>" class=""></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-booking">
            <div class="container container-big">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 ">
                        <p class="text-tt">
                            Check availability and book online <span>Please select guests and starterooms</span>
                        </p>
                    </div>
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

                                <?php foreach ($rooms_html as $key => $room) {
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
                                    <span class="price-2">Total: <b>US$<span class="booking-total">0</span></b></span>
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
                                    <a href="<?php echo WP_SITEURL . '/journeys' ?>" class="back">Back</a>
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
        var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        var $ = jQuery.noConflict();
        $(document).ready(function () {

            var booking_ready = true;

            // Click on roomid
            $(document).delegate('[data-roomid]', 'click', function (e) {
                if (booking_ready) {
                    booking_ready = false;

                    var parent = $(this).parent();
                    var room_id = $(this).attr('data-roomid');
                    var room_type_id = $(this).attr('data-roomtypeid');
                    var current_type = $(this).attr('data-type');
                    var current_room_type = ".room_type_" + room_type_id;

                    if (current_type == 'twin') {
                        // SINGLE

                        // Icon
                        var type = 'single';
                        var icon_html = '<img class="icon-booking" style="position: absolute; width: auto; height: auto; top: 50%; left: 50%; margin-top: -14px; margin-left: -18px;" src="<?php echo VIEW_URL ?>/images/icon-booking-single.png">';

                        // Twin quantity
                        var twin_quantity = parseInt($(current_room_type + "_twin").html());
                        if (twin_quantity > 0) {
                            twin_quantity = twin_quantity - 2;
                        } else {
                            twin_quantity = 0;
                        }
                        $(current_room_type + "_twin").html(twin_quantity);

                        // Single quantity
                        current_room_type += "_" + type;
                        var current_quantity = parseInt($(current_room_type).html());
                        $(current_room_type).html(current_quantity + 1);

                    } else if (current_type == 'single') {
                        // NONE

                        type = 'none';
                        icon_html = '';

                        // Twin quantity
                        twin_quantity = parseInt($(current_room_type + "_twin").html());
                        if (twin_quantity > 0) {
                            twin_quantity = twin_quantity - 2;
                        } else {
                            twin_quantity = 0;
                        }
                        $(current_room_type + "_twin").html(twin_quantity);

                        // Single quantity
                        var single_quantity = parseInt($(current_room_type + "_single").html());
                        if (single_quantity > 0) {
                            single_quantity = single_quantity - 1;
                        } else {
                            single_quantity = 0;
                        }
                        $(current_room_type + "_single").html(single_quantity);

                    } else {
                        // TWIN

                        type = 'twin';
                        icon_html = '<img class="icon-booking" style="position: absolute; width: auto; height: auto; top: 50%; left: 50%; margin-top: -14px; margin-left: -18px;" src="<?php echo VIEW_URL ?>/images/icon-booking-twin.png">';

                        // Single quantity
                        single_quantity = parseInt($(current_room_type + "_single").html());
                        if (single_quantity > 0) {
                            single_quantity = single_quantity - 1;
                        } else {
                            single_quantity = 0;
                        }
                        $(current_room_type + "_single").html(single_quantity);

                        // Twin quantity
                        current_room_type += "_" + type;
                        current_quantity = parseInt($(current_room_type).html());
                        $(current_room_type).html(current_quantity + 2);

                    }

                    $(this).attr('data-type', type);
                    $(this).find('.icon-booking').remove();
                    $(this).prepend(icon_html);

                    $.ajax({
                        url: ajax_url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'ajax_handler_booking',
                            method: 'SaveBooking',
                            room_id: $(this).attr('data-roomid'),
                            type: type
                        },
                        beforeSend: function () {
                            // $('input, select', $('.room-info')).attr('disabled', true).css('opacity', 0.5);
                        },
                        success: function (data) {
                            // $('input, select', $('.room-info')).attr('disabled', false).css('opacity', 1);

                            if (data.status == 'success') {
                                booking_ready = true;
                                // $('#room_name').val(data.data.room_name);
                                // $('#room_type').val(data.data.room_type_id);
                                // $('#room_id').val(data.data.id);
                                console.log(data);
                            }
                            else {
                                var html_msg = '<div>';
                                if (data.message) {
                                    $.each(data.message, function (k_msg, msg) {
                                        html_msg += msg + "<br/>";
                                    });
                                } else if (data.data) {
                                    $.each(data.data, function (k_msg, msg) {
                                        html_msg += msg + "<br/>";
                                    });
                                }
                                html_msg += "</div>";
                                swal({"title": "Error", "text": html_msg, "type": "error", html: true});
                            }
                        }
                    }); // end ajax
                }

            });

        });
    </script>

<?php get_footer();
