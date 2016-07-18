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
        add_action('add_meta_boxes', array($this, 'addRoomType'));
        add_action('save_post', array($this, 'save'));
    }


    public function addRoomType()
    {
        add_meta_box('room_info', 'Room Infomation', array($this, 'show'), 'ship', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        $ship_ctrl = ShipController::init();

        // Ship detail
        $ship_info = $ship_ctrl->getShipDetail($post->ID);

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
        </style>

        <div class="ctn-box">
            <div class="ship_map" style="width: 50%; display: inline-block;">
                <img src="<?php echo $ship_info->map; ?>"/>

                <?php foreach ($ship_info->rooms as $key => $room) {
                    echo $room->html;
                } ?>

            </div>

            <div class="room-info" style="width: 49%; display: inline-block; background: #00a0d2;">
                <div>Room name: <span class="room-name"></span></div>
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
                        },
                        success: function (data) {
                            console.log(data);

                            if (data.status == 'success') {
                                $('.room-name').html(data.data.room_name);
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

                });

            });

        </script>

        <?php
    }


    public function save()
    {

    }

}
