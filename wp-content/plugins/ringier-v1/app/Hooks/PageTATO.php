<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 10/1/2016
 * Time: 5:09 PM
 */

namespace RVN\Hooks;

use RVN\Models\Addon;
use RVN\Models\Booking;
use RVN\Models\Destinations;
use RVN\Models\Journey;
use RVN\Models\Ports;
use RVN\Models\Ships;
use RVN\Models\TaTo;

Class PageTATO
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PageTATO();
        }

        return self::$instance;
    }


    function __construct()
    {
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('add_meta_boxes', [$this, 'metaboxTaToBooking']);
        add_action('save_post', [$this, 'save']);
        // add_action('wp_trash_post', [$this, 'trashJourneySeries']);
        // add_action('untrash_post', [$this, 'untrashJourneySeries']);
        // add_action('delete_post', [$this, 'deleteJourneySeries']);
    }


    public function adminMenu()
    {
        // Add menu link Ta/To
        global $submenu;
        $submenu['edit.php?post_type=booking'][] = [
            'TA/TO Booking',
            'edit_posts',
            'post-new.php?post_type=booking&type=tato'
        ];
    }


    public function metaboxTaToBooking()
    {
        global $pagenow, $post;

        $current_post_type = get_post_type();

        // get booking type
        $m_booking = Booking::init();
        $booking = $m_booking->getBookingDetail($post->ID);


        if ($current_post_type == 'booking') {

            if (isset($_GET['type']) && $_GET['type'] == 'tato') {
                add_meta_box('tato-booking', 'TA/TO Booking', [$this, 'tatoBooking'], 'booking', 'normal', 'high');
                add_meta_box('tato-select', 'TA/TO', [$this, 'tatoSelect'], 'booking', 'side', 'high');

                remove_meta_box('submitdiv', 'booking', 'side');
                remove_meta_box('wpseo_meta', 'booking', 'normal');
            } elseif (!empty($booking->is_tato)) {
                // add_meta_box('tato-booking', 'TA/TO Booking', [$this, 'tatoBooking'], 'booking', 'normal', 'high');
            }
        }

    }


    public function tatoSelect()
    {
        $m_tato = TaTo::init();
        $list_tato = $m_tato->getTaToList();
        ?>

        <style>
            #tato-select .form-group {
                width: 100%;
            }

            #tato-select .form-group:first-child {
                margin-top: 0;
            }

            #deposit_rate {
                width: 100%;
            }

            #tato-select.fixed {
                position: fixed;
                top: 60px;
                max-width: 280px;
            }

            #tato-select .submit {
                text-align: center;
                margin-bottom: 0;
                padding-bottom: 10px;
                padding-top: 10px;
            }

            #post-body-content {
                display: none;
            }
        </style>

        <div class="row">
            <!----- TA/TO ----->
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Choose TA/TO</label>
                            <select name="tato" id="tato_select" class="select2">
                                <option value="">--- Select TA/TO ---</option>
                                <?php if (!empty($list_tato)) {
                                    foreach ($list_tato as $k => $v) {
                                        echo '<option value="' . $v->ID . '">' . $v->post_title . '</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" style="padding: 15px 0;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <a href="<?php echo WP_SITEURL; ?>/wp-admin/post-new.php?post_type=tato">
                                Add new TA/TO
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Deposit ($)</label>
                            <input type="number" name="deposit_rate" id="deposit_rate">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label style="margin-top: 10px;">
                                <span class="cb-commission-wrapper" style="display: none;">
                                    <input type="checkbox" name="is_commission" id="is_commission"> Include commission (<span
                                        class="cm-percent"></span>%) on booking
                                </span>
                                <input type="hidden" name="commission_percent" id="commission_percent" value="0">
                                <input type="hidden" name="commission_value" id="commission_value" value="0">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <i>Note: The booking is kept for only 3 days. Please tell the TA/TO for deposit as
                            soon
                            as
                            possible.</i>
                    </div>
                </div>

                <div class="row">
                    <?php submit_button('Save TA/TO Booking'); ?>
                </div>

            </div>
        </div>

        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {

                $(document).on("scroll", function () {
                    var scrollPos = $(document).scrollTop();

                    if (scrollPos < 60) {
                        $('#tato-select').removeClass('fixed');
                    } else {
                        $('#tato-select').addClass('fixed');
                    }

                });

            });
        </script>

        <?php
    }


    // Register Navigation Menus
    public function tatoBooking()
    {
        global $pagenow, $post;
        $m_journey = Journey::init();
        $m_ships = Ships::init();
        $m_ports = Ports::init();
        $m_destination = Destinations::init();
        $m_booking = Booking::init();


        $booking_detail = null;
        $edit_journey_info = null;
        $edit_room = null;
        if (isset($_GET['action']) && $_GET['action'] == 'edit') {
            $booking_detail = $m_booking->getBookingDetail($post->ID);
            $edit_journey_info = $m_journey->getInfo($booking_detail->journey_id);
            foreach ($booking_detail->cart_detail as $k => $v) {
                $room = $m_ships->getRoomInfo($v->room_id);
                $edit_room[] = [
                    'room_id'      => $v->room_id,
                    'room_name'    => $room->room_name,
                    'room_type_id' => $room->room_type_id
                ];
            }
        }


        $destination = $m_destination->getDestinationHaveJourney();
        $sail_month = $m_journey->getMonthHaveJourney();
        $list_port = $m_ports->getPortHaveJourney();
        $list_ship = $m_ships->getShipHaveJourney();
        ?>


        <style>
            #adminmenuback {
                /*display: none;*/
            }

            h2 {
                font-size: 32px;
                margin-bottom: 15px;
                line-height: 32px;
            }

            .line {
                border-bottom: 1px solid #dddddd;
            }

            .line-top {
                border-top: 1px solid #dddddd;
            }

            .form-group {
                display: inline-block;
                min-width: 200px;
                margin-right: 20px;
                margin-top: 20px;
            }

            .form-group label {
                display: block;
                padding-bottom: 5px;
            }

            .form-group select {
                width: 100%;
            }

            .addon a {
                border-radius: 50%;
                border: 1px solid #676767;
                font-size: 15px;
                vertical-align: middle;
                float: left;
                left: 0;
                width: 15px;
                height: 15px;
                text-align: center;
                line-height: 12px;
                color: #676767;
                margin-top: 6px;
                text-decoration: none;
            }

            .addon a:hover {
                background: #0085ba;
                color: #ffffff;
            }

            .addon span {
                float: left;
                padding: 1px 10px;
            }

            .box {
                border: 1px solid #dddddd;
                padding: 20px;
            }

            table {
                border-collapse: collapse;
                width: 95%;
                text-align: left;
            }

            table th, table td {
                vertical-align: top;
                min-width: 120px;
                padding-right: 25px;
                line-height: 25px;
            }

            table.addon-wrapper th, table.addon-wrapper td {
                min-width: 100px;
            }

            table.addon-wrapper th {
                color: #999999;
            }

            .addon-services-wrapper label {
                font-weight: bold;
                font-size: 18px;
            }

            .journey-no {
                font-weight: bold;
            }

            .bold, .room-list-price, .room-list-subtotal {
                font-weight: bold;
            }

            .double-line {
                border-top: 1px dashed #dddddd;
                border-bottom: 1px dashed #dddddd;
            }

            .addon-list-wrapper .form-group {
                border-right: 1px dashed #cccccc;
                padding-right: 15px;
            }

            .room-list [data-room] {
                white-space: nowrap;
            }

            .twin_single label:first-child {
                padding-right: 10px;
            }

            .crit-2 {
                display: none;
            }

            .wrap {
                margin: 10px 20px 20px 2px;
            }

        </style>


        <form action='' method='post'>

            <div class="sub">Please create only 1 booking per journey</div>

            <section class="wrap">

                <!------ Staterooms ------>
                <div class="content-wrapper">
                    <h3>Select journey & cabin</h3>
                    <hr class="line"/>


                    <!---- Criteria 1 ---->
                    <div class="row">

                        <div class="col-md-12">

                            <!--Destination-->
                            <div class="form-group">
                                <label>Destination</label>
                                <select id="destination" name="destination" class="select2">
                                    <option value="">--- Select destination ---</option>
                                    <?php if (!empty($destination)) {
                                        foreach ($destination as $k => $v) {
                                            echo '<option value="' . $v->ID . '">' . $v->post_title . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>

                            <!--Sail month-->
                            <div class="form-group">
                                <label>Sail month</label>
                                <select id="sail_month" name="sail_month" class="select2">
                                    <option value="">--- Select sail month ---</option>
                                    <?php if (!empty($sail_month)) {
                                        foreach ($sail_month as $k => $v) {
                                            echo '<option value="' . $v->month . '">' . $v->month . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>

                            <!--Port-->
                            <div class="form-group">
                                <label>Departure Port</label>
                                <select id="port" name="port" class="select2">
                                    <option value="">--- Select port ---</option>
                                    <?php if (!empty($list_port)) {
                                        foreach ($list_port as $k => $v) {
                                            echo '<option value="' . $v->ID . '">' . $v->post_title . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>

                            <!--Ship-->
                            <div class="form-group">
                                <label>Ship</label>
                                <select id="ship" name="ship" class="select2">
                                    <option value="">--- Select ship ---</option>
                                    <?php if (!empty($list_ship)) {
                                        foreach ($list_ship as $k => $v) {
                                            echo '<option value="' . $v->ID . '">' . $v->post_title . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>

                            <!--Journey-->
                            <div class="form-group">
                                <label>Journey</label>
                                <select id="journey_id" name="journey_id" class="select2">
                                    <option value="">--- Select journey ---</option>
                                    <?php if (!empty($edit_journey_info)) {
                                        echo '<option value="' . $edit_journey_info->ID . '" selected>' . $edit_journey_info->post_title . '</option>';
                                    } ?>
                                </select>
                            </div>

                        </div>

                    </div>


                    <!---- Criteria 2 ---->
                    <div class="row crit-2">

                        <div class="col-md-12">

                            <!--Room type-->
                            <div class="form-group">
                                <label>Room type</label>
                                <select id="room_type" name="room_type" class="select2">
                                    <option value="">--- Select room type ---</option>
                                </select>
                            </div>

                            <!--Room list-->
                            <div class="form-group">
                                <label>Room list</label>
                                <select id="room" name="room" class="select2" multiple></select>
                            </div>

                        </div>

                    </div>


                </div>


                <!------ Addon Services ------>
                <div class="content-wrapper addon-services-wrapper" style="display: none; margin-top: 40px;">
                    <h3>Create service add-ons for TA/TO</h3>
                    <hr class="line"/>


                    <div class="row">

                        <div class="col-md-12 addon-list-wrapper">

                            <!--Load ajax-->

                        </div>

                    </div>

                </div>


                <!------ Booking Review ------>
                <div class="content-wrapper box booking-review" style="margin-top: 40px; display: none">
                    <h3>TA/TO Booking Review</h3>

                    <div class="row">


                        <!----- Booking items ----->
                        <div class="col-md-12">

                            <table class="booking-review-table">
                                <tr class="line">
                                    <th colspan="2">
                                        Booking items
                                    </th>
                                    <th>
                                        Price
                                    </th>
                                    <th>Subtotal</th>
                                </tr>

                                <tr class="double-line">
                                    <td style="white-space: nowrap;">Journey ID No #:</td>
                                    <td class="journey-no" colspan="3"></td>
                                </tr>

                                <!--Rooms-->
                                <tr class="room-list-wrapper double-line">
                                    <td>Room</td>
                                    <td class="room-list"></td>
                                    <td class="room-list-price"></td>
                                    <td class="room-list-subtotal"></td>
                                </tr>

                                <tr class="line-top subtotal-wrapper">
                                    <td></td>
                                    <td colspan="2">Subtotal</td>
                                    <td class="bold">$<span class="subtotal">0</span></td>
                                </tr>

                                <tr class="line-top commission-wrapper" style="display: none;">
                                    <td></td>
                                    <td colspan="2">Commission</td>
                                    <td class="bold">- <span class="percent">0</span>% = $<span class="value">0</span>
                                    </td>
                                </tr>

                                <tr class="line-top total-wrapper">
                                    <td></td>
                                    <td colspan="2">Total</td>
                                    <td class="bold">$<span class="total">0</span></td>
                                </tr>

                                <tr class="line-top">
                                    <td></td>
                                    <td colspan="2">Deposit Amount (%)</td>
                                    <td class="bold">
                                        <span class="deposit-amount">0</span>% = $<span
                                            class="deposit-amount-real">0</span>
                                    </td>
                                </tr>

                            </table>

                        </div>


                    </div>


                </div>

            </section>

        </form>


        <script>
            var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';

            var $ = jQuery.noConflict();
            $(document).ready(function ($) {

                $('#destination').change(function () {
                    var dest = $(this).val();
                    console.log(dest);
                    if (dest) {
                        // Add to cart ajax
                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_jt',
                                method: 'GetMonths',
                                destination: dest
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (data) {
                                switch_loading(false);

                                if (data.status == 'success') {
                                    $('#sail_month option:gt(0)').remove();
                                    $('#port option:gt(0)').remove();
                                    $('#ship option:gt(0)').remove();

                                    var options = [];
                                    $.each(data.data, function (k, v) {
                                        var item = new Option(k, v);
                                        options.push(item);
                                    });
                                    $('#sail_month').append(options);
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

                    loadJourney();
                });

                $('#sail_month').change(function () {
                    var dest = $('#destination').val();
                    var month = $(this).val();
                    if (dest && month) {
                        // Add to cart ajax
                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_jt',
                                method: 'GetPorts',
                                month: month,
                                destination: dest
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (data) {
                                switch_loading(false);

                                if (data.status == 'success') {
                                    $('#port option:gt(0)').remove();

                                    var options = [];
                                    $.each(data.data, function (k, v) {
                                        var item = new Option(v, k);
                                        options.push(item);
                                    });
                                    $('#port').append(options);
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

                    loadJourney();
                });

                $('#port').change(function () {
                    var dest = $('#destination').val();
                    var month = $('#sail_month').val();
                    var port = $('#port').val();
                    if (dest && month && port) {
                        // Add to cart ajax
                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_jt',
                                method: 'GetShips',
                                dest: dest,
                                month: month,
                                port: port
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (data) {
                                switch_loading(false);

                                if (data.status == 'success') {
                                    $('#ship option:gt(0)').remove();

                                    var options = [];
                                    $.each(data.data, function (k, v) {
                                        var item = new Option(v, k);
                                        options.push(item);
                                    });
                                    $('#ship').append(options);
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

                    loadJourney();
                });

                $('#ship').change(function () {
                    loadJourney();
                });


                // --- Journey change ---
                $('#journey_id').change(function () {
                    var journey_id = $(this).val();


                    // Show addons & review
                    if (journey_id) {
                        $('.addon-services-wrapper').fadeIn();
                        $('.booking-review').fadeIn();
                        $('.crit-2').fadeIn();


                        // get journey info
                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_journey',
                                method: 'GetJourneyInfo',
                                object_id: journey_id
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (data) {
                                switch_loading(false);

                                if (data.status == 'success') {
                                    // Journey code
                                    $('.journey-no').html(data.data.journey_code);
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


                        // get room types ajax
                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_journey',
                                method: 'GetRoomTypes',
                                journey_id: journey_id
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (data) {
                                switch_loading(false);

                                if (data.status == 'success') {
                                    $('#room_type').find('option:gt(0)').remove();
                                    $('#room_type').append($('<option/>', {
                                        value: 'all',
                                        text: 'All room type'
                                    }));

                                    $.each(data.data, function (k, v) {
                                        $('#room_type').append($('<option/>', {
                                            value: v.id,
                                            text: v.room_type_name
                                        }));
                                    });

                                    // Resolve width for select2
                                    $('.select2').select2({width: 'resolve'});
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


                        // get available rooms ajax
                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_journey',
                                method: 'GetAvailableRooms',
                                journey_id: journey_id
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (data) {
                                switch_loading(false);

                                if (data.status == 'success') {
                                    $('#room').find('option').remove();

                                    $.each(data.data, function (k, v) {
                                        $('#room').append($('<option/>', {
                                            value: v.id,
                                            text: v.room_name
                                        }).attr('data-room-type-id', v.room_type_id));
                                    });


                                    <?php if (!empty($edit_room)) {
                                    // Insert room in cart
                                    foreach ($edit_room as $k => $v)  { ?>
                                    $('#room').append($('<option/>', {
                                        value: '<?php echo $v['room_id'] ?>',
                                        text: '<?php echo $v['room_name'] ?>'
                                    }).attr('data-room-type-id', <?php echo $v['room_type_id']; ?>).attr('selected', true));
                                    <?php }
                                    } ?>


                                    // Resolve width for select2
                                    $('.select2').select2({width: 'resolve'});
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


                        // get service addons ajax
                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_addon',
                                method: 'GetAddonList',
                                journey_id: journey_id
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (data) {
                                switch_loading(false);

                                if (data.status == 'success') {
                                    $('.addon-list-wrapper').html('');

                                    clearAddonTable();

                                    // Addon HTML
                                    $.each(data.data, function (k, v) {
                                        populateAddon(v);
                                    });


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


                        // get booking code for title


                    } else {
                        $('.addon-services-wrapper').hide();
                        $('.booking-review').hide();
                        $('.crit-2').hide();
                    }

                });


                // --- Get room when Room Type change ---
                $('#room_type').change(function () {
                    var room_type = $(this).val();

                    // get journey ajax
                    $.ajax({
                        url: ajax_url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'ajax_handler_journey',
                            method: 'GetAvailableRooms',
                            journey_id: $('#journey_id').val(),
                            room_type_id: room_type
                        },
                        beforeSend: function () {
                            switch_loading(true);
                        },
                        success: function (data) {
                            switch_loading(false);

                            if (data.status == 'success') {
                                $('#room').find('option:gt(0)').remove();

                                $.each(data.data, function (k, v) {
                                    $('#room').append($('<option/>', {
                                        value: v.id,
                                        text: v.room_name
                                    }));
                                });

                                // Resolve width for select2
                                $('.select2').select2({width: 'resolve'});
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
                });


                $('#room').change(function () {
                    var room_id = $(this).val();
                    var journey_id = $('#journey_id').val();

                    if (journey_id) {
                        // get room info ajax
                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_booking',
                                method: 'GetRoomBookingInfo',
                                room_id: room_id,
                                journey_id: journey_id
                            },
                            success: function (data) {
                                if (data.status == 'success') {
                                    $('.room-list').html('');
                                    $('.room-list-price').html('');
                                    $('.room-list-subtotal').html('');
                                    var resp = data.data;

                                    var room_price = '';
                                    var subtotal = '';
                                    $.each(resp, function (k, v) {
                                        var html = roomHtml(v.room_id, v.room_name, v.room_type_name, v.twin_price, v.single_price);
                                        room_price += '<div>$<span class="price-room-' + v.room_id + '">' + numberFormat(v.twin_price) + '</span></div>';
                                        subtotal += '<div>$<span class="subtotal-room-' + v.room_id + '">' + numberFormat(v.twin_price * 2) + '</span></div>';

                                        $('.room-list').append(html);
                                    });

                                    $('.room-list-price').html(room_price);
                                    $('.room-list-subtotal').html(subtotal);

                                    updateTotal();
                                    isEmptyBooking();
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


                // Change room twin - single
                $(document).on('change', '.twin_single input', function () {
                    var room_id = $(this).parents('[data-room]').attr('data-room');
                    var price = $(this).attr('data-price');
                    var twin_single = $(this).val();

                    $('.price-room-' + room_id).html(numberFormat(price));
                    if (twin_single == 'twin') {
                        $('.subtotal-room-' + room_id).html(numberFormat(price * 2));
                    } else {
                        $('.subtotal-room-' + room_id).html(numberFormat(price));
                    }

                    updateTotal();
                });


                // Change quantity
                $(document).on('click', '[data-action-type]', function () {
                    var addon_type = $(this).parent().attr('data-addon-type');
                    var addon_option_id = $(this).parent().attr('data-addon');
                    var addon_option_price = $(this).parent().attr('data-price');
                    var action_type = $(this).attr('data-action-type');

                    if (addon_type == 'addon') {
                        var obj_addon_option = $('.addon-quantity-' + addon_option_id);
                        var obj_addon_subtotal = $('.addon-subtotal-' + addon_option_id);
                        var quantity = parseFloat(obj_addon_option.html());

                        // + or -
                        if (action_type == 'plus') {
                            quantity += 1;
                        } else {
                            if (quantity > 0) {
                                quantity -= 1;
                            }
                        }

                        // Quantity
                        obj_addon_option.html(quantity);

                        // Subtotal
                        var subtotal = quantity * addon_option_price;
                        obj_addon_subtotal.html(numberFormat(subtotal));
                    } else {
                        obj_addon_option = $('.addon-quantity-' + addon_option_id + '-' + addon_type);
                        obj_addon_subtotal = $('.addon-subtotal-' + addon_option_id + '-' + addon_type);
                        quantity = parseFloat(obj_addon_option.html());

                        var plus_quantity = addon_type == 'twin' ? 2 : 1;

                        // + or -
                        if (action_type == 'plus') {
                            quantity += plus_quantity;
                        } else {
                            if (quantity > 0) {
                                quantity -= plus_quantity;
                            }
                        }
                        obj_addon_option.html(quantity);

                        // subtotal
                        subtotal = quantity * addon_option_price;
                        obj_addon_subtotal.html(numberFormat(subtotal));
                    }

                    // Hide or show addon review
                    if (quantity > 0) {
                        obj_addon_option.parents('tr').show();
                    } else {
                        obj_addon_option.parents('tr').hide();
                    }

                    $(this).parent().find('span').html(quantity);
                    $(this).parent().find('input[type="hidden"]').val(quantity);

                    updateTotal();
                });


                $('#tato_select').change(function () {
                    var tato_id = $(this).val();

                    if (tato_id) {
                        $.ajax({
                            url: ajax_url,
                            method: 'post',
                            dataType: 'json',
                            data: {
                                'action': 'ajax_handler_booking',
                                'method': 'GetTaToInfo',
                                'tato_id': tato_id
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (data) {
                                switch_loading(false);

                                if (data.status == 'success') {
                                    // $('#deposit_rate').val(data.data.deposit_rate);
                                    $('.deposit-amount').html(data.data.deposit_rate);

                                    $('#commission_percent').val(data.data.commission_rate);

                                    $('.cb-commission-wrapper .cm-percent').html(data.data.commission_rate);
                                    $('.cb-commission-wrapper').show();

                                    updateDeposit();
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
                        });
                    } else {
                        $('.cb-commission-wrapper').hide();
                    }

                    isEmptyBooking();
                });


                $('#deposit_rate').change(function () {
                    $('.deposit-amount-real').html(numberFormat($(this).val()));
                    updateDeposit();
                });

                // button add new TA/TO booking
                $('.wrap h1').append('<a href="<?php echo WP_SITEURL; ?>/wp-admin/post-new.php?post_type=booking&type=tato" class="page-title-action" style="margin-left: 10px;">Add New TA/TO Booking</a>');

                <?php if (!empty($edit_journey_info)) { ?>

                $('#tato_select').val(<?php echo $booking_detail->tato_id; ?>);

                $('#journey_id').trigger('change');
                $('#tato_select').trigger('change');

                <?php foreach ($booking_detail->cart_detail as $k => $v) {
                //TODO load room that already book if in editing screen ?>

                $('#room').find('option[value="<?php echo $v->id; ?>"]').attr('selected', true);

                <?php } ?>

                setTimeout(function () {
                    console.log('change');
                    $('#room').trigger('change');
                }, 2000);

                <?php } ?>


                // Update commission
                $('#is_commission').change(function () {
                    updateCommission();
                });


                // Hide save button if not book
                isEmptyBooking();
            });


            // Format number
            function numberFormat(x) {
                if (isNaN(x))return "";

                n = x.toString().split('.');
                return n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (n.length > 1 ? "." + n[1] : "");
            }


            // --- Load journey list ---
            function loadJourney() {
                var destination = $('#destination').val();
                var sail_month = $('#sail_month').val();
                var port = $('#port').val();
                var ship = $('#ship').val();

                // Load journey => hide detail
                // $('.booking-review').fadeOut();
                // $('.addon-services-wrapper').fadeOut();

                if (destination && sail_month && port && ship) {

                    // get journey ajax
                    $.ajax({
                        url: ajax_url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'ajax_handler_journey',
                            method: 'GetJourneys',
                            _destination: destination,
                            _month: sail_month,
                            _port: port,
                            _ship: ship
                        },
                        beforeSend: function () {
                            switch_loading(true);
                        },
                        success: function (data) {
                            switch_loading(false);

                            if (data.status == 'success') {
                                $('#journey_id').find('option:gt(0)').remove();

                                $.each(data.data, function (k, v) {
                                    $('#journey_id').append($('<option/>', {
                                        value: v.ID,
                                        text: v.post_title
                                    }));
                                });

                                // Resolve width for select2
                                $('.select2').select2({width: 'resolve'});
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
            }


            function populateAddon(v) {

                var table_item = '';
                var item_html = '<div class="form-group">' +
                    '<label>' + v.post_title + '</label>' +
                    '<table class="addon-wrapper"><tr>' +
                    '<th>Option</th>' +
                    '<th>person</th></tr>';

                // Create service add-ons section
                if (v.post_type == 'addon') {
                    // Addon
                    $.each(v.addon_option, function (k, val) {
                        item_html += '<tr>' +
                            '<td>' + val.option_name + '</td><td>' +
                            '<div class="addon" data-addon="' + val.id + '" data-addon-type="addon" data-price="' + val.option_price + '">' +
                            '<a href="javascript:void(0)" data-action-type="minus">-</a>' +
                            '<span>0</span>' +
                            '<input type="hidden" name="addon-addon-' + v.ID + '-' + val.id + '" value="0">' +
                            '<a href="javascript:void(0)" data-action-type="plus">+</a>' +
                            '</div>' +
                            '</td>' +
                            '</tr>';

                        // Booking review section
                        table_item += '<tr class="double-line" style="display: none;">' +
                            '<td>' + v.post_title + ' - ' + val.option_name + '</td>' +
                            '<td><span class="addon-quantity-' + val.id + '">0</span> persons</td>' +
                            '<td class="bold">$<span class="addon-price-' + val.id + '">' + val.option_price + '</span></td>' +
                            '<td class="bold">$<span class="addon-subtotal-' + val.id + '">0</span></td>' +
                            '</tr>';
                    });
                }
                else {
                    // Tour
                    item_html += '<tr>' +
                        '<td>Twin Sharing</td>' +
                        '<td>' +
                        '<div class="addon" data-addon="' + v.ID + '" data-addon-type="twin" data-price="' + v.twin_share_price + '">' +
                        '<a href="javascript:void(0)" data-action-type="minus">-</a>' +
                        '<span>0</span>' +
                        '<input type="hidden" name="addon-twin-' + v.ID + '" value="0">' +
                        '<a href="javascript:void(0)" data-action-type="plus">+</a>' +
                        '</div>' +
                        '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Single</td>' +
                        '<td>' +
                        '<div class="addon" data-addon="' + v.ID + '" data-addon-type="single" data-price="' + v.single_price + '">' +
                        '<a href="javascript:void(0)" data-action-type="minus">-</a>' +
                        '<span>0</span>' +
                        '<input type="hidden" name="addon-single-' + v.ID + '" value="0">' +
                        '<a href="javascript:void(0)" data-action-type="plus">+</a>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';

                    // Booking review section
                    table_item += '<tr class="double-line" style="display: none;">' +
                        '<td>' + v.post_title + ' - Twin Sharing</td>' +
                        '<td><span class="addon-quantity-' + v.ID + '-twin">0</span> persons</td>' +
                        '<td class="bold">$<span class="addon-price-' + v.ID + '-twin">' + v.twin_share_price + '</span></td>' +
                        '<td class="bold">$<span class="addon-subtotal-' + v.ID + '-twin">0</span></td>' +
                        '</tr>' + '<tr class="double-line" style="display: none;">' +
                        '<td>' + v.post_title + ' - Single Use</td>' +
                        '<td><span class="addon-quantity-' + v.ID + '-single">0</span> persons</td>' +
                        '<td class="bold">$<span class="addon-price-' + v.ID + '-single">' + v.single_price + '</span></td>' +
                        '<td class="bold">$<span class="addon-subtotal-' + v.ID + '-single">0</span></td>' +
                        '</tr>';
                }

                item_html += '</table></div>';


                $('.addon-list-wrapper').append(item_html);

                $('.room-list-wrapper').after(table_item);
            }


            function roomHtml(room_id, room_name, room_type_name, twin_price, single_price) {
                return '<div data-room="' + room_id + '" data-twin_single="twin">' + room_type_name + ' - ' + room_name + ' - ' +
                    '<span class="twin_single">' +

                    '<label>' +
                    '<input type="radio" name="twin_single_' + room_id + '" value="twin" data-price="' + twin_price + '" checked> Twin' +
                    '</label>' +

                    '<label>' +
                    '<input type="radio" name="twin_single_' + room_id + '" value="single" data-price="' + single_price + '"> Single' +
                    '</label>' +

                    '</span>' +
                    '</div>';
            }


            function clearAddonTable() {
                var room_list_wrapper_index = $('.room-list-wrapper').index();
                var total_wrapper_index = $('.subtotal-wrapper').index();
                var addon_selectors = $('.booking-review-table tr:lt(' + total_wrapper_index + '):gt(' + room_list_wrapper_index + ')');

                addon_selectors.remove();
            }


            function updateTotal() {
                var total = 0;
                var obj_room_subtotal = $('[class^=subtotal-room]');
                var obj_addon_subtotal = $('[class^=addon-subtotal]');

                // Room
                $(obj_room_subtotal).each(function (k, v) {
                    if ($(v).html()) {
                        total += parseFloat($(v).html().replace(',', ''));
                    }
                });

                // Addon
                $(obj_addon_subtotal).each(function (k, v) {
                    if ($(v).html()) {
                        total += parseFloat($(v).html().replace(',', ''));
                    }
                });

                $('.subtotal').html(numberFormat(total));
                $('.total').html(numberFormat(total));
                updateDeposit();
                updateCommission();
            }


            function updateDeposit() {
                var total = $('.total').html().replace(',', '');
                var percent = $('.deposit-amount').html();
                $('.deposit-amount-real').html(Math.ceil(parseFloat(total) * parseFloat(percent) / 100));
                $('#deposit_rate').val(Math.ceil(parseFloat(total) * parseFloat(percent) / 100));
            }


            function updateCommission() {
                var subtotal = $('.subtotal').html().replace(',', '');
                var is_commission = $('#is_commission').is(':checked');
                var commission_percent = $('#commission_percent').val();

                if (is_commission) {
                    var commission_value = Math.ceil(parseFloat(subtotal) * parseFloat(commission_percent) / 100);
                    var commission_value_number = numberFormat(commission_value);
                    $('#commission_value').val(commission_value);

                    // update total
                    $('.total').html(numberFormat(parseFloat(subtotal) - parseFloat(commission_value)));

                    // update value
                    $('.commission-wrapper .percent').html(commission_percent);
                    $('.commission-wrapper .value').html(commission_value_number);

                    // show
                    if ($('.booking-review').is(":visible")) {
                        $('.commission-wrapper').show();
                    }
                } else {
                    $('.commission-wrapper').hide();
                    // Total = subtotal
                    $('.total').html($('.subtotal').html());
                }

                updateDeposit();
            }


            function isEmptyBooking() {
                var room_count = $('.room-list-wrapper [data-room]').length;
                var tato_select = $('#tato_select').val();

                if (!room_count || !tato_select) {
                    $('#submit').hide();
                } else {
                    $('#submit').fadeIn();
                }
            }

        </script>

    <?php }


    public function save()
    {
        global $wpdb, $post;
        if (!empty($post) && $post->post_type == 'booking') {
            if (!empty($_POST)) {
                $m_booking = Booking::init();
                $m_journey = Journey::init();
                $m_addon = Addon::init();

                $user_id = get_current_user_id();

                if (!empty($_POST['journey_id']) && !empty($_POST['tato'])) {
                    $journey_id = $_POST['journey_id'];
                    $tato_id = $_POST['tato'];
                    $deposit_value = $_POST['deposit_rate'];

                    $cart = $m_booking->getTatoCart($user_id, $journey_id);

                    // Create cart if not exist
                    if (empty($cart)) {
                        $journey = $m_journey->getJourneyInfoByID($journey_id);
                        $code = $m_booking->generateBookingCode($journey->journey_code);

                        // Insert booking post
                        remove_action('save_post', [$this, 'save']);
                        wp_update_post([
                            'post_type'   => 'booking',
                            'post_title'  => $code,
                            'post_status' => 'publish'
                        ]);
                        add_action('save_post', [$this, 'save']);

                        $cart = [
                            'id'           => $post->ID,
                            'user_id'      => $user_id,
                            'journey_id'   => $journey_id,
                            'booking_code' => $code,
                            'status'       => 'cart',
                            'created_at'   => current_time('mysql'),
                            'updated_at'   => current_time('mysql')
                        ];
                        $wpdb->insert(TBL_CART, $cart);

                        $cart = (object)$cart;
                    }

                    if (!empty($cart)) {
                        $room_list = [];
                        $addon_list = [];
                        foreach ($_POST as $key => $value) {
                            if (!empty($value)) {
                                // Room list
                                if (preg_match("@^twin_single@", $key)) {
                                    $arr = explode('_', $key);
                                    $room_list[end($arr)] = $value;
                                }

                                // Addon list
                                if (preg_match("@^addon-@", $key)) {
                                    $arr = explode('-', $key);

                                    if ($arr[1] == 'addon') {
                                        $addon_list[$arr[2]] = [
                                            $arr[3] => $value
                                        ];
                                    } else {
                                        $addon_list[$arr[2]][$arr[1]] = $value;
                                    }
                                }
                            }
                        }


                        $cart_total = 0;
                        // Update cart detail
                        foreach ($room_list as $room_id => $type) {
                            $room_info = $m_journey->getRoomInfo($room_id);
                            $price = $m_journey->getRoomPrice($room_id, $journey_id, $type);
                            $quantity = ($type == 'twin') ? 2 : 1;
                            $total = $price * $quantity;

                            $cart_detail = $m_booking->getCartDetail($cart->id, $room_id);

                            if (empty($cart_detail)) {
                                $wpdb->insert(TBL_CART_DETAIL, [
                                    'cart_id'      => $cart->id,
                                    'room_id'      => $room_id,
                                    'room_type_id' => $room_info->room_type_id,
                                    'type'         => $type,
                                    'price'        => $price,
                                    'quantity'     => $quantity,
                                    'total'        => $total
                                ]);
                            } else {
                                $wpdb->update(TBL_CART_DETAIL, [
                                    'type'     => $type,
                                    'price'    => $price,
                                    'quantity' => $quantity,
                                    'total'    => $total
                                ], ['cart_id' => $cart->id, 'room_id' => $room_id]);
                            }

                            $cart_total += $total;
                        }

                        // Update cart addon
                        foreach ($addon_list as $object_id => $addon_array) {
                            $wpdb->delete(TBL_CART_ADDON, ['cart_id' => $cart->id]);

                            foreach ($addon_array as $k => $v) {
                                $data = [
                                    'cart_id'   => $cart->id,
                                    'status'    => 'active',
                                    'object_id' => $object_id,
                                ];

                                if ($k == 'twin' || $k == 'single') {
                                    $data['type'] = $k;
                                    $data['price'] = $m_addon->getAddonPrice([
                                        'object_id'   => $object_id,
                                        'twin_single' => $k
                                    ]);
                                } else {
                                    $data['addon_option_id'] = $k;
                                    $data['price'] = $m_addon->getAddonPrice([
                                        'object_id'       => $object_id,
                                        'addon_option_id' => $k
                                    ]);
                                }

                                $data['total'] = $data['price'] * $v;
                                $cart_total += $data['total'];

                                $wpdb->insert(TBL_CART_ADDON, $data);
                            }
                        }


                        // Update cart status
                        $wpdb->update(TBL_CART, [
                            'status'     => 'tato',
                            'tato_id'    => $tato_id,
                            'is_tato'    => 1,
                            // 'deposit'    => ($deposit_rate * $cart_total) / 100,
                            'deposit'    => $deposit_value,
                            'expired_at' => date('Y-m-d H:i:s', strtotime('+ 3 days'))
                        ], ['id' => $cart->id]);


                        if (isset($_POST['is_commission']) && !empty($_POST['is_commission'])) {
                            $wpdb->update(TBL_CART, [
                                'commission_percent' => $_POST['commission_percent'],
                                'commission_value'   => $_POST['commission_value']
                            ], ['id' => $cart->id]);
                        }


                        // sendEmailHTML()
                        $m_tato = TaTo::init();
                        $tato = $m_tato->getTaToByID($tato_id);
                        $email_args = [
                            'first_name' => $tato->first_name
                        ];
                        sendEmailHTML($tato->email, 'Reservation confirmation, reservation ID #' . $cart->booking_code,
                            'ta_to/reservation_for_ta_to.html', $email_args);
                    }

                }
            }
        }

    }
}