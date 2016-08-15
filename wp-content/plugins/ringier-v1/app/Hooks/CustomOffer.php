<?php
namespace RVN\Hooks;

use RVN\Library\CPTColumns;
use RVN\Models\Journey;
use RVN\Models\JourneyType;
use RVN\Models\Offer;
use RVN\Models\Posts;

class CustomOffer
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomOffer();
        }

        return self::$instance;
    }

    function __construct()
    {

        add_action('add_meta_boxes', [$this, 'box']);
        add_action('save_post', [$this, 'save']);
    }


    public function box()
    {
        add_meta_box('journey_type_and_room_type', 'Journey Type and Room type ', [$this, 'show'], 'offer', 'normal',
            'high');
    }


    public function show()
    {
        global $post;
        $Journey_type = JourneyType::init();

        $list_journey_type = $Journey_type->getJourneyTypeList(['limit' => 10000]);

        $objOffer = Offer::init();
        $offer_info = $objOffer->getOfferInfo($post);

        ?>

        <style>
            .add_journey_type_and_room_type {
                padding: 20px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                background: ghostwhite;
            }

            .add_journey_type_and_room_type select {
                width: 100%;
            }

            .add_journey_type_and_room_type .ctn_room_types {
                margin-top: 10px;
            }

            .add_journey_type_and_room_type .ctn_room_types .box-input {
                display: inline-block;
                padding-right: 20px;
            }
        </style>

        <div class="ctn_add_journey_type_and_room_type">
            <?php
            if ($offer_info->list_offer_room) {
                $result = [];
                foreach ($offer_info->list_offer_room as $data) {
                    $id = $data->journey_type_id;
                    if (isset($result[$id])) {
                        $result[$id][] = $data;
                    } else {
                        $result[$id] = [$data];
                    }
                }


                if ($result) {
                    foreach ($result as $k => $v) {
                        $list_room = [];
                        foreach ($v as $k1 => $v1) {
                            $list_room[] = $v1->room_type_id;
                        }

                        ?>
                        <div class="add_journey_type_and_room_type">
                            <select name="journey_type[]" class="journey_type">
                                <option value=""> --- Select journey type ---</option>
                                <?php foreach ($list_journey_type['data'] as $jt) {
                                    $room_types = json_encode($jt->ship_info->room_types);
                                    if ($k == $jt->ID) {
                                        $select = 'selected';
                                    } else {
                                        $select = '';
                                    }
                                    if (($k == $jt->ID) or empty($jt->offer)) {

                                    }
                                    echo "<option value='" . $jt->ID . "' 
            data-ship = '" . $jt->ship_info->ID . "' 
            data-room_types= '" . $room_types . "' " . $select . " > " . $jt->post_title . "</option>";
                                } ?>
                            </select>
                            <div class="ctn_room_types">
                                <?php
                                $journey_info = $Journey_type->getInfo($k);
                                $room_types_new = $journey_info->ship_info->room_types;
                                if ($room_types_new) {
                                    foreach ($room_types_new as $r) {
                                        $check = '';
                                        if (in_array($r->id, $list_room)) {
                                            $check = 'checked';
                                        }

                                        echo '<div class="box-input">
                                <input type="checkbox" name="room_type[]" value=" ' . $k . ' - ' . $r->id . ' " ' . $check . '> ' . $r->room_type_name . '
                                </div>';
                                    }
                                }

                                ?>
                            </div>
                            <p style="color: orange">Please check room type for offer. </p>
                            <!-- <a href="javascript:void(0)" class="delete_journey_type">Delete this journey type</a>-->
                        </div>
                        <?php
                    }
                }

            } else {
                echo $this->html($list_journey_type['data']);
            }


            ?>

        </div>
        <!--<div class="add_journey_type_and_room_type_new">
            <a href="javascript:void(0)" data-jt='' >Add new Journey Type</a>
        </div>-->
        <?php

        ?>

        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                $(document).delegate('.journey_type', 'change', function () {
                    var id = $(this).val();
                    var html = '';
                    if (id) {
                        var room_types = $(this).find('option:selected').attr('data-room_types');
                        if (room_types) {
                            room_types = JSON.parse(room_types);
                        }
                        $.each(room_types, function (key, value) {
                            html += '<div class="box-input">' +
                                '<input type="checkbox" name="room_type[]" value="' + id + ' - ' + value.id + '"> ' + value.room_type_name + '' +
                                '</div>';
                        });

                        //html += ' <p style="color: orange">Please check room type for offer. </p>';
                        //console.log(html);
                        $(this).closest('.add_journey_type_and_room_type').find('.ctn_room_types').html(html);
                    } else {
                        $(this).closest('.add_journey_type_and_room_type').find('.ctn_room_types').html(html);
                    }
                });

                $('.add_journey_type_and_room_type_new a').click(function () {
                    var data = <?php echo json_encode($list_journey_type['data']) ?>;
                    var html = '';
                    if (data) {
                        html += '<div class="add_journey_type_and_room_type">' +
                            '<select name="journey_type[]" class="journey_type">' +
                            '<option value=""> --- Select journey --- </option>';

                        $.each(data, function (key, value) {
                            html += "<option value='" + value.ID + "'" +
                                "data-ship = '" + value.ship_info.ID + "'" +
                                "data-room_types= '" + JSON.stringify(value.ship_info.room_types) + "' > " +
                                value.post_title +
                                "</option>";
                        });

                        html += '</select> <div class="ctn_room_types"> </div> ' +
                            ' <a href="javascript:void(0)" class="delete_journey_type">Delete this journey</a>' +
                            '</div> ';
                    }


                    $('.ctn_add_journey_type_and_room_type').append(html);
                });

                $(document).delegate('.delete_journey_type', 'click', function () {
                    $(this).closest('.add_journey_type_and_room_type').remove();
                });


            });
        </script>

        <?php
    }

    public function html($data)
    {
        $html = '
            <div class="add_journey_type_and_room_type">
            <select name="journey_type[]" class="journey_type">
                <option value=""> --- Select journey --- </option>';
        foreach ($data as $jt) {
            if (empty($jt->offer)) {
                $room_types = json_encode($jt->ship_info->room_types);
                $html .= "<option value='" . $jt->ID . "' 
            data-ship = '" . $jt->ship_info->ID . "' 
            data-room_types= '" . $room_types . "' > " . $jt->post_title . "</option>";
            }
        }

        $html .= '</select>
                <div class="ctn_room_types">
                </div>
                <!--<a href="javascript:void(0)" class="delete_journey_type">Delete this journey type</a>-->
                </div>';

        return $html;
    }

    public function save()
    {
        $objOffer = Offer::init();
        if (!empty($_POST['room_type'])) {
            $objOffer->deleteOfferRoomType($_POST['post_ID']);
            foreach ($_POST['room_type'] as $v) {
                $jt_rt = explode(' - ', $v);
                if (isset($jt_rt[1])) {
                    $jt = $jt_rt[0];
                    $rt = $jt_rt[1];

                    $insert = $objOffer->insertOfferRoomType($_POST['post_ID'], $jt, $rt);
                }
            }
        }
    }

}