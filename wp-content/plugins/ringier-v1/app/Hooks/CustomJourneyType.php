<?php
namespace RVN\Hooks;

use RVN\Controllers\ShipController;
use RVN\Library\CPTColumns;
use RVN\Models\JourneyType;
use RVN\Models\Posts;
use RVN\Models\Ships;

class CustomJourneyType
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomJourneyType();
        }

        return self::$instance;
    }

    function __construct()
    {

        add_action('add_meta_boxes', [$this, 'itinerary']);
        add_action('save_post', [$this, 'save']);
    }


    public function itinerary()
    {
        add_meta_box('itinerary', 'ITINERARY', [$this, 'show'], 'journey_type', 'normal', 'high');
        add_meta_box('include', 'WHAT’S INCLUDED', [$this, 'show_include'], 'journey_type', 'normal', 'high');
        add_meta_box('ship_price', 'Ship and Room Price', [$this, 'show_ship'], 'journey_type', 'normal', 'high');
    }


    public function show_include(){
        global $post;
        $objJourneyType = JourneyType::init();
        $jt_info = $objJourneyType->getInfo($post->ID);
        ?>
        <style>

            textarea {
                width: 100%;
                padding: 7px;
            }

        </style>
        <div class="form-group">
            <!--<textarea class="form-control" rows="5" name="include" ><?php /*echo !empty($jt_info->include) ? $jt_info->include :''  */?></textarea>-->
            <?php
            $acf_field_wysiwyg = new \acf_field_wysiwyg();
            $field = array(
                'id' => 'include',
                'name' => 'include',
                'value' => !empty($jt_info->include) ? $jt_info->include :'' ,
                'toolbar' => 'full',
                'media_upload' => 'yes',
            );
            $acf_field_wysiwyg->create_field($field);
            ?>
        </div>
    <?php }

    public function show()
    {
        global $post;
        $objJourneyType = JourneyType::init();
        $list_itinerary = $objJourneyType->getJourneyTypeItinerary($post->ID);

        $objPost = Posts::init();
        $list_port = $objPost->getList(array(
            'post_type' => array('port','excursion'),
        ));
        ?>
        <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
        <style>
            .box-day {
                border: 1px solid #ccc;
                padding: 20px;
                position: relative;
                margin-bottom: 20px;
                background: #faf7f2;
            }

            .box-day input, .box-day textarea {
                width: 100%;
                padding: 7px;
            }
            .box-day select{
                width: 100%;
            }

            .box-day label {
                font-weight: bold;
            }

            .box-day .form-group {
                margin-bottom: 20px;
            }
            .icon-show-hide-day{
                position: absolute;
                top: 8px;
                right: 10px;
                border-radius: 50%;
                color: #23282d;
                font-size: 17px;
                width: 21px;
                height: 21px;
                text-align: center;
            }
            .icon-show-hide-day:hover{
                color: blue;
            }

        </style>
        <script src="https://cdn.tinymce.com/4/tinymce.min.js"></script>


        <?php if (!empty($list_itinerary)) { ?>
        <div class="ctn-box-day">
            <?php

            foreach ($list_itinerary as $k => $v) {
                //var_dump($v);?>
                <div class="box-day">
                    <div class="class-show-all" style="">
                        <div class="form-group">
                            <label for="day_name">Day : </label>
                            <input type="number" class="form-control day_name_key" placeholder="" name="day_name[]" value="<?php echo $v->day ?>">
                        </div>
                        <div class="form-group">
                            <label for="day_port">Location</label>
                            <select name="day_port[]" class="form-control">
                                <option value=""> --- Select port --- </option>
                                <?php if(!empty($list_port['data'])){
                                    foreach ($list_port['data'] as $p){ ?>
                                        <option value="<?php echo $p->ID ?>" <?php echo $v->location== $p->ID ? 'selected' :''  ?> ><?php echo $p->post_title ?></option>
                                    <?php }
                                } ?>
                            </select>
                            <!--<input type="text" class="form-control" placeholder="" name="day_port[]" value="<?php /*echo $v['day_port']*/?>">-->
                        </div>
                        <div class="form-group">
                            <label for="day_content">Content</label>
                            <?php
                            $acf_field_wysiwyg = new \acf_field_wysiwyg();
                            $field = array(
                                'id' => 'day_'.$k ,
                                'name' => 'day_content[]',
                                'value' => $v->content,
                                'toolbar' => 'full',
                                'media_upload' => 'yes',
                            );
                            $acf_field_wysiwyg->create_field($field);
                            ?>
                        </div>
                        <a href="javascript:void(0)" class="delete_day">Delete day</a>
                    </div>
                    <div class="class-hide-all" style="display: none">
                        <b>Day  : <span class="number_day_change"><?php echo $v->day ?></span></b>
                    </div>
                    <a href="javascript:void(0)" class="icon-show-hide-day hide-day" title="Hide" >
                        <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    </a>
                    <a href="javascript:void(0)" class="icon-show-hide-day show-day" title="Show more" style="display: none">
                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    </a>
                </div>

            <?php } ?>
        </div>
        <?php
    } else { ?>
        <div class="ctn-box-day">
            <div class="box-day">
                <div class="class-show-all" style="">
                    <div class="form-group">
                        <label for="day_name">Day : </label>
                        <input type="number" class="form-control day_name_key" placeholder="" name="day_name[]">
                    </div>
                    <div class="form-group">
                        <label for="day_port">Location</label>
                        <select name="day_port[]" class="form-control">
                            <option value=""> --- Select port --- </option>
                            <?php if(!empty($list_port['data'])){
                                foreach ($list_port['data'] as $p){ ?>
                                    <option value="<?php echo $p->ID ?>" ><?php echo $p->post_title ?></option>
                                <?php }
                            } ?>
                        </select>
                        <!--<input type="text" class="form-control" placeholder="" name="day_port[]">-->
                    </div>
                    <div class="form-group">
                        <label for="day_content">Content</label>
                        <?php
                        $acf_field_wysiwyg = new \acf_field_wysiwyg();
                        $field = array(
                            'id' => 'day_1',
                            'name' => 'day_content[]',
                            'value' => '',
                            'toolbar' => 'full',
                            'media_upload' => 'yes',
                        );
                        $acf_field_wysiwyg->create_field($field);
                        ?>
                    </div>
                    <a href="javascript:void(0)" class="delete_day">Delete day</a>
                </div>
                <div class="class-hide-all" style="display: none">
                    <b>Day  : <span class="number_day_change"></span></b>
                </div>
                <a href="javascript:void(0)" class="icon-show-hide-day hide-day" title="Hide" >
                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                </a>
                <a href="javascript:void(0)" class="icon-show-hide-day show-day" title="Show more" style="display: none">
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    <?php } ?>


        <a href="javascript:void(0)" class="add_new_day">Add new day</a>

        <script>
            var $ = jQuery.noConflict();
            jQuery(document).ready(function ($) {
                var list_port = <?php echo json_encode($list_port['data']) ?>;

                var html = '<div class="box-day"> ' +
                    '<div class="class-show-all" style="">' +
                    '<div class="form-group"> ' +
                    '<label for="day_name">Day : </label> ' +
                    '<input type="number" class="form-control day_name_key"  placeholder="" name="day_name[]"> ' +
                    '</div> ' +
                    '<div class="form-group"> ' +
                    '<label for="day_port">Location</label> ' +
                    '<select name="day_port[]" class="form-control"> ' +
                    '<option value=""> --- Select port --- </option> ' ;

                if(list_port){
                    $.each(list_port, function(key, p) {
                        html +=' <option value="'+p.ID+'" >'+p.post_title+'</option>';
                    });
                }

                html +='</select>' +
                    '<!--<input type="text" class="form-control"  placeholder="" name="day_port[]">--> ' +
                    '</div> ' +
                    '<div class="form-group"> ' +
                    '<label for="day_content">Content</label> ' +
                    '<textarea name="day_content[]" class="tinimce_n" rows="5"></textarea>'+
                    '</div> ' +
                    '<a href="javascript:void(0)" class="delete_day">Delete day</a>' +
                    '</div>' +
                    '<div class="class-hide-all" style="display: none"> ' +
                    '<b>Day  : <span class="number_day_change"></span></b> ' +
                    '</div> ' +
                    '<a href="javascript:void(0)" class="icon-show-hide-day hide-day" title="Hide" > ' +
                    '<i class="fa fa-sort-asc" aria-hidden="true"></i> ' +
                    '</a> ' +
                    '<a href="javascript:void(0)" class="icon-show-hide-day show-day" title="Show more" style="display: none"> ' +
                    '<i class="fa fa-sort-desc" aria-hidden="true"></i> ' +
                    '</a>' +
                    '</div>';

                $('.add_new_day').click(function () {
                    $('.ctn-box-day').append(html);
                    tinymce.init({ selector:'textarea.tinimce_n' });
                });

                $(document).delegate('.delete_day', 'click', function () {
                    $(this).closest('.box-day').remove();
                });

                $(document).delegate('.hide-day', 'click', function () {
                    var obj  = $(this);
                    obj.closest('.box-day').find('.show-day').fadeIn();
                    obj.closest('.box-day').find('.class-hide-all').fadeIn();
                    obj.closest('.box-day').find('.class-show-all').fadeOut();
                    obj.fadeOut();
                });

                $(document).delegate('.show-day', 'click', function () {
                    var obj  = $(this);
                    obj.closest('.box-day').find('.hide-day').fadeIn();
                    obj.closest('.box-day').find('.class-show-all').fadeIn();
                    obj.closest('.box-day').find('.class-hide-all').fadeOut();

                    obj.fadeOut();
                });

                $(document).delegate('.day_name_key','change', function () {
                    var obj  = $(this);
                    var day = obj.val();
                    obj.closest('.box-day').find('.number_day_change').text(day);
                });
                $(document).delegate('.day_name_key','keyup', function () {
                    var obj  = $(this);
                    var day = obj.val();
                    obj.closest('.box-day').find('.number_day_change').text(day);
                });
            });

        </script>
        <?php
    }

    public function show_ship(){
        global $post;
        $objShip = Ships::init();
        $objJourneyType = JourneyType::init();
        $objPost = Posts::init();

        $list_Ship = $objPost->getList(array(
            'posts_per_page' => 100,
            'post_type' => 'ship',
        ));

        $current_ship_id = 0;
        $current_list_room_type = $objJourneyType->getJourneyTypePrice($post->ID);
        if(!empty($current_list_room_type)){
            $first_current_list_room_type = $current_list_room_type[0];
            $current_ship_id = $first_current_list_room_type->ship_id;
        }
        ?>
        <style>
            .box-room_type{
                padding: 20px;
                border: 1px solid #ccc;
                margin-top: 10px;
                background: #faf7f2;
            }
            .box-room_type h5{
                font-weight: bold;
                margin-bottom: 10px;
                text-align: center;
            }
            .box-room_type h3{
                font-weight: bold;
                color: #ccaf0b;
                text-align: center;
                margin-top: 0;
                font-size: 15px;
                margin-bottom: 0;
            }
            .box-room_type .box-price-room label{
                margin-top: 10px;
                display: block;
            }
            .box-room_type .box-price-room{
                width: 40%;
                padding: 20px;
                display: inline-block;
            }
        </style>
        <div class="acf_postbox ">
            <div class="field field_type-post_object">
                <p class="label"><label for="ship_id">Ship</label></p>
                <select class="post_object ship_id" name="ship_id" id="ship_id" >
                    <option value="" data-is_current = '0' > --- Select ship --- </option>
                    <?php if(!empty($list_Ship['data'])){
                        foreach ($list_Ship['data'] as $ship){
                            $ship_room_type = $objShip->getShipRoomTypes($ship->ID);
                            ?>
                            <option value="<?php echo $ship->ID ?>" data-ship_room='<?php echo json_encode($ship_room_type) ?>' data-is_current="<?php echo ($current_ship_id == $ship->ID) ? 1 : 0 ?>" <?php echo ($current_ship_id == $ship->ID) ? 'selected' : '' ?>> <?php echo $ship->post_title ?> </option>
                        <?php }
                    } ?>
                </select>
            </div>

            <div class="field field_type-post_object list_room_type">
                <?php if(!empty($current_list_room_type)){
                    foreach ($current_list_room_type as $v){ ?>
                        <div class="box-room_type">
                            <h3>Room Type : <?php echo $v->room_type_name ?></h3>
                            <div class="box-price-room" >
                                <h5>High Season Price: (1 day)</h5>
                                <label>Twin sharing ($):</label>
                                <input type="number" name="twin_high_season_price[]" value="<?php echo $v->twin_high_season_price ?>">
                                <label>Single use ($):</label>
                                <input type="number" name="single_high_season_price[]" value="<?php echo $v->single_high_season_price ?>">
                                </div>
                             <div class="box-price-room" >
                                <h5>Low Season Price: (1 day)</h5>
                                <label>Twin sharing ($):</label>
                                <input type="number" name="twin_low_season_price[]" value="<?php echo $v->twin_low_season_price ?>">
                                <label>Single use ($):</label>
                                <input type="number" name="single_low_season_price[]" value="<?php echo $v->single_low_season_price ?>">
                                </div>
                            <input type="hidden" name="room_type_id[]" value="<?php echo $v->id ?>">
                            </div>
                    <?php }
                }  ?>
            </div>
        </div>


        <script>
            var $ = jQuery.noConflict();
            jQuery(document).ready(function ($) {
                var html_room_type_current = $('.list_room_type').html();
                $(document).delegate('.ship_id', 'change', function () {
                    var obj = $(this);
                    var html_room_type =   '';
                    var ship_room =  obj.find('option:selected').attr('data-ship_room');
                    var is_current =  obj.find('option:selected').attr('data-is_current');
                    if(is_current == 0){
                        if(ship_room){
                            ship_room = JSON.parse(ship_room);
                            $.each(ship_room, function (key, value) {
                                html_room_type += '<div class="box-room_type">' +
                                    '<h3>Room Type : '+value.room_type_name+'</h3> ' +
                                    '<div class="box-price-room" >' +
                                    '<h5>High Season Price: (1 day)</h5>' +
                                    '<label>Twin sharing ($):</label>' +
                                    '<input type="number" name="twin_high_season_price[]" value=""> ' +
                                    '<label>Single use ($):</label>' +
                                    '<input type="number" name="single_high_season_price[]" value="">' +
                                    '</div>' +
                                    ' <div class="box-price-room" >' +
                                    '<h5>Low Season Price: (1 day)</h5>' +
                                    '<label>Twin sharing ($):</label>' +
                                    '<input type="number" name="twin_low_season_price[]" value=""> ' +
                                    '<label>Single use ($):</label>' +
                                    '<input type="number" name="single_low_season_price[]" value=""> ' +
                                    '</div>' +
                                    '<input type="hidden" name="room_type_id[]" value="'+value.id+'"> ' +
                                    '</div>';
                            });
                        }
                    }else {
                        html_room_type = html_room_type_current;
                    }

                    $('.list_room_type').html(html_room_type);
                })
            });
        </script>
        <?php

    }

    public function save()
    {
        /*var_dump($_POST);
        exit();*/
        global $post;
        if(!empty($post) && $post->post_type == 'journey_type') {
            if(!empty($_POST)){
                $objJourneyType = JourneyType::init();

                if (!empty($_POST['day_name']) && !empty($_POST['day_content'])) {
                    $objJourneyType->deleteJourneyTypeItinerary($_POST['post_ID']);
                    $data = array();
                    foreach ($_POST['day_name'] as $k => $v) {
                        $data = array(
                            'journey_type_id' => $_POST['post_ID'],
                            'day' => $v,
                            'location' => $_POST['day_port'][$k],
                            'content' => $_POST['day_content'][$k],
                        );

                        $objJourneyType->saveJourneyTypeItinerary($data);
                    }

                    /*$args = array(
                        'itinerary' => serialize($data),
                    );
                    $objJourneyType->saveJourneyTypeInfo($_POST['post_ID'], $args);*/
                }/*else{
                    $objJourneyType->updateJourneyTypeInfo($_POST['post_ID'],array(
                        'itinerary' => '',
                    ));
                }*/

                if(!empty($_POST['include'])){
                    $include = $_POST['include'];
                }else{
                    $include = '';
                }
                $args = array(
                    'include' => $include,
                );
                $objJourneyType->saveJourneyTypeInfo($_POST['post_ID'], $args);

                if(!empty($_POST['ship_id'])){

                    $ship_id = $_POST['ship_id'];
                    $objJourneyType->saveJourneyTypeInfo($_POST['post_ID'], array('ship'=> $ship_id));

                    if(!empty($_POST['room_type_id']) && !empty($_POST['twin_high_season_price']) && !empty($_POST['single_high_season_price']) && !empty($_POST['twin_low_season_price']) && !empty($_POST['single_low_season_price'])){

                        $objJourneyType->deleteJourneyTypePrice($_POST['post_ID']);

                        foreach ($_POST['room_type_id'] as $kr => $room_type_id){
                            $args_room_price = array(
                                'journey_type_id' => $_POST['post_ID'],
                                'room_type_id' => $room_type_id,
                                'twin_high_season_price' => $_POST['twin_high_season_price'][$kr],
                                'single_high_season_price' => $_POST['single_high_season_price'][$kr],
                                'twin_low_season_price' => $_POST['twin_low_season_price'][$kr],
                                'single_low_season_price' => $_POST['single_low_season_price'][$kr],
                            );

                            $objJourneyType->saveJourneyTypePrice($args_room_price);
                        }
                    }
                }
            }
        }
    }

}