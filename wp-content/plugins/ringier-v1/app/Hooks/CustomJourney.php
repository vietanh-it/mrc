<?php
namespace RVN\Hooks;

use RVN\Library\CPTColumns;
use RVN\Models\Posts;

class CustomJourney
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomJourney();
        }

        return self::$instance;
    }

    function __construct()
    {

        add_action('add_meta_boxes', [$this, 'box_room_type_price']);
        add_action('save_post', [$this, 'save']);
    }


    public function box_room_type_price()
    {
        add_meta_box('room_type_price', 'Room Types & Pricing', [$this, 'show'], 'journey', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        ?>

        <style>
            .box-detail {
                padding: 20px;
                border: 1px solid #ccc;
                margin: 20px 0;
            }

            .box-detail label {
                font-weight: bold;
                font-size: 20px;
                margin-right: 30px;
                float: left;
                width: 30%;
            }

            #room_type_price {
                display: none;
                overflow: hidden;
            }

            .ship_map {
                max-width: 100%;
                height: auto;
            }

        </style>

        <div style="width: 50%; display: inline-block;">
            <img class="ship_map" src="#" title="ship map">
        </div>

        <table class="form-table room_type_wrapper" style="width: 49%; display: inline-block; vertical-align: top;">
            <thead>
            <tr>
                <th colspan="2">
                    <h3 style="text-align: center; text-transform: uppercase; margin: 0;">Room Types & Pricing</h3>
                </th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>
                    <label for="room_name">Room name:</label>
                </td>
                <td>
                    <input type="text" name="room_name" id="room_name" placeholder="Enter room name">
                </td>
            </tr>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input id="btn_save_room_type_price" type="button" class="button button-primary button-large"
                           value="Save Room Type Price">

                    <input id="object_id" type="hidden" name="object_id" value="<?php echo $post->ID; ?>">
                </td>
            </tr>
            </tfoot>
        </table>

        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';

                $('#acf-field-ship').on('change', function (e) {
                    var ship_id = $(this).val();

                    if (ship_id) {

                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_ship',
                                method: 'GetShipDetail',
                                ship_id: ship_id
                            },
                            success: function (data) {
                                if (data.status == 'success') {
                                    $('.ship_map').attr('src', data.data.map);

                                    var html = '';
                                    $.each(data.data.room_types, function (k, v) {
                                        html += "<tr>" +
                                            "<td><label for='room_type_" + v.id + "'>" + v.room_type_name + ":</label></td>" +
                                            "<td>$<input type='number' name='room_type_" + v.id + "' id='room_type_" + v.id + "' placeholder='Enter price for " + v.room_type_name + "' min='0' value='0'></td>" +
                                            "</tr>";
                                    });
                                    $('table.room_type_wrapper tbody').html(html);

                                    $('#room_type_price').show();
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
                                    swal({"title": "Lỗi", "text": html_msg, "type": "error", html: true});
                                }
                            }
                        }); // end ajax

                    }
                });
                $('#acf-field-ship').trigger('change');

                $('#btn_save_room_type_price').on('click', function (e) {
                    e.preventDefault();

                    var room_type = [];
                    $('.room_type_wrapper tbody input[type="number"]').each(function (k, v) {
                        if ($(v).val()) {
                            room_type[$(v).attr('name')] = $(v).val();
                        } else {
                            room_type[$(v).attr('name')] = 0;
                        }
                    });

                    $.ajax({
                        url: ajax_url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: 'ajax_handler_journey',
                            method: 'GetShipDetail',
                            ship_id: ship_id
                        },
                        success: function (data) {
                            if (data.status == 'success') {
                                $('.ship_map').attr('src', data.data.map);

                                var html = '';
                                $.each(data.data.room_types, function (k, v) {
                                    html += "<tr>" +
                                        "<td><label for='room_type_" + v.id + "'>" + v.room_type_name + ":</label></td>" +
                                        "<td>$<input type='number' name='room_type_" + v.id + "' id='room_type_" + v.id + "' placeholder='Enter price for " + v.room_type_name + "' min='0' value='0'></td>" +
                                        "</tr>";
                                });
                                $('table.room_type_wrapper tbody').html(html);

                                $('#room_type_price').show();
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
                                swal({"title": "Lỗi", "text": html_msg, "type": "error", html: true});
                            }
                        }
                    });
                });
            });
        </script>

        <?php
    }

    public function save()
    {

    }

}