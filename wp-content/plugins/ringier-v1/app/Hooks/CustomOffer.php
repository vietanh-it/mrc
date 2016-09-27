<?php
namespace RVN\Hooks;

use RVN\Models\Journey;
use RVN\Models\JourneySeries;
use RVN\Models\JourneyType;
use RVN\Models\Offer;

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


    protected function __construct()
    {
        add_action('add_meta_boxes', [$this, 'box']);
        add_action('save_post', [$this, 'save']);
    }


    public function box()
    {
        add_meta_box('journey_type_and_room_type', 'Journey To Apply Offer', [
            $this,
            'show'
        ], 'offer', 'normal', 'high');
    }


    public function show()
    {
        global $post;

        // Object initialize
        $m_journey_type = JourneyType::init();
        $m_offer = Offer::init();
        $m_journey = Journey::init();
        $m_journey_series = JourneySeries::init();

        // Initialize
        $offer_info = [];
        $journey_info = [];
        $journey_list = [];
        $promotion = 0;
        if (!empty($post)) {
            $offer_info = $m_offer->getOfferDetailList($post);

            if (!empty($offer_info)) {
                $journey_info = $m_journey->getInfo($offer_info[0]->journey_id);
                $journey_list = $m_journey->getJourneyList([
                    'journey_type_id'    => $journey_info->journey_type_info->ID,
                    'is_exclude_offered' => true,
                    'not_exclude'        => $offer_info[0]->journey_id
                ]);
                $journey_series_list = $m_journey_series->getList(['journey_type_id' => $journey_info->journey_type_info->ID]);
                $promotion = $offer_info[0]->promotion;
            }
        }

        // Journey type list
        $journey_type_list = $m_journey_type->getJourneyTypeList(); ?>


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

            .wrapper {
                padding: 20px;
                background: #ffe28d;
                text-align: center;
            }

            .row {
                margin-bottom: 20px;
            }

            .col-md-6 {
                width: 50%;
            }

            .form-control {
                color: #444;
                line-height: 28px;
                background: #ffffff;
                border: 1px solid #aaa;
                border-radius: 4px;
            }

            #journey_type_and_room_type label {
                font-weight: bold;
                padding-right: 10px;
            }

            .select2 {
                min-width: 320px;
            }

            .journey-info {
                display: none;
            }

            .table {
                margin: 0 auto;
            }

            .table th, .table td {
                text-align: left;
            }

            .table td {
                padding-left: 10px;
            }

            .text-center {
                text-align: center;
            }

            #normal-sortables .postbox .submit {
                float: none;
                text-align: center;
            }

            .room-type-wrapper {
                display: none;
            }
        </style>

        <div class="wrapper">

            <div class="row">
                <h3>Select Journey</h3>
            </div>

            <!--Journey Type-->
            <div class="row">
                <select name="journey_type" id="select_journey_type" class="select2">
                    <option value="">--- Select Journey Type ---</option>
                    <?php if (!empty($journey_type_list['data'])) {
                        foreach ($journey_type_list['data'] as $key => $item) {
                            if (!empty($journey_info) && $item->ID == $journey_info->journey_type_info->ID) {
                                $selected = 'selected';
                            }
                            else {
                                $selected = '';
                            }

                            echo "<option value='{$item->ID}' {$selected}>{$item->post_title}</option>";
                        }
                    } ?>
                </select>
            </div>


            <!--Journey Series-->
            <div class="row">
                <select name="journey_series" id="select_journey_series" class="select2">
                    <option value="">--- Select Journey Series ---</option>
                    <?php if (!empty($journey_info) && !empty($journey_series_list)) {
                        foreach ($journey_series_list as $k => $v) {
                            if ($v->ID == $journey_info->journey_series_id) {
                                $selected = 'selected';
                            }
                            else {
                                $selected = '';
                            }

                            echo "<option value='{$v->ID}' {$selected}>{$v->post_title}</option>";
                        }
                    } ?>
                </select>
            </div>


            <!--Journey-->
            <div class="row">
                <select name="journey" id="select_journey" class="select2">
                    <option value="">--- Select Journey ---</option>
                    <?php if (!empty($journey_info) && !empty($journey_list['data'])) {
                        foreach ($journey_list['data'] as $k => $v) {
                            if ($v->ID == $journey_info->ID) {
                                $selected = 'selected';
                            }
                            else {
                                $selected = '';
                            }

                            echo "<option value='{$v->ID}' {$selected}>{$v->post_title}</option>";
                        }
                    } ?>
                </select>
            </div>


            <!--Journey Info-->
            <?php if (!empty($journey_info)) {
                $display = 'block';
            }
            else {
                $display = 'none';
            } ?>


            <!--Journey Info-->
            <div class="row journey-info" style="display: <?php echo $display; ?>">
                <h3>Journey Info</h3>
                <table class="table">
                    <tr>
                        <th>Departure</th>
                        <td class="journey-departure">
                            <?php if (!empty($journey_info)) {
                                echo $journey_info->departure_fm;
                            } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Navigation</th>
                        <td class="journey-navigation">
                            <?php if (!empty($journey_info)) {
                                echo $journey_info->navigation;
                            } ?>
                        </td>
                    </tr>
                </table>
            </div>


            <!--Room Types-->
            <div class="row room-type-wrapper" style="display: <?php echo $display; ?>">
                <h3>Select Room Types</h3>
                <div class="row room-type">
                    <?php if (!empty($journey_info)) {
                        $room_types = $journey_info->journey_type_info->ship_info->room_types;

                        // Get array room type id in offer
                        $offer_room_type_id = [];
                        foreach ($offer_info as $k => $v) {
                            array_push($offer_room_type_id, $v->room_type_id);
                        }

                        foreach ($room_types as $k => $v) {
                            $checked = (in_array($v->id, $offer_room_type_id)) ? 'checked' : '';
                            echo "<label>{$v->room_type_name} <input type='checkbox' name='room_type[]' value='{$v->id}' {$checked}></label>";
                        }
                    } ?>
                </div>
            </div>


            <!--Promotion-->
            <div class="row" style="display: <?php echo $display; ?>;">
                <label>Promotion (%)</label>
                <input id="promotion" type="number" name="promotion" value="<?php echo $promotion; ?>" min="0">
            </div>

            <div class="row">
                <?php submit_button('Save'); ?>
            </div>

            <input id="journey_id" type="hidden" name="journey_id"
                   value="<?php echo empty($offer_info) ? '' : $offer_info[0]->journey_id; ?>">

        </div>

        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                var ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
                var select_journey_series = $('#select_journey_series');
                var select_journey = $('#select_journey');

                // Journey Type
                $(document).delegate('#select_journey_type', 'change', function () {
                    $('.journey-info').hide();
                    $('#promotion').parent('.row').hide();

                    var id = $(this).val();
                    if (id) {

                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_journey_series',
                                method: 'GetJourneySeries',
                                journey_type_id: id
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (resp) {
                                switch_loading(false);
                                if (resp.status == 'success') {
                                    var optionHtml = '<option value="">--- Select Journey Series ---</option>';
                                    $.each(resp.data, function (k, v) {
                                        optionHtml += '<option value="' + v.ID + '">' + v.post_title + '</option>';
                                    });
                                    select_journey_series.html(optionHtml);
                                }
                            }
                        });
                        // end ajax

                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_journey',
                                method: 'GetJourneys',
                                journey_type_id: id,
                                is_exclude_offered: true
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (resp) {
                                switch_loading(false);
                                if (resp.status == 'success') {
                                    var optionHtml = '<option value="">--- Select Journey ---</option>';
                                    $.each(resp.data, function (i, j) {
                                        optionHtml += '<option value="' + j.ID + '">' + j.post_title + '</option>';
                                    });
                                    select_journey.html(optionHtml);
                                }
                            }
                        });
                        // end ajax

                    }

                });


                // Journey Series
                $(document).delegate('#select_journey_series', 'change', function () {
                    $('.journey-info').hide();
                    $('#promotion').parent('.row').hide();

                    var id = $(this).val();
                    if (id) {

                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_journey',
                                method: 'GetJourneys',
                                journey_series_id: id,
                                is_exclude_offered: true
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (resp) {
                                switch_loading(false);
                                if (resp.status == 'success') {
                                    var optionHtml = '<option value="">--- Select Journey ---</option>';
                                    $.each(resp.data, function (k, v) {
                                        optionHtml += '<option value="' + v.ID + '">' + v.post_title + '</option>';
                                    });
                                    select_journey.html(optionHtml);
                                }
                            }
                        });
                        // end ajax

                    }

                });


                // Journey
                $(document).delegate('#select_journey', 'change', function () {
                    var id = $(this).val();
                    if (id) {
                        $('#journey_id').val(id);

                        $.ajax({
                            url: ajax_url,
                            type: 'post',
                            dataType: 'json',
                            data: {
                                action: 'ajax_handler_journey',
                                method: 'GetJourneyInfo',
                                object_id: id
                            },
                            beforeSend: function () {
                                switch_loading(true);
                            },
                            success: function (resp) {
                                switch_loading(false);
                                if (resp.status == 'success') {

                                    var ship_info = resp.data.journey_type_info.ship_info;
                                    var room_types = ship_info.room_types;
                                    var room_type_html = '';
                                    $.each(room_types, function (k, v) {
                                        room_type_html += '<label>' + v.room_type_name + ' <input type="checkbox" name="room_type[]" value="' + v.id + '"></label>'
                                    });
                                    $('.room-type').html(room_type_html);


                                    // Show room types
                                    $('.room-type-wrapper').fadeIn();

                                    // Journey info
                                    $('.journey-departure').html(resp.data.departure_fm);
                                    $('.journey-navigation').html(resp.data.navigation);

                                    // Show promotion input
                                    $('.journey-info').fadeIn();
                                    $('#promotion').attr('disabled', false);
                                    $('#promotion').parent('.row').fadeIn();
                                }
                            }
                        });
                        // end ajax
                    } else {
                        $('.journey-info').hide();
                    }
                });


            });
        </script>

        <?php
    }


    public function save()
    {
        global $post;
        if (!empty($post) && $post->post_type == 'offer') {

            $promotion = valueOrNull($_POST['promotion'], 0);
            $room_type = valueOrNull($_POST['room_type'], []);
            $journey_id = $_POST['journey_id'];

            $objOffer = Offer::init();

            // Clear current offer
            $objOffer->deleteOffer($post->ID);

            // Create new
            foreach ($room_type as $k => $v) {
                $objOffer->saveOffer([
                    'promotion'    => $promotion,
                    'object_id'    => $post->ID,
                    'journey_id'   => $journey_id,
                    'room_type_id' => $v
                ]);
            }

        }
    }

}