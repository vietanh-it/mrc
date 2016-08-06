<?php
namespace RVN\Hooks;

use RVN\Library\CPTColumns;
use RVN\Models\JourneyType;
use RVN\Models\Posts;

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
            <textarea class="form-control" rows="5" name="include" ><?php echo !empty($jt_info->include) ? $jt_info->include :''  ?></textarea>
        </div>
    <?php }

    public function show()
    {
        global $post;
        $objJourneyType = JourneyType::init();
        $jt_info = $objJourneyType->getInfo($post->ID);
        ?>
        <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
        <style>
            .box-day {
                border: 1px solid #ccc;
                padding: 20px;
                margin-bottom: 20px;
                background: #faf7f2;
            }

            .box-day input, .box-day textarea {
                width: 100%;
                padding: 7px;
            }

            .box-day label {
                font-weight: bold;
            }

            .box-day .form-group {
                margin-bottom: 20px;
            }

        </style>

        <?php if ($jt_info->itinerary) {
        $list = unserialize($jt_info->itinerary);
        foreach ($list as $v) {
            //var_dump($v);?>
            <div class="ctn-box-day">
                <div class="box-day">
                    <div class="form-group">
                        <label for="day_name">Day : </label>
                        <input type="number" class="form-control" placeholder="" name="day_name[]" value="<?php echo $v['day_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="day_port">Location</label>
                        <input type="text" class="form-control" placeholder="" name="day_port[]" value="<?php echo $v['day_port']?>">
                    </div>
                    <div class="form-group">
                        <label for="day_content">Content</label>
                        <textarea class="form-control" rows="5" name="day_content[]" ><?php echo $v['day_content'] ?></textarea>
                    </div>

                    <a href="javascript:void" class="delete_day">Delete day</a>
                </div>
            </div>
        <?php }
    } else { ?>
        <div class="ctn-box-day">
            <div class="box-day">
                <div class="form-group">
                    <label for="day_name">Day : </label>
                    <input type="number" class="form-control" placeholder="" name="day_name[]">
                </div>
                <div class="form-group">
                    <label for="day_port">Location</label>
                    <input type="text" class="form-control" placeholder="" name="day_port[]">
                </div>
                <div class="form-group">
                    <label for="day_content">Content</label>
                    <textarea class="form-control" rows="5" name="day_content[]"></textarea>
                </div>

                <a href="javascript:void" class="delete_day">Delete day</a>
            </div>
        </div>

    <?php } ?>


        <a href="javascript:void" class="add_new_day">Add new day</a>


        <script>
            var $ = jQuery.noConflict();
            jQuery(document).ready(function ($) {
                var html = '<div class="box-day"> ' +
                    '<div class="form-group"> ' +
                    '<label for="day_name">Day : </label> ' +
                    '<input type="number" class="form-control"  placeholder="" name="day_name[]"> ' +
                    '</div> ' +
                    '<div class="form-group"> ' +
                    '<label for="day_port">Location</label> ' +
                    '<input type="text" class="form-control"  placeholder="" name="day_port[]"> ' +
                    '</div> ' +
                    '<div class="form-group"> ' +
                    '<label for="day_content">Content</label> ' +
                    '<textarea class="form-control" rows="5" name="day_content[]"></textarea> ' +
                    '</div> ' +
                    ' <a href="javascript:void" class="delete_day">Delete day</a>' +
                    '</div>';
                $('.add_new_day').click(function () {
                    $('.ctn-box-day').append(html);
                });

                $(document).delegate('.delete_day', 'click', function () {
                    $(this).closest('.box-day').remove();
                });
            });

        </script>
        <?php
    }

    public function save()
    {
        if (!empty($_POST['day_name']) && !empty($_POST['day_content'])) {
            $data = array();
            foreach ($_POST['day_name'] as $k => $v) {

                $data[] = array(
                    'day_name' => $v,
                    'day_port' => $_POST['day_port'][$k],
                    'day_content' => $_POST['day_content'][$k],
                );
            }
            if(!empty($_POST['include'])){
                $include = $_POST['include'];
            }else{
                $include = '';
            }

            $objJourneyType = JourneyType::init();

            $args = array(
                'itinerary' => serialize($data),
                'include' => $include,
            );
            $objJourneyType->saveJourneyTypeInfo($_POST['post_ID'], $args);
        }

    }

}