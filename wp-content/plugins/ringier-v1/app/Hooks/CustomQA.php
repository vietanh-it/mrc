<?php
namespace RVN\Hooks;

use RVN\Controllers\ShipController;
use RVN\Library\CPTColumns;
use RVN\Models\JourneyType;
use RVN\Models\Posts;
use RVN\Models\Ships;

class CustomQA
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomQA();
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
        add_meta_box('qa', 'Q&A list', [$this, 'show'], 'page', 'normal', 'high');
    }



    public function show()
    {
        global $post;

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

        <div class="ctn-box-day">
            <?php
            $list_qa = get_post_meta($post->ID,'list_qa',true);
            if(!empty($list_qa)){
                $list_qa_new = unserialize($list_qa);
                foreach ($list_qa_new as $v){ ?>
                    <div class="box-day">
                        <div class="class-show-all" style="">
                            <div class="form-group">
                                <label for="question">Question : </label>
                                <input type="text" class="form-control day_name_key" placeholder="" value="<?php echo $v['question'] ?>" name="question[]">
                            </div>
                            <div class="form-group">
                                <label for="answer">Answer : </label>
                                <textarea class="form-control" rows="10" name="answer[]"><?php echo $v['answer'] ?></textarea>
                            </div>
                            <a href="javascript:void" class="delete_day">Delete Q&A</a>
                        </div>

                        <a href="javascript:void(0)" class="icon-show-hide-day hide-day" title="Hide" >
                            <i class="fa fa-sort-asc" aria-hidden="true"></i>
                        </a>
                        <a href="javascript:void(0)" class="icon-show-hide-day show-day" title="Show more" style="display: none">
                            <i class="fa fa-sort-desc" aria-hidden="true"></i>
                        </a>
                    </div>
                <?php }
            }else{ ?>
                <div class="box-day">
                    <div class="class-show-all" style="">
                        <div class="form-group">
                            <label for="question">Question : </label>
                            <input type="text" class="form-control day_name_key" placeholder="" name="question[]">
                        </div>
                        <div class="form-group">
                            <label for="answer">Answer : </label>
                            <textarea class="form-control" rows="10" name="answer[]"></textarea>
                        </div>
                        <a href="javascript:void" class="delete_day">Delete Q&A</a>
                    </div>

                    <a href="javascript:void(0)" class="icon-show-hide-day hide-day" title="Hide" >
                        <i class="fa fa-sort-asc" aria-hidden="true"></i>
                    </a>
                    <a href="javascript:void(0)" class="icon-show-hide-day show-day" title="Show more" style="display: none">
                        <i class="fa fa-sort-desc" aria-hidden="true"></i>
                    </a>
                </div>
            <?php }
            ?>
        </div>
        <a href="javascript:void" class="add_new_day">Add new Q&A</a>


        <script>
            var $ = jQuery.noConflict();
            jQuery(document).ready(function ($) {

                var html = '<div class="box-day"> ' +
                    '<div class="class-show-all" style=""> ' +
                    '<div class="form-group"> ' +
                    '<label for="question">Question : </label> ' +
                    '<input type="text" class="form-control day_name_key" placeholder="" name="question[]"> ' +
                    '</div> ' +
                    '<div class="form-group"> ' +
                    '<label for="answer">Answer : </label> ' +
                    '<textarea class="form-control" rows="10" name="answer[]"></textarea> ' +
                    '</div> ' +
                    '<a href="javascript:void" class="delete_day">Delete Q&A</a> ' +
                    '</div> ' +
                    '<a href="javascript:void(0)" class="icon-show-hide-day hide-day" title="Hide" > ' +
                    '<i class="fa fa-sort-asc" aria-hidden="true"></i> ' +
                    '</a> ' +
                    '<a href="javascript:void(0)" class="icon-show-hide-day show-day" title="Show more" style="display: none"> ' +
                    '<i class="fa fa-sort-desc" aria-hidden="true"></i> ' +
                    '</a> ' +
                    '</div>';
                $('.add_new_day').click(function () {
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
            });

        </script>
        <?php
    }

    public function save()
    {
        global $post;
        if($post->post_type == 'page') {
            if(!empty($_POST)){
                if(!empty($_POST['question'] && !empty($_POST['answer']))){
                    $args = array();
                    foreach ($_POST['question'] as $k => $question ){
                        $args[] = array(
                            'question' => $question,
                            'answer' => $_POST['answer'][$k],
                        );
                    }

                    $list_qa = get_post_meta($_POST['post_ID'],'list_qa',true);
                    if($list_qa){
                        update_post_meta($_POST['post_ID'],'list_qa',serialize($args));
                    }else{
                        add_post_meta($_POST['post_ID'],'list_qa',serialize($args));
                    }
                }

            }
        }

    }

}