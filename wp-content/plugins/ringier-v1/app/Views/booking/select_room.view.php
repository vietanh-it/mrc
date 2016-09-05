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
$journey_type_info = $journey_detail->journey_type_info;
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

                <!--Ship map-->
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

                <!--Stateroom Price-->
                <div class="col-xs-12 col-sm-5">
                    <div class="booking-info">
                        <form>

                            <!--Price table-->
                            <div>
                                <div class="bk-box " style="padding: 20px 30px">
                                    <span style="text-transform: uppercase;font-weight: bold">Stateroom Prices</span>
                                    (per person, including any discount)
                                    <a href="javascript:void(0)" class="stateroom-price-toggle">
                                        <i class="fa fa-sort-desc"></i>
                                    </a>
                                </div>

                                <div class="stateroom-price-wrapper">
                                    <?php foreach ($journey_type_info->room_price as $key => $room_type) { ?>
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
                                </div>
                            </div>


                            <!--Room booking-->
                            <div>
                                <div class="bk-box bk-box-2" style="background: #d5b76e;margin-top: 50px">
                                <span
                                    style="text-transform: uppercase;font-weight: bold">Your stateroom selection</span>
                                    <span class="price-2">Total: <b>US$<span class="stateroom-booking-total">0</span></b></span>
                                </div>

                                <div>
                                    <?php foreach ($journey_type_info->room_price  as $key => $room_type) {
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
                                                <div class="col-xs-12 col-sm-3" style="white-space: nowrap">
                                                <span class="room_type_<?php echo $room_type->id; ?>_twin"
                                                      data-price="<?php echo $twin_price; ?>">
                                                    0
                                                </span>
                                                    persons <span style="padding-left: 10px;">x</span>
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
                                                <div class="col-xs-12 col-sm-3" style="white-space: nowrap">
                                                <span class="room_type_<?php echo $room_type->id; ?>_single"
                                                      data-price="<?php echo $single_price; ?>">
                                                    0
                                                </span>
                                                    persons <span style="padding-left: 10px;">x</span>
                                                </div>
                                                <div class="col-xs-12 col-sm-3">
                                                <span class="price-2">
                                                <?php echo "US$<b>" . number_format($single_price) . "</b>"; ?>
                                                </span>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>
                                </div>

                                <div class="text-center btt-box">
                                    <!--<a href="--><?php //echo WP_SITEURL . '/journeys' ?><!--" class="back">Back</a>-->
                                    <a href="<?php echo $journey_type_info->permalink; ?>" class="back">Back</a>
                                    <a href="<?php echo $journey_detail->permalink . '?step=services-addons'; ?>"
                                       class="btn btn-primary btn-continue btn-yellow">Continue</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="option-dialog" style="display: none;">
    <a href="javascript:void(0)" class="twin"><img src="<?php echo VIEW_URL . '/images/icon-booking-twin.png'; ?>"> Twin</a> |
    <a href="javascript:void(0)" class="single"><img src="<?php echo VIEW_URL . '/images/icon-booking-single.png'; ?>"> Single</a> |
    <a href="javascript:void(0)" class="none"><img src="<?php echo VIEW_URL . '/images/icon-booking-none.png'; ?>"> None</a>
</div>

<script>
    var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
    var $ = jQuery.noConflict();
    $(document).ready(function () {

        var booking_ready = true;


        // Click on roomid
        $(document).delegate('[data-roomid]', 'click', function (e) {
            var room_id = $(this).attr('data-roomid');

            var top_position = $(this).offset().top - $('.option-dialog').outerHeight() - 20;
            var left_position = $(this).offset().left - ($('.option-dialog').outerWidth() / 2) + ($(this).width() / 2);
            $('.option-dialog').css({
                'top': top_position,
                'left': left_position
            }).attr('data-picking-roomid', room_id).fadeIn();
        });


        // Option a href click
        $(document).delegate('.option-dialog a', 'click', function (e) {
            var type = $(this).attr('class');
            var room_id = $(this).parents('.option-dialog').attr('data-picking-roomid');

            var old_type = $('[data-roomid="' + room_id + '"]').attr('data-type');

            // update current
            $('[data-roomid="' + room_id + '"]').attr('data-type', type);

            if (booking_ready) {
                booking_ready = false;
                switch_loading(true);

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
                        journey_id: <?php echo $post->ID; ?>
                    },
                    beforeSend: function () {
                        $('.option-dialog').fadeOut();
                    },
                    success: function (data) {
                        switch_loading(false);

                        if (data.status == 'success') {
                            booking_ready = true;

                            if (data.data.type != 'none') {
                                // Icon
                                $('[data-roomid="' + data.data.room_id + '"]').find('.icon-booking').remove();
                                $('[data-roomid="' + data.data.room_id + '"]').prepend(data.data.booking_icon);

                                // Show room type
                                var room_type_select = ".room_type_" + data.data.room_info.room_type_id + "_" + data.data.type;
                                $(room_type_select).parents('.bk-box').show();

                            } else {
                                // Remove icon
                                $('[data-roomid="' + data.data.room_id + '"]').find('.icon-booking').remove();

                                // Hide room type
                                room_type_select = "room_type_" + data.data.room_info.room_type_id;
                                $.each($('span[class^="' + room_type_select + '"]'), function (k, v) {
                                    if ($(v).html() <= 0) {
                                        $(v).parents('.bk-box').hide();
                                    }
                                });
                            }


                            // Edit quantity
                            // --twin
                            if (data.data.room_type_count.twin > 0) {
                                $(".room_type_" + data.data.room_info.room_type_id + "_twin").html(data.data.room_type_count.twin);
                            } else {
                                $(".room_type_" + data.data.room_info.room_type_id + "_twin").parents('.bk-box').hide();
                            }

                            // --single
                            if (data.data.room_type_count.single > 0) {
                                $(".room_type_" + data.data.room_info.room_type_id + "_single").html(data.data.room_type_count.single);
                            } else {
                                $(".room_type_" + data.data.room_info.room_type_id + "_single").parents('.bk-box').hide();
                            }

                            // TOTAL
                            $('.booking-total').html(data.data.booking_total_text);
                            $('.stateroom-booking-total').html(data.data.stateroom_booking_total_text);
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


        // Hide option dialog when click on another element
        $('html').click(function (e) {
            if (!$(e.target).hasClass('option-dialog') && (!$(e.target).parents('.option-dialog').length)) {
                if ($('.option-dialog').is(':visible')) {
                    $('.option-dialog').fadeOut();
                }
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
                        $('.stateroom-booking-total').html(data.data.stateroom_total_text);

                        // Hide stateroom if not selected
                        stateroomToggle();
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


        // Toggle stateroom price list
        $('.stateroom-price-toggle').on('click', function (e) {
            e.preventDefault();
            if ($(this).find('i').hasClass('fa-sort-desc')) {
                $(this).html('<i class="fa fa-sort-up"></i>');
            } else {
                $(this).html('<i class="fa fa-sort-desc"></i>');
            }
            $('.stateroom-price-wrapper').slideToggle();
        });
    });

    function stateroomToggle() {
        var selectors = $('span[class^="room_type_"]');

        $.each(selectors, function (k, v) {
            if (parseInt($(v).html()) > 0) {
                $(v).parents('.bk-box').show();
            } else {
                $(v).parents('.bk-box').hide();
            }
        });
    }
</script>