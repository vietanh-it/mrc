<?php
namespace RVN\Hooks;

use RVN\Controllers\ShipController;
use RVN\Models\JourneyType;
use RVN\Models\Posts;
use RVN\Models\Ships;

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
        add_action('add_meta_boxes', [$this, 'metabox']);
        add_action('save_post', [$this, 'save']);
    }


    public function metabox()
    {
        add_meta_box('decks', 'DECKS', [$this, 'show'], 'ship', 'normal', 'high');
        add_meta_box('rooms', 'ROOMS', [$this, 'show_rooms'], 'ship', 'normal', 'high');

    }



    public function show()
    {
        global $post;
        $objShip = Ships::init();
        $list_deck = $objShip->getShipInfo($post->ID);

        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/jquery.fileupload.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/jquery.fileupload-process.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/jquery.fileupload-validate.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/jquery.iframe-transport.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/vendor/jquery.ui.widget.js"></script>

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

        <?php if (!empty($list_deck->decks)) {
        $list_deck = unserialize($list_deck->decks);
        ?>
        <div class="ctn-box-day">
            <?php

            foreach ($list_deck as $k => $v) {
                $v = unserialize($v);
                $img= wp_get_attachment_image_src($v['img_id']);
                if($img) $img = array_shift($img);

                //var_dump($v);?>
                <div class="box-day">
                    <div class="class-show-all" style="">
                        <div class="form-group">
                            <label for="day_name">Title : </label>
                            <input type="text" class="form-control day_name_key" placeholder="" name="deck_title[]" value="<?php echo $v['title'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="day_content">Content</label>
                            <textarea class="form-control" rows="5" name="deck_content[]" ><?php echo $v['content'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label  for="featured_image" >Image</label>
                            <div  class="img_featured_show">
                        <span class="btn btn-success fileinput-button">
                            <input class="ship_featured_image_1" type="file" name="featured_image" data-number ="<?php echo $k +1 ?>">
                        </span>
                                <div  class="progress_<?php echo $k +1 ?> hidden">
                                    <div class="progress-bar-<?php echo $k +1 ?> progress-bar-success"></div>
                                </div>
                                <div class="return_images_<?php echo $k +1 ?>">
                                    <img src="<?php echo $img ?>" alt="">
                                </div>
                                <input type="hidden" name="img_id[]" value="<?php echo $v['img_id']  ?>" class="img_id_<?php echo $k +1 ?>">
                            </div>
                        </div>

                        <a href="javascript:void" class="delete_day">Delete deck</a>
                    </div>
                    <div class="class-hide-all" style="display: none">
                        <b>Deck  : <span class="number_day_change"><?php echo $v->day ?></span></b>
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
                        <label for="day_name">Title :  </label>
                        <input type="text" class="form-control day_name_key" placeholder="" name="deck_title[]">
                    </div>
                    <div class="form-group">
                        <label for="day_content">Content</label>
                        <textarea class="form-control" rows="5" name="deck_content[]"></textarea>
                    </div>
                    <div class="form-group">
                        <label  for="featured_image" >Image</label>
                        <div  class="img_featured_show">
                        <span class="btn btn-success fileinput-button">
                            <input class="ship_featured_image_1" type="file" name="featured_image" data-number ="<?php echo 1 ?>" >
                        </span>
                            <div  class="progress_1 hidden">
                                <div class="progress-bar-1 progress-bar-success"></div>
                            </div>
                            <div class="return_images_1"></div>
                            <input type="hidden" name="img_id[]" value="" class="img_id_1">
                        </div>
                    </div>
                    <a href="javascript:void" class="delete_day">Delete deck</a>
                </div>
                <div class="class-hide-all" style="display: none">
                    <b>Deck  : <span class="number_day_change"></span></b>
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


        <a href="javascript:void(0)" class="add_new_day" data-number="<?php echo (!empty($list_deck))  ? count($list_deck) + 1 : 2 ?>">Add new deck</a>


        <script>
            var $ = jQuery.noConflict();
            jQuery(document).ready(function ($) {
                $('.add_new_day').click(function () {
                    var obj = $(this);
                    var number = obj.attr('data-number');

                    var html = '<div class="box-day"> ' +
                        '<div class="class-show-all" style="">' +
                        '<div class="form-group"> ' +
                        '<label for="day_name">Title : </label> ' +
                        '<input type="text" class="form-control day_name_key"  placeholder="" name="deck_title[]"> ' +
                        '</div> ' +
                        '<div class="form-group"> ' +
                        '<label for="day_content">Content</label> ' +
                        '<textarea class="form-control" rows="5" name="deck_content[]"></textarea> ' +
                        '</div>' +
                        '<div class="form-group"> ' +
                        '<label  for="featured_image" >Image</label> ' +
                        '<div  class="img_featured_show"> <span class="btn btn-success fileinput-button"> ' +
                        '<input class="ship_featured_image_1" type="file" name="featured_image" data-number ="'+number+'" > </span> ' +
                        '<div  class="progress_'+number+' hidden"> ' +
                        '<div class="progress-bar-'+number+' progress-bar-success"></div> ' +
                        '</div> ' +
                        '<div class="return_images_'+number+'"></div> ' +
                        '<input type="hidden" name="img_id[]" value="" class="img_id_'+number+'">' +
                        '</div> ' +
                        '</div> ' +
                        ' <a href="javascript:void" class="delete_day">Delete deck</a>' +
                        '</div>' +
                        '<div class="class-hide-all" style="display: none"> ' +
                        '<b>DECK  : <span class="number_day_change"></span></b> ' +
                        '</div> ' +
                        '<a href="javascript:void(0)" class="icon-show-hide-day hide-day" title="Hide" > ' +
                        '<i class="fa fa-sort-asc" aria-hidden="true"></i> ' +
                        '</a> ' +
                        '<a href="javascript:void(0)" class="icon-show-hide-day show-day" title="Show more" style="display: none"> ' +
                        '<i class="fa fa-sort-desc" aria-hidden="true"></i> ' +
                        '</a>' +
                        '</div>';
                    $('.ctn-box-day').append(html);
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

                $(document).delegate('.ship_featured_image_1', 'click', function () {
                    var obj = $(this);
                    var  number = obj.attr('data-number');
                    rvn_upload_photo_1(obj, {
                        action: 'ajax_handler_media',
                        method: 'UploadImages',
                        counterField: '#counter_field_featured',
                        image_size: 'thumbnail',
                        progress_bar: '.progress_'+number
                    },number);
                });
            });

            function rvn_upload_photo_1(obj, params,number=1) {
                var defaults = {
                    action: '',
                    counterField: '',
                    method: '',
                    numberImagesCurr: 0,
                    maxNumberOfFiles: 1,
                    object_id: 0,
                    image_size: 'thumbnail',
                    progress_bar: '.progress_'+number,
                    container: ''
                };

                $.extend(defaults, params);

                var contain_error = $('<p class="errors" style="color: #d9534f"/>');
                var obj_progress_bar = $('.progress-bar-'+number, $(defaults.progress_bar));

                $(obj).fileupload({
                        url: "<?php echo admin_url('admin-ajax.php') ?>",
                        dataType: 'json',
                        formData: {
                            action: defaults.action,
                            method: defaults.method,
                            image_size: defaults.image_size
                        },
                        maxFileSize: 2 * 1024 * 1024,
                        acceptFileTypes: /(\.|\/)(jpe?g|png)$/i,
                        numberImagesCurr: defaults.numberImagesCurr,
                        counterField: defaults.counterField,
                        messages: {
                            acceptFileTypes: 'Hình này không hợp lệ.',
                            maxFileSize: 'Kích thước hình phải nhỏ hơn 2M'
                        },
                        progressall: function(e, data) {
                            $('.progress_'+number).removeClass('hidden');
                            var progress = parseInt(data.loaded / data.total * 100, 10) - 10;
                            obj_progress_bar.css(
                                'width',
                                progress + '%'
                            );
                        },
                        done: function (e, data) {
                            $('.progress_'+number).addClass('hidden');
                            if(data.result.status == 'success'){
                                var images =  data.result.img;
                                contain_error.remove();
                                console.log(data);

                                $('.return_images_'+number).html("<img src ='"+images+"' style='max-width:100%;padding : 10px 0' />" );
                                $('.img_id_'+number).val(data.result.img_id);

                            }else{
                                contain_error.append('<span>' + data.result.message + '</span>');
                                contain_error.insertBefore($(defaults.progress_bar));
                            }
                        },
                        stop: function() {
                            obj_progress_bar.css(
                                'width', '100%'
                            );
                            obj_progress_bar.fadeOut(500, function() {
                                $(this).css('width', 0);
                            }).fadeIn();
                        }
                    })
                    .on('fileuploadprocessalways', function(e, data) {
                        var index = data.index,
                            file = data.files[index];
                        if(file.error) {
                            contain_error.append('<span>' + file.name + ' : ' + file.error + '</span><br/>');
                            contain_error.insertBefore($(defaults.progress_bar));
                        }
                    });
            }

        </script>
        <?php
    }


    public function show_rooms()
    {
        global $post;
        $objShip = Ships::init();
        $ship_info = $objShip->getShipInfo($post->ID);

        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/jquery.fileupload.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/jquery.fileupload-process.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/jquery.fileupload-validate.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/jquery.iframe-transport.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.fileupload/9.9.0/js/vendor/jquery.ui.widget.js"></script>
        <!--<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script>tinymce.init({ selector:'.tniMCE textarea' });</script>-->
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


            .tniMCE .mce-tinymce{
                border: 1px solid #ccc !important;
                margin-top: 10px;
            }
            .tniMCE .mce-tinymce .mce-content-body,
            .tniMCE .mce-tinymce p
            {
                font-size: 15px !important;
                font-family: Arial, "Times New Roman", "Bitstream Charter", Times, serif;
            }
        </style>
        <div class="box-day" style="border:1px solid #ccaf0b ;    background: white;">
            <div class="form-group">
                <label for="day_content">General introduction</label>
                <textarea class="form-control" rows="5" name="room_general_introduction" ><?php echo !empty($ship_info->room_general_introduction) ? $ship_info->room_general_introduction : '' ?></textarea>
            </div>
        </div>
        <?php if (!empty($ship_info->rooms_info)) {
        $list_rooms = unserialize($ship_info->rooms_info);
        ?>
        <div class="ctn-box-room">
            <?php
            foreach ($list_rooms as $k => $v) {
               $v = unserialize($v);
                $img= wp_get_attachment_image_src($v['room_img_id']);
                if($img) $img = array_shift($img);

                //var_dump($v);?>
                <div class="box-day">
                    <div class="class-show-all" style="">
                        <div class="form-group">
                            <label for="day_name">Room title : </label>
                            <input type="text" class="form-control day_name_key" placeholder="" name="room_title[]" value="<?php echo $v['room_title'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="day_content">Description</label>
                            <textarea class="form-control" rows="5" name="room_description[]" ><?php echo $v['room_description'] ?></textarea>
                        </div>
                        <div class="form-group tniMCE">
                            <label for="day_content">Content</label>
                            <textarea class="form-control" rows="5" name="room_content[]" ><?php echo $v['room_content'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label  for="featured_image" >Image</label>
                            <div  class="img_featured_show">
                        <span class="btn btn-success fileinput-button">
                            <input class="ship_featured_image" type="file" name="featured_image" data-number ="<?php echo $k +1 ?>">
                        </span>
                                <div  class="room_progress_<?php echo $k +1 ?> hidden">
                                    <div class="room_progress-bar-<?php echo $k +1 ?> progress-bar-success"></div>
                                </div>
                                <div class="room_return_images_<?php echo $k +1 ?>">
                                    <img src="<?php echo $img ?>" alt="">
                                </div>
                                <input type="hidden" name="room_img_id[]" value="<?php echo $v['room_img_id']  ?>" class="room_img_id_<?php echo $k +1 ?>">
                            </div>
                        </div>

                        <a href="javascript:void" class="delete_day">Delete room</a>
                    </div>
                    <div class="class-hide-all" style="display: none">
                        <b>Room  : <span class="number_day_change"><?php echo $v->day ?></span></b>
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
        <div class="ctn-box-room">
            <div class="box-day">
                <div class="class-show-all" style="">
                    <div class="form-group">
                        <label for="day_name">Title :  </label>
                        <input type="text" class="form-control day_name_key" placeholder="" name="room_title[]">
                    </div>
                    <div class="form-group">
                        <label for="day_content">Description</label>
                        <textarea class="form-control" rows="5" name="room_description[]" ></textarea>
                    </div>
                    <div class="form-group tniMCE">
                        <label for="day_content">Content</label>
                        <textarea class="form-control" rows="5" name="room_content[]"></textarea>
                    </div>
                    <div class="form-group">
                        <label  for="featured_image" >Image</label>
                        <div  class="img_featured_show">
                        <span class="btn btn-success fileinput-button">
                            <input class="ship_featured_image" type="file" name="featured_image" data-number ="<?php echo 1 ?>" >
                        </span>
                            <div  class="room_progress_1 hidden">
                                <div class="room_progress-bar-1 progress-bar-success"></div>
                            </div>
                            <div class="room_return_images_1"></div>
                            <input type="hidden" name="room_img_id[]" value="" class="room_img_id_1">
                        </div>
                    </div>
                    <a href="javascript:void" class="delete_day">Delete room</a>
                </div>
                <div class="class-hide-all" style="display: none">
                    <b>Room  : <span class="number_day_change"></span></b>
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


        <a href="javascript:void(0)" class="add_new_room" data-number="<?php echo (!empty($list_rooms))  ? count($list_rooms) + 1 : 2 ?>">Add new room</a>


        <script>
            var $ = jQuery.noConflict();
            jQuery(document).ready(function ($) {
                $('.add_new_room').click(function () {
                    var obj = $(this);
                    var number = obj.attr('data-number');

                    var html = '<div class="box-day"> ' +
                        '<div class="class-show-all" style="">' +
                        '<div class="form-group"> ' +
                        '<label for="day_name">Title : </label> ' +
                        '<input type="text" class="form-control day_name_key"  placeholder="" name="room_title[]"> ' +
                        '</div>' +
                        '<div class="form-group"> ' +
                        '<label for="day_content">Description</label> ' +
                        '<textarea class="form-control" rows="5" name="room_description[]" ></textarea>' +
                        '</div> ' +
                        '<div class="form-group tniMCE"> ' +
                        '<label for="day_content">Content</label> ' +
                        '<textarea class="form-control" rows="5" name="room_content[]"></textarea> ' +
                        '</div>' +
                        '<div class="form-group"> ' +
                        '<label  for="featured_image" >Image</label> ' +
                        '<div  class="img_featured_show"> <span class="btn btn-success fileinput-button"> ' +
                        '<input class="ship_featured_image" type="file" name="featured_image" data-number ="'+number+'" > </span> ' +
                        '<div  class="room_progress_'+number+' hidden"> ' +
                        '<div class="progress-bar-'+number+' progress-bar-success"></div> ' +
                        '</div> ' +
                        '<div class="room_return_images_'+number+'"></div> ' +
                        '<input type="hidden" name="room_img_id[]" value="" class="room_img_id_'+number+'">' +
                        '</div> ' +
                        '</div> ' +
                        ' <a href="javascript:void" class="delete_day">Delete room</a>' +
                        '</div>' +
                        '<div class="class-hide-all" style="display: none"> ' +
                        '<b>Room  : <span class="number_day_change"></span></b> ' +
                        '</div> ' +
                        '<a href="javascript:void(0)" class="icon-show-hide-day hide-day" title="Hide" > ' +
                        '<i class="fa fa-sort-asc" aria-hidden="true"></i> ' +
                        '</a> ' +
                        '<a href="javascript:void(0)" class="icon-show-hide-day show-day" title="Show more" style="display: none"> ' +
                        '<i class="fa fa-sort-desc" aria-hidden="true"></i> ' +
                        '</a>' +
                        '</div>';
                    $('.ctn-box-room').append(html);
                    obj.attr('data-number',parseInt(number) + 1);
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

                $(document).delegate('.ship_featured_image', 'click', function () {
                    var obj = $(this);
                    var  number = obj.attr('data-number');
                    rvn_upload_photo(obj, {
                        action: 'ajax_handler_media',
                        method: 'UploadImages',
                        counterField: '#counter_field_featured',
                        image_size: 'thumbnail',
                        progress_bar: '.room_progress_'+number
                    },number);
                });
            });

            function rvn_upload_photo(obj, params,number=1) {
                var defaults = {
                    action: '',
                    counterField: '',
                    method: '',
                    numberImagesCurr: 0,
                    maxNumberOfFiles: 1,
                    object_id: 0,
                    image_size: 'thumbnail',
                    progress_bar: '.room_progress_'+number,
                    container: ''
                };

                $.extend(defaults, params);

                var contain_error = $('<p class="errors" style="color: #d9534f"/>');
                var obj_progress_bar = $('.room_progress-bar-'+number, $(defaults.progress_bar));

                $(obj).fileupload({
                        url: "<?php echo admin_url('admin-ajax.php') ?>",
                        dataType: 'json',
                        formData: {
                            action: defaults.action,
                            method: defaults.method,
                            image_size: defaults.image_size
                        },
                        maxFileSize: 2 * 1024 * 1024,
                        acceptFileTypes: /(\.|\/)(jpe?g|png)$/i,
                        numberImagesCurr: defaults.numberImagesCurr,
                        counterField: defaults.counterField,
                        messages: {
                            acceptFileTypes: 'Hình này không hợp lệ.',
                            maxFileSize: 'Kích thước hình phải nhỏ hơn 2M'
                        },
                        progressall: function(e, data) {
                            $('.room_progress_'+number).removeClass('hidden');
                            var progress = parseInt(data.loaded / data.total * 100, 10) - 10;
                            obj_progress_bar.css(
                                'width',
                                progress + '%'
                            );
                        },
                        done: function (e, data) {
                            $('.room_progress_'+number).addClass('hidden');
                            if(data.result.status == 'success'){
                                var images =  data.result.img;
                                contain_error.remove();

                                $('.room_return_images_'+number).html("<img src ='"+images+"' style='max-width:100%;padding : 10px 0' />" );
                                $('.room_img_id_'+number).val(data.result.img_id);

                            }else{
                                contain_error.append('<span>' + data.result.message + '</span>');
                                contain_error.insertBefore($(defaults.progress_bar));
                            }
                        },
                        stop: function() {
                            obj_progress_bar.css(
                                'width', '100%'
                            );
                            obj_progress_bar.fadeOut(500, function() {
                                $(this).css('width', 0);
                            }).fadeIn();
                        }
                    })
                    .on('fileuploadprocessalways', function(e, data) {
                        var index = data.index,
                            file = data.files[index];
                        if(file.error) {
                            contain_error.append('<span>' + file.name + ' : ' + file.error + '</span><br/>');
                            contain_error.insertBefore($(defaults.progress_bar));
                        }
                    });
            }

        </script>
        <?php
    }

    public function save()
    {

        global $post;
        if(!empty($post) && $post->post_type == 'ship') {
            if($_POST){
                //var_dump($_POST);
                $data = $_POST;
                $args_deck =array();
                $args_room =array();
                $room_general_introduction = !empty($_POST['room_general_introduction']) ? $_POST['room_general_introduction'] : '';
                if(!empty($data['deck_title']) && !empty($data['deck_content'])){
                    foreach ($data['deck_title'] as $k => $title ){
                        $deck = array(
                            'title' => $title ,
                            'content' => $data['deck_content'][$k],
                            'img_id' => $data['img_id'][$k],
                        );

                        $args_deck[] = serialize($deck);
                    }
                }

                if(!empty($data['room_title']) && !empty($data['room_content']) && !empty($data['room_description'])){
                    foreach ($data['room_title'] as $k => $title ){
                        $room = array(
                            'room_title' => $title ,
                            'room_content' => $data['room_content'][$k],
                            'room_description' => $data['room_description'][$k],
                            'room_img_id' => $data['room_img_id'][$k],
                        );

                        $args_room[] = serialize($room);
                    }
                }

                $ship_data = array(
                    'ship_id' => $data['post_ID'],
                    'decks' => serialize($args_deck),
                    'rooms_info' => serialize($args_room),
                    'room_general_introduction' => ($room_general_introduction),
                );

                $objShips = Ships::init();
                $objShips->saveShipInfo($ship_data);

            }
        }
    }



}
