<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 10/1/2016
 * Time: 5:09 PM
 */

namespace RVN\Hooks;

use RVN\Models\Addon;
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
        add_action('admin_menu', [$this, 'initPages']);
    }


    public function initPages()
    {
        // add_menu_page('TA/TO Booking', 'TA/TO Booking', 'manage_options', 'tato-booking', [$this, 'tatoBooking'], '',
        //     50);
        add_submenu_page('edit.php?post_type=booking', 'TA/TO Booking', 'TA/TO Booking', 'manage_options',
            'tato-booking',
            [$this, 'tatoBooking']);
    }

    // Register Navigation Menus
    public function tatoBooking()
    {
        $m_journey = Journey::init();
        $m_ships = Ships::init();
        $m_ports = Ports::init();
        $m_destination = Destinations::init();
        $m_tato = TaTo::init();

        $destination = $m_destination->getDestinationHaveJourney();
        $sail_month = $m_journey->getMonthHaveJourney();
        $list_port = $m_ports->getPortHaveJourney();
        $list_ship = $m_ships->getShipHaveJourney();
        $list_tato = $m_tato->getTaToList();
        ?>


        <style>
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

        </style>


        <form action='' method='post'>

            <h2>Booking</h2>
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
                                </select>
                            </div>

                        </div>

                    </div>


                    <!---- Criteria 2 ---->
                    <div class="row">

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
                                <select id="room" name="room" class="select2" multiple>
                                </select>
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
                <div class="content-wrapper box booking-review" style="margin-top: 40px; display: block">
                    <h3>TA/TO Booking Review</h3>

                    <div class="row">


                        <!----- Booking items ----->
                        <div class="col-md-7">

                            <table>
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
                                    <td>Journey ID No #:</td>
                                    <td class="journey-no" colspan="3"></td>
                                </tr>

                                <tr class="room-list-wrapper double-line">
                                    <td>Room</td>
                                    <td class="room-list">
                                        <div data-room="201" data-type="wait">
                                            Apasui - 201 - <a href="#">Twin sharing</a> or <a href="#">Single use</a>
                                        </div>
                                        <div data-room="202" data-type="wait">
                                            Apasui - 202 -
                                            <span class="twin_single">
                                                Twin sharing
                                            </span>
                                        </div>
                                    </td>
                                    <td class="room-list-price">
                                        <div>?</div>
                                        <div>$4,000</div>
                                    </td>
                                    <td class="room-list-subtotal">
                                        <div>?</div>
                                        <div>$4,000</div>
                                    </td>
                                </tr>

                                <tr class="line-top">
                                    <td></td>
                                    <td colspan="2">Total</td>
                                    <td class="bold">$<span class="total">0</span></td>
                                </tr>

                                <tr class="line-top">
                                    <td></td>
                                    <td colspan="2">Deposit Amount (%)</td>
                                    <td class="bold">$<span class="deposit-amount">0</span></td>
                                </tr>

                            </table>

                        </div>


                        <!----- TA/TO ----->
                        <div class="col-md-5">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Choose TA/TO</label>
                                        <select name="tato" class="select2">
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

                            <div class="row">
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
                                        <label>Deposit (%)</label>
                                        <input type="number" name="deposit">
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

                        </div>


                    </div>


                </div>

            </section>

            <?php
            submit_button();
            ?>

        </form>


        <script>
            var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';

            var $ = jQuery.noConflict();
            $(document).ready(function ($) {


                $('#destination').change(function () {
                    loadJourney();
                });

                $('#sail_month').change(function () {
                    loadJourney();
                });

                $('#port').change(function () {
                    loadJourney();
                });

                $('#ship').change(function () {
                    loadJourney();
                });


                // --- Journey change ---
                $('#journey_id').change(function () {
                    var journey_id = $(this).val();


                    // Show addons & review
                    $('.addon-services-wrapper').fadeIn();
                    $('.booking-review').fadeIn();


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

                    swal({
                        title: "Twin sharing or Single use?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#0085ba",
                        confirmButtonText: "Twin Sharing",
                        cancelButtonText: "Single Use",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function (isConfirm) {
                        if (isConfirm) {
                            swal.close();
                        } else {

                            // get room info ajax
                            $.ajax({
                                url: ajax_url,
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    action: 'ajax_handler_booking',
                                    method: 'GetRoomBookingInfo',
                                    room_id: room_id,
                                    twin_single: 'single'
                                },
                                success: function (data) {
                                    if (data.status == 'success') {

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

                            swal.close();
                        }
                    });
                });

            });


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

                // Create service add-ons section
                if (v.type == 'addon') {
                    // Addon
                    var item_html = '<div class="form-group">' +
                        '<label>' + v.post_title + '</label>' +
                        '<table class="addon-wrapper"><tr>' +
                        '<th>Option</th>' +
                        '<th>person</th></tr><tr>' +
                        '<td>Sharing</td><td>' +
                        '<div class="addon">' +
                        '<a href="javascript:void(0)" data-action-type="minus">-</a>' +
                        '<span>0</span>' +
                        '<a href="javascript:void(0)" data-action-type="plus">+</a>' +
                        '</div>' +
                        '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Single</td>' +
                        '<td>' +
                        '<div class="addon">' +
                        '<a href="javascript:void(0)" data-action-type="minus">-</a>' +
                        '<span>0</span>' +
                        '<a href="javascript:void(0)" data-action-type="plus">+</a>' +
                        '</div>' +
                        '</td>' +
                        '</tr></table></div>';

                    // Booking review section
                    var table_item = '<tr class="double-line">' +
                        '<td>' + v.post_title + '</td>' +
                        '<td><span data-addon-id-quantity="' + v.ID + '">0</span> persons</td>' +
                        '<td class="bold">$<span data-addon-id-price="' + v.ID + '">0</span></td>' +
                        '<td class="bold">$<span data-addon-id-subtotal="' + v.ID + '">0</span></td>' +
                        '</tr>';
                }
                else {
                    // Tour
                    item_html = '<div class="form-group">' +
                        '<label>' + v.post_title + '</label>' +
                        '<table class="addon-wrapper">' +
                        '<tr>' +
                        '<th>Option</th>' +
                        '<th>person</th>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Sharing</td>' +
                        '<td><div class="addon">' +
                        '<a href="javascript:void(0)" data-action-type="minus">-</a>' +
                        '<span>0</span>' +
                        '<a href="javascript:void(0)" data-action-type="plus">+</a>' +
                        '</div>' +
                        '</td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td>Single</td>' +
                        '<td>' +
                        '<div class="addon">' +
                        '<a href="javascript:void(0)" data-action-type="minus">-</a>' +
                        '<span>0</span>' +
                        '<a href="javascript:void(0)" data-action-type="plus">+</a>' +
                        '</div>' +
                        '</td>' +
                        '</tr></table></div>';

                    // Booking review section
                    table_item = '<tr class="double-line">' +
                        '<td>' + v.post_title + '</td>' +
                        '<td><span data-addon-id-quantity="' + v.ID + '">0</span> persons</td>' +
                        '<td class="bold">$<span data-addon-id-price="' + v.ID + '">0</span></td>' +
                        '<td class="bold">$<span data-addon-id-subtotal="' + v.ID + '">0</span></td>' +
                        '</tr>';
                }
                $('.addon-list-wrapper').append(item_html);

                $('.room-list-wrapper').after(table_item);
            }
        </script>

    <?php }
}