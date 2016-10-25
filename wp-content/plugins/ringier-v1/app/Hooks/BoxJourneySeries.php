<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 29-Aug-16
 * Time: 3:17 PM
 */

namespace RVN\Hooks;

use RVN\Models\Journey;
use RVN\Models\JourneySeries;
use RVN\Models\JourneyType;
use RVN\Models\Posts;

class BoxJourneySeries
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new BoxJourneySeries();
        }

        return self::$instance;
    }


    function __construct()
    {
        add_action('add_meta_boxes', [$this, 'boxJourneySeries']);
        add_action('save_post', [$this, 'save']);
    }


    public function boxJourneySeries()
    {
        add_meta_box('box_journey_series', 'Journey Series', [$this, 'show'], 'journey_series', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        $objJourneyType = JourneyType::init();
        $objJourney = Journey::init();
        $list_journey_type = $objJourneyType->getJourneyTypeList(array(
            'limit' => 10000,
        ));

        $objJourneySeries = JourneySeries::init();
        $journey_series_info = $objJourneySeries->getJourneySeriesInfo($post->ID);

        if(!empty($journey_series_info->journey_type_id)){
            $journey_type_info_current = $objJourneyType->getInfo($journey_series_info->journey_type_id);
        }

        $list_journey_series = $objJourneySeries->getListJourneySeries();
        $list_prefix = array();
        foreach ($list_journey_series as $s){
            if($s->object_id != $post->ID){
                $list_prefix[] = $s->prefix;
            }
        }
        ?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <style>
            .single-journey:not(:first-child) {
                border: 1px dashed #c7c7c7;
                margin-top: 20px;
                padding: 20px;
            }

            .item-wrapper {
                padding: 25px 40px;
                border: 1px solid #dddddd;
                border-radius: 5px;
                margin-top: 25px;
            }

            .item-wrapper h3 {
                margin: 0;
            }

            .form-group {
                margin: 10px auto;
            }
            .box-journey-series label{
                font-weight: bold;
            }
            .box-journey-series input,.box-journey-series select,.box-journey-series textarea{
                width: 100%;
                margin-top: 10px;
                margin-bottom: 10px;
            }
            .box-journey-series .single-journey{
                position: relative;
                overflow: hidden;
            }
            .box-journey-series .delete_journey{
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 17px;
            }

            .box-journey-series .edit_journey{
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                padding: 25px 0;
                background: rgba(229, 232, 126, 0.37);
                text-align: center;
                font-size: 15px;
            }
        </style>

        <div class="box-journey-series">
            <!--General Information-->
            <div class="general-info">
                <h3>
                    General Information
                </h3>
                <div class="form-group">
                    <label>Journey Type</label>
                    <select name="journey_type" id="journey_type">
                        <option value="">--- Select Journey Type ---</option>
                        <?php if($list_journey_type['data']){
                            foreach ($list_journey_type['data'] as $jt){
                                ?>
                                <option value="<?php echo $jt->ID  ?>" data-duration="<?php echo $jt->nights ?>" <?php echo (!empty($journey_series_info->journey_type_id) && $journey_series_info->journey_type_id == $jt->ID) ? 'selected': '' ?>><?php echo $jt->post_title ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Journey Prefix</label>
                    <input type="text" placeholder="Enter journey prefix" name="prefix" value="<?php echo !empty($journey_series_info->prefix) ? $journey_series_info->prefix : ''  ?>" style="margin-bottom: 0" data-list_prefix='<?php echo json_encode($list_prefix) ?>' autocomplete="off">
                    <p style="color: orangered;display: none" class="error-prefix">Prefix has coincided</p>
                </div>
                <div class="form-group">
                    <b>Journey Duration : </b> <span class="duration" style="font-weight: bold;font-size: 15px"><?php echo (!empty($journey_type_info_current)) ? $journey_type_info_current->nights : 0 ?></span> nights
                </div>
            </div>

            <div class="item-wrapper">
                <h3>Journey Series</h3>
                <!--Single Journey-->
                <?php
                $journey_list = $objJourney->getJourneyDetailByJourneySeries($post->ID);
                $number = 2;
                if(!empty($journey_list)){
                    foreach ($journey_list as $kj => $j){
                        $journey_code = str_replace($journey_series_info->prefix,'',$j->journey_code);
                        //var_dump($journey_code);
                        $number = intval($journey_code) + 1;
                        $class = 'journey-disable';
                        if($kj == 0){
                            $class = 'journey-main';
                        }
                        ?>
                        <div class="single-journey <?php echo $class ?>">
                            <div class="form-group">
                                <label>Journey Code:</label>
                                <span style="padding-left: 20px;color: #ccaf0b;font-weight: bold"><span class="prefix"><?php echo !empty($journey_series_info->prefix) ? $journey_series_info->prefix : ''  ?></span><span class="code-number"><?php echo $journey_code ?></span></span>
                                <input class="journey-code" type="hidden" name="journey-code[]" value="<?php echo $journey_code ?>">
                            </div>
                            <div class="form-group">
                                <label>Departure:</label>
                                <input type="text" class="<?php echo $kj == 0 ? 'datepicker' : '' ?> departure" name="departure[]" placeholder="" value="<?php echo $j->departure ?>">
                            </div>
                            <div class="form-group">
                                <label>Navigation</label>
                                <select name="navigation[]" class="navigation">
                                    <?php if($kj == 0){ ?>
                                        <option value="upstream" <?php echo $j->navigation == 'upstream' ? 'selected' : '' ?>  >Upstream</option>
                                        <option value="downstream" <?php echo $j->navigation == 'downstream' ? 'selected' : '' ?> >Downstream</option>
                                    <?php }else{
                                        if($j->navigation == 'downstream'){ ?>
                                            <option value="downstream"  >Downstream</option>
                                        <?php }else{ ?>
                                            <option value="upstream"  >Upstream</option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <input type="hidden" name="journey_id[]" value="<?php echo $j->object_id ?>">

                            <?php if($kj == 0){ ?>
                                <a href="javascript:void(0)" class="edit_journey"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                            <?php }else{ ?>
                                <a href="javascript:void(0)" class="delete_journey" data-journey="<?php echo $j->object_id ?>"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
                            <?php } ?>
                        </div>
                    <?php }
                }else{
                    ?>
                    <div class="single-journey journey-main journey-main-empty">
                        <div class="form-group">
                            <label>Journey Code:</label>
                            <span style="padding-left: 20px;color: #ccaf0b;font-weight: bold"><span class="prefix"><?php echo !empty($journey_series_info->prefix) ? $journey_series_info->prefix : ''  ?></span><span class="code-number">01</span></span>
                            <input class="journey-code" type="hidden" name="journey-code[]" value="1">
                        </div>
                        <div class="form-group">
                            <label>Departure:</label>
                            <input type="text" class="datepicker departure" name="departure[]" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Navigation</label>
                            <select name="navigation[]" class="navigation">
                                <option value="upstream">Upstream</option>
                                <option value="downstream">Downstream</option>
                            </select>
                        </div>
                        <input type="hidden" name="journey_id[]" value="0">
                    </div>
                <?php } ?>

            </div>
            <div class="list-journey-remove">

            </div>

            <button class="btn-add-new-item button button-primary button-large" style="margin-top: 20px;" data-number="<?php echo $number ?>" data-prefix="<?php echo !empty($journey_series_info->prefix) ? $journey_series_info->prefix : ''  ?>">Add new Journey</button>

        </div>
        <script>
            var $ = jQuery.noConflict();
            var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
            $(document).ready(function () {
                $(document).delegate(".edit_journey", "click", function() {
                    var obj = $(this);
                    swal({   title: "Are you sure edit main journey?",
                        text: "When you edit the main journey, the journey will be lost!",
                        type: "warning",   showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, edit it!",
                        closeOnConfirm: false },
                        function(){
                            obj.closest('.journey-main').addClass('journey-main-empty');
                            $('.journey-disable .delete_journey').click();
                            obj.remove();
                            $('.btn-add-new-item').attr('data-number',2);
                            swal.close();
                        });
                });

                $('.single-journey input,select').attr('readonly',true);

                $('.btn-add-new-item').on('click', function (e) {
                    e.preventDefault();
                    var number = $(this).attr('data-number');
                    var prefix = $(this).attr('data-prefix');
                    var journey_type = $('select[name ="journey_type"]').val();

                    var navigation = $('.navigation:last').val();
                    var departure = $('.departure:last').val();

                    var  duration = $('.duration').text();
                    if(journey_type == ''){
                        alert('Please select journey type');
                    }else {
                        if(prefix == ''){
                            alert('Please enter prefix');
                        }else {
                            if(departure == ''){
                                alert('Please select departure');
                            }else {
                                $.ajax({
                                    url: ajax_url,
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        action: 'ajax_handler_journey_series',
                                        method: 'CountNewDeparture',
                                        departure: departure,
                                        duration: duration
                                    },
                                    success: function (data) {
                                        var new_departure = '';
                                        if(data){
                                            new_departure = data;
                                            var html = singleJourneySeries(prefix,number,navigation,new_departure);
                                            $('.item-wrapper').append(html);

                                            $('.journey-main-empty').append('<a href="javascript:void(0)" class="edit_journey"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>');
                                            $('.journey-main').removeClass('journey-main-empty');
                                        }
                                    }
                                });
                                $(this).attr('data-number',(parseInt(number) + 1));
                            }
                        }
                    }
                });

                $(document).delegate(".delete_journey", "click", function() {
                    var obj = $(this);
                    var journey_id = obj.attr('data-journey');
                    if(journey_id != 0){
                        $('.list-journey-remove').append('<input type="hidden" name="journey_remove[]" value="'+journey_id+'">');
                    }
                    obj.closest('.single-journey').remove();
                });
                $(document).delegate(".datepicker", "hover", function() {
                    $(this).datepicker({
                        changeYear: true,
                        dateFormat: 'yy-mm-dd'
                    });
                });

                $('#journey_type').change(function () {
                    var obj = $(this);
                    var duration = obj.find('option:selected').attr('data-duration');
                    $('.duration').text(duration);
                });

                $(document).delegate("input[name='prefix']", "keyup change", function() {
                    var obj = $(this);
                    var prefix = obj.val();
                    var list_prefix = obj.attr('data-list_prefix');

                    var is_prefix_true = true;

                    if(list_prefix){
                        list_prefix = JSON.parse(list_prefix);
                        var in_arr = $.inArray( prefix, list_prefix );
                        if(in_arr != -1){
                            is_prefix_true = false;
                        }
                    }

                    if(is_prefix_true){
                        $('.prefix').text(prefix);
                        $('.btn-add-new-item').attr('data-prefix',prefix);
                        $('.error-prefix').fadeOut();
                    }
                    else{
                        $('.error-prefix').fadeIn();
                    }

                });

            });

            function singleJourneySeries(prefix,number,navigation,departure) {
                var  text_number = number;
                var html = '';

                html += '<div class="single-journey journey-disable">' +
                    '<div class="form-group">' +
                    '<label>Journey Code:</label>' +
                    '<span style="padding-left: 20px;color: #ccaf0b;font-weight: bold"><span class="prefix">'+prefix+'</span><span class="code-number">'+text_number+'</span></span> ' +
                    '<input class="journey-code" type="hidden" name="journey-code[]" value="'+number+'">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Departure:</label>' +
                    '<input type="text" class="departure" readonly name="departure[]" placeholder="" value="'+departure+'">' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label>Navigation</label>' +
                    '<select name="navigation[]" class="navigation">' ;
                if(navigation == 'upstream'){
                    html += '<option value="downstream" selected>Downstream</option>' ;
                }else {
                    html += '<option value="upstream" selected>Upstream</option>' ;
                }

                html +='</select>' +
                    '</div>' +
                    '<input type="hidden" name="journey_id[]" value="0">' +
                    '<a href="javascript:void(0)" class="delete_journey" data-journey="0"><i class="fa fa-times-circle" aria-hidden="true"></i></a>' +
                    '</div>';

                return html;
            }
        </script>

        <?php
    }


    public function save()
    {
        global $post;
        if(!empty($post) && $post->post_type == 'journey_series') {
            if(!empty($_POST['prefix']) && !empty($_POST['journey_type'])) {
                $objJourneySeries = JourneySeries::init();
                $objJourney = Journey::init();
                $data_journey_series_info = array(
                    'object_id' => $_POST['post_ID'],
                    'journey_type_id' => $_POST['journey_type'],
                    'prefix' => $_POST['prefix'],
                );
                $objJourneySeries->saveJourneySeriesInfo($data_journey_series_info);
                $journey_type = get_post($_POST['journey_type']);


                if(!empty($_POST['journey-code'])){
                    $objJourney->deleteJourneyDetail($_POST['post_ID']);
                    if(!empty($_POST['journey_remove']) && is_array($_POST['journey_remove'])){
                        foreach ($_POST['journey_remove'] as $jr){
                            $objJourney->deleteJourney($jr);
                        }
                    }
                    foreach ($_POST['journey-code'] as $k => $code){
                        $journey_id = $_POST['journey_id'][$k];

                        if($journey_id == 0){
                            $args_post = array(
                                'post_title' => $_POST['prefix'].$code .' - '.$journey_type->post_title,
                                'post_name' => $journey_type->post_name .'-'.sanitize_title($_POST['prefix'].$code),
                                'post_author' => get_current_user_id(),
                                'post_status' => 'publish',
                                'post_type' => 'journey',
                                'post_date' => current_time('mysql')
                            );
                            remove_action('save_post', [$this, 'save']);
                            $journey_id = $objJourney->insertJourney($args_post);
                            add_action('save_post', [$this, 'save']);
                        }

                        $argc = array(
                            'object_id' =>$journey_id,
                            'journey_series_id' =>$_POST['post_ID'],
                            'journey_code' => $_POST['prefix'].$code,
                            'departure' => $_POST['departure'][$k],
                            'navigation' =>$_POST['navigation'][$k],
                        );

                        $objJourney->insertJourneyDetail($argc);
                    }

                }
            }
        }
    }

}