<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 11-Aug-16
 * Time: 12:27 AM
 */

$user_id = get_current_user_id();

global $post;

$booking_ctrl = \RVN\Controllers\BookingController::init();
$journey_ctrl = \RVN\Controllers\JourneyController::init();
$ship_ctrl = \RVN\Controllers\ShipController::init();

$journey_detail = $journey_ctrl->getJourneyDetail($post->ID);
$ship_info = $journey_detail->journey_type_info->ship_info;
$current_season = $journey_detail->current_season;

$booked_rooms = $booking_ctrl->getBookedRoom($post->ID);
$rooms_html = $ship_ctrl->getShipRooms($ship_info->ID, $booked_rooms); ?>

<div class="journey-detail">

    <?php view('blocks/booking-topbar', ['journey_id' => $journey_detail->ID]); ?>

    <div class="content-booking">
        <div class="container container-big">
            <div class="row">
                <div class="col-xs-12 col-sm-12 ">
                    <p class="text-tt">
                        Check availability and book online <span>Would you like extension and service addons?</span>
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

                                                <?php echo htmlPrice($room_type, 'twin', $current_season); ?>

                                        </span>
                                </div>

                                <div class="bk-box ">
                                        <span class="text">
                                            <?php echo $room_type->room_type_name ?> Single Use
                                        </span>
                                    <span class="price">
                                            <span class="big">
                                                <?php echo htmlPrice($room_type, 'single', $current_season); ?>
                                            </span>
                                        </span>
                                </div>
                            <?php } ?>


                            <div class="bk-box bk-box-2" style="background: #d5b76e;margin-top: 50px">
                                <span
                                    style="text-transform: uppercase;font-weight: bold">Your stateroom selection</span>
                                <span class="price-2">Total: <b>US$<span class="booking-total">0</span></b></span>
                            </div>

                            <?php foreach ($ship_info->room_types as $key => $room_type) {
                                $twin_price = ($current_season == 'high') ?
                                    valueOrNull($room_type->twin_high_season_price_offer,
                                        $room_type->twin_high_season_price) :
                                    valueOrNull($room_type->twin_low_season_price_offer,
                                        $room_type->twin_low_season_price);

                                $single_price = ($current_season == 'high') ?
                                    valueOrNull($room_type->single_high_season_price_offer,
                                        $room_type->single_high_season_price) :
                                    valueOrNull($room_type->single_low_season_price_offer,
                                        $room_type->single_low_season_price); ?>

                                <div class="bk-box bk-box-2">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                                <span class="text">
                                                    <?php echo $room_type->room_type_name ?> Twin Share
                                                </span>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                                <span class="room_type_<?php echo $room_type->id; ?>_twin"
                                                      data-price="<?php echo $twin_price; ?>">
                                                    0
                                                </span>
                                            persons
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                                <span class="price-2">
                                                    <?php echo "US$<b>" . number_format($twin_price) . '</b>'; ?>
                                                </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bk-box bk-box-gray bk-box-2">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                                <span class="text"><?php echo $room_type->room_type_name ?>
                                                    Single Use
                                                </span>
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                                <span class="room_type_<?php echo $room_type->id; ?>_single"
                                                      data-price="<?php echo $single_price; ?>">
                                                    0
                                                </span>
                                            persons
                                        </div>
                                        <div class="col-xs-12 col-sm-3">
                                                <span class="price-2">
                                                <?php echo "US$<b>" . number_format($single_price) . "</b>"; ?>
                                                </span>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>

                            <div class="text-center btt-box">
                                <a href="<?php echo WP_SITEURL . '/journeys' ?>" class="back">Back</a>
                                <!--<button type="submit">Continue</button>-->
                                <!--<a href="-->
                                <?php //echo $journey_detail->permalink . '?step=services-addons'; ?><!--"-->
                                <?php //echo $journey_detail->permalink . '?step=process&payment_type=atm'; ?>
                                <a href="<?php echo $journey_detail->permalink . '?step=services-addons'; ?>"
                                   class="btn btn-primary btn-continue btn-yellow">Continue</a>
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

                var room_id = $(this).attr('data-roomid');
                var room_type_id = $(this).attr('data-roomtypeid');
                var current_type = $(this).attr('data-type');
                var current_room_type = ".room_type_" + room_type_id;

                if (current_type == 'twin') {
                    // SINGLE
                    var quantity = 1;

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
                    quantity = 0;

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
                    quantity = 2;

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

                var price = $(current_room_type).attr('data-price');

                if (!price) {
                    price = 0;
                }


                // Add to cart ajax
                $.ajax({
                    url: ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'ajax_handler_booking',
                        method: 'SaveCart',
                        room_id: room_id,
                        type: type,
                        price: price,
                        journey_id: <?php echo $post->ID; ?>,
                        total: price * quantity,
                        quantity: quantity
                    },
                    beforeSend: function () {
                        // $('input, select', $('.room-info')).attr('disabled', true).css('opacity', 0.5);
                    },
                    success: function (data) {
                        // $('input, select', $('.room-info')).attr('disabled', false).css('opacity', 1);

                        if (data.status == 'success') {
                            booking_ready = true;

                            // TOTAL
                            $('.booking-total').html(data.data.booking_total_text);
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


        // Load room
        if (booking_ready) {

            $.ajax({
                url: ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'ajax_handler_booking',
                    method: 'GetCart',
                    user_id: '<?php echo $user_id; ?>',
                    journey_id: '<?php echo $post->ID; ?>'
                },
                beforeSend: function () {
                    booking_ready = false;
                    // $('input, select', $('.room-info')).attr('disabled', true).css('opacity', 0.5);
                },
                success: function (data) {
                    // $('input, select', $('.room-info')).attr('disabled', false).css('opacity', 1);
                    booking_ready = true;

                    if (data.status == 'success') {
                        var icon_single_html = '<img class="icon-booking" style="position: absolute; width: auto; height: auto; top: 50%; left: 50%; margin-top: -14px; margin-left: -13px;" src="<?php echo VIEW_URL ?>/images/icon-booking-single.png">';
                        var icon_twin_html = '<img class="icon-booking" style="position: absolute; width: auto; height: auto; top: 50%; left: 50%; margin-top: -14px; margin-left: -18px;" src="<?php echo VIEW_URL ?>/images/icon-booking-twin.png">';

                        // TWIN
                        var room_type_twin_count = data.data.room_type_twin_count;
                        for (var tkey in room_type_twin_count) {
                            if (room_type_twin_count.hasOwnProperty(tkey)) {
                                $('.room_type_' + tkey + '_twin').html(room_type_twin_count[tkey]);
                            }
                        }

                        // SINGLE
                        var room_type_single_count = data.data.room_type_single_count;
                        for (var skey in room_type_single_count) {
                            if (room_type_single_count.hasOwnProperty(skey)) {
                                $('.room_type_' + skey + '_single').html(room_type_single_count[skey]);
                            }
                        }

                        // ROOM
                        $(data.data.cart).each(function (k, v) {
                            if (v.type == 'twin') {
                                $('[data-roomid="' + v.room_id + '"]').prepend(icon_twin_html).attr('data-type', v.type);
                            } else if (v.type == 'single') {
                                $('[data-roomid="' + v.room_id + '"]').prepend(icon_single_html).attr('data-type', v.type);
                            }
                        });

                        // TOTAL
                        $('.booking-total').html(data.data.total_text);
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

        // Button continue
        var btn_continue_ready = true;

        $('.btn-continue').on('click', function (e) {
            e.preventDefault();
            var next = $(this).attr('href');

            if (btn_continue_ready) {

                $.ajax({
                    url: ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {
                        action: 'ajax_handler_booking',
                        method: 'CheckCartEmpty',
                        user_id: '<?php echo $user_id; ?>',
                        journey_id: '<?php echo $post->ID; ?>'
                    },
                    beforeSend: function () {
                        btn_continue_ready = false;
                    },
                    success: function (data) {
                        btn_continue_ready = true;

                        if (data.status == 'success') {
                            window.location.href = next;
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