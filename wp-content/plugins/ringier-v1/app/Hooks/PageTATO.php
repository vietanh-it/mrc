<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 10/1/2016
 * Time: 5:09 PM
 */

namespace RVN\Hooks;

use RVN\Models\Destinations;
use RVN\Models\Journey;
use RVN\Models\JourneyType;
use RVN\Models\Ports;
use RVN\Models\Posts;
use RVN\Models\Ships;

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
        add_menu_page('TA/TO Booking', 'TA/TO Booking', 'manage_options', 'tato-booking', [$this, 'tatoBooking'], '',
            50);
    }

    // Register Navigation Menus
    public function tatoBooking()
    {
        $m_journey_type = JourneyType::init();
        $m_journey = Journey::init();
        $m_post = Posts::init();
        $m_ships = Ships::init();
        $m_ports = Ports::init();
        $m_destination = Destinations::init();

        $destination = $m_destination->getDestinationHaveJourney();
        $sail_month = $m_journey->getMonthHaveJourney();
        $list_port = $m_ports->getPortHaveJourney();
        $list_ship = $m_ships->getShipHaveJourney();
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
                margin-top: 2px;
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
            }

            table th, table td {
                vertical-align: top;
                min-width: 120px;
                padding-right: 25px;
                line-height: 25px;
            }

            table {
                text-align: left;
            }

            .journey-no {
                font-weight: bold;
            }

            .bold {
                font-weight: bold;
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
                                <select name="destination" class="select2">
                                    <option value="">--- Select room type ---</option>
                                </select>
                            </div>

                            <!--Room list-->
                            <div class="form-group">
                                <label>Room list</label>
                                <select name="destination" class="select2">
                                    <option value="">--- Select room ---</option>
                                </select>
                            </div>

                        </div>

                    </div>


                </div>


                <!------ Addon Services ------>
                <div class="content-wrapper" style="margin-top: 40px;">
                    <h3>Create service add-ons for TA/TO</h3>
                    <hr class="line"/>


                    <div class="row">

                        <div class="col-md-12">

                            <!-- Addon -->
                            <div class="form-group">
                                <label>Extra</label>
                                <div class="addon">
                                    <a href="javascript:void(0)" data-action-type="minus">-</a>
                                    <span>0</span>
                                    <a href="javascript:void(0)" data-action-type="plus">+</a>
                                </div>
                            </div>

                            <!-- Addon -->
                            <div class="form-group">
                                <label>Extra</label>
                                <div class="addon">
                                    <a href="javascript:void(0)" data-action-type="minus">-</a>
                                    <span>0</span>
                                    <a href="javascript:void(0)" data-action-type="plus">+</a>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>


                <!------ Booking Review ------>
                <div class="content-wrapper box" style="margin-top: 40px;">
                    <h3>TA/TO Booking Review</h3>

                    <div class="row">


                        <div class="col-md-7">

                            <table>
                                <tr class="line">
                                    <th colspan="2">
                                        Booking items
                                    </th>
                                    <th>
                                        Price
                                    </th>
                                </tr>

                                <tr>
                                    <td>Journey ID No #:</td>
                                    <td class="journey-no" colspan="2">MCC1</td>
                                </tr>
                                <tr>
                                    <td>Room list</td>
                                    <td class="room-list">
                                        Type 1. Room 1 <br/>
                                        Type 2. Room 2 <br/>
                                        Type 3. Room 3 <br/>
                                    </td>
                                    <td class="room-list-price bold">
                                        $1,200 <br/>
                                        $1,200 <br/>
                                        $1,200 <br/>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Service 1</td>
                                    <td>2 persons</td>
                                    <td class="bold">$600</td>
                                </tr>

                                <tr class="line-top">
                                    <td></td>
                                    <td>Total</td>
                                    <td class="total bold">$3,300</td>
                                </tr>

                                <tr class="line-top">
                                    <td></td>
                                    <td>Deposit Amount (x%)</td>
                                    <td class="total bold">$1,600</td>
                                </tr>

                            </table>

                        </div>

                        <div class="col-md-5">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Choose TA/TO</label>
                                        <select name="tato" class="select2">
                                            <option value="">--- Select TA/TO ---</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="#">Add new TA/TO</a>
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


                $('#journey_id').change(function () {
                    var journey_id = $(this).val();

                    // get journey ajax
                    $.ajax({
                        url: ajax_url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'ajax_handler_journey',
                            method: 'GetJourneys',
                            journey_id: journey_id
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

            });

            function loadJourney() {
                var destination = $('#destination').val();
                var sail_month = $('#sail_month').val();
                var port = $('#port').val();
                var ship = $('#ship').val();

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
        </script>

    <?php }
}