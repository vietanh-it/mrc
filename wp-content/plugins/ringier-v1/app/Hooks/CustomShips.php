<?php
namespace RVN\Hooks;

use RVN\Controllers\ShipController;

/**
 * Created by PhpStorm.
 *
 * Date: 2/9/2015
 * Time: 9:44 AM
 */
class CustomShips
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomShips();
        }

        return self::$instance;
    }


    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addRoomType']);
        add_action('save_post', [$this, 'save']);
    }


    public function addRoomType()
    {
        add_meta_box('room_info', 'Room Infomation', [$this, 'show'], 'ship', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        $ship_ctrl = ShipController::init();

        // Ship detail
        $ship_info = $ship_ctrl->getShipDetail($post->ID);
        $room_types = $ship_ctrl->getShipRoomTypes($post->ID);

        ?>

        <style>
            .ship_map {
                position: relative;
                width: 100%;
            }

            .ship_map img {
                width: 100%;
                height: auto;
            }

            .room-info {
                display: inline-block;
                vertical-align: top;
            }

            .form-table td {
                padding: 8px 10px;
            }
        </style>

        <div class="ctn-box">
            <div class="ship_map" style="width: 50%; display: inline-block;">
                <img src="<?php echo $ship_info->map; ?>"/>

                <?php foreach ($ship_info->rooms as $key => $room) {
                    echo $room->html;
                } ?>

            </div>

            <div style="width: 49%; display: inline-block; vertical-align: top;">

                <table class="form-table room-info" style="width: 100%;">
                    <tr>
                        <th colspan="2">
                            <h3 style="text-align: center; text-transform: uppercase; margin: 0;">Room Infomartion</h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label for="room_name">Room name:</label>
                        </td>
                        <td>
                            <input type="text" name="room_name" id="room_name" placeholder="Enter room name" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="room_type">Room type:</label>
                        </td>
                        <td>
                            <select id="room_type" name="room_type" disabled>
                                <option>--- Select Room Type ---</option>
                                <?php foreach ($room_types as $key => $item) {
                                    echo "<option value='{$item->id}'>{$item->room_type_name}</option>";
                                } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input id="btn_save_room_info" type="button" class="button button-primary button-large"
                                   value="Save Room Info">

                            <input type="hidden" name="room_id" id="room_id" value="0">
                        </td>
                    </tr>
                </table>


                <table class="form-table room-type-info" style="width: 100%; border-top: 1px dashed #aaaaaa;">
                    <tr>
                        <th colspan="2">
                            <h3 style="text-align: center; text-transform: uppercase; margin: 0;">Room Type Pricing</h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label for="rp_room_type">Room type:</label>
                        </td>
                        <td>
                            <select id="rp_room_type">
                                <option value="">--- Select Room Type ---</option>
                                <?php foreach ($room_types as $key => $item) {
                                    echo "<option value='{$item->id}'>{$item->room_type_name}</option>";
                                } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; font-weight: bold;">
                            <label>High Season Price:</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="prefix-price">$</span><input type="number" min="0" name="rp_twin_high_price"
                                                                      id="rp_twin_high_price"
                                                                      placeholder="Twin sharing price">
                        </td>
                        <td>
                            <span class="prefix-price">$</span><input type="number" min="0" name="rp_single_high_price"
                                                                      id="rp_single_high_price"
                                                                      placeholder="Single use price">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center; font-weight: bold;">
                            <label>Low Season Price:</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="prefix-price">$</span><input type="number" min="0" name="rp_twin_low_price"
                                                                      id="rp_twin_low_price"
                                                                      placeholder="Twin sharing price">
                        </td>
                        <td>
                            <span class="prefix-price">$</span><input type="number" min="0" name="rp_single_low_price"
                                                                      id="rp_single_high_price"
                                                                      placeholder="Single use price">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input id="btn_save_price" type="button" class="button button-primary button-large"
                                   value="Save Room Type Pricing">

                            <input type="hidden" name="rp_room_type_id" id="rp_room_type_id" value="0">
                        </td>
                    </tr>
                </table>

            </div>
        </div>

        <script>
            var $ = jQuery.noConflict();

            jQuery(document).ready(function ($) {
                var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

                $(document).delegate('[data-roomid]', 'click', function (e) {

                    $.ajax({
                        url: ajax_url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'ajax_handler_ship',
                            method: 'GetRoomInfo',
                            room_id: $(this).attr('data-roomid')
                        },
                        beforeSend: function () {
                            $('input, select', $('.room-info')).attr('disabled', true).css('opacity', 0.5);
                        },
                        success: function (data) {
                            $('input, select', $('.room-info')).attr('disabled', false).css('opacity', 1);

                            if (data.status == 'success') {
                                $('#room_name').val(data.data.room_name);
                                $('#room_type').val(data.data.room_type_id);
                                $('#room_id').val(data.data.id);
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

                $('#btn_save_room_info').on('click', function (e) {
                    e.preventDefault();

                    var room_id = $('#room_id').val();
                    var room_name = $('#room_name').val();
                    var room_type = $('#room_type').val();

                    if (!room_id) {
                        swal({
                            type: 'warning',
                            title: 'Please select room to update'
                        });
                    } else {


                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_ship',
                                method: 'SaveRoomInfo',
                                room_id: room_id,
                                room_name: room_name,
                                room_type_id: room_type
                            },
                            beforeSend: function () {
                                $('input, select', $('.room-info')).attr('disabled', true).css('opacity', 0.5);
                            },
                            success: function (data) {
                                $('input, select', $('.room-info')).attr('disabled', false).css('opacity', 1);

                                if (data.status == 'success') {
                                    $('[data-roomid="' + data.data.id + '"]').css('background', data.data.background).html('<b>' + data.data.room_name + '</b>');
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

                $('#btn_save_price').on('click', function (e) {
                    e.preventDefault();

                    var room_type_id = $('#rp_room_type').val();
                    if (!room_type_id) {
                        // Chưa chọn room type
                        swal({
                            title: 'Please choose room type',
                            type: 'warning'
                        });
                    } else {
                        var twin_high_price = $('#rp_twin_high_price').val();
                        var twin_low_price = $('#rp_twin_low_price').val();
                        var single_high_price = $('#rp_single_use_high_price').val();
                        var single_low_price = $('#rp_single_use_low_price').val();

                        if (!twin_high_price) {
                            swal({
                                title: 'Please enter twin sharing high season room pricing',
                                type: 'warning'
                            });
                        }

                        if (!twin_low_price) {
                            swal({
                                title: 'Please enter twin sharing low season room pricing',
                                type: 'warning'
                            });
                        }

                        if (!single_high_price) {
                            swal({
                                title: 'Please enter single use high season room pricing',
                                type: 'warning'
                            });
                        }

                        if (!single_low_price) {
                            swal({
                                title: 'Please enter single use low season room pricing',
                                type: 'warning'
                            });
                        }
                    }
                });

            });

        </script>

        <?php
    }


    public function save()
    {

    }

}
