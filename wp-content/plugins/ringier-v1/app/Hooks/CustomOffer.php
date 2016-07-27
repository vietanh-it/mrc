<?php
namespace RVN\Hooks;

use RVN\Library\CPTColumns;
use RVN\Models\JourneyType;
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
        add_meta_box('journey_type_and_room_type', 'Journey Type and Room type ', [$this, 'show'], 'offer', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        $JourneyType = JourneyType::init();
        $Ship = JourneyType::init();

        $list_journey_type = $JourneyType->getJourneyTypeList(array('limit' =>10000));
        /*foreach ($list_journey_type['data'] as $jt){
            var_dump(json_encode($jt->ship_info->room_types));
        }*/
        ?>

        <style>

        </style>

        <form class="add_journey_type_and_room_type">
            <select name="journey_type" id="journey_type">
                <option value=""> --- Select journey type --- </option>
                <?php foreach ($list_journey_type['data'] as $jt){
                    $room_types = json_encode($jt->ship_info->room_types);
                    ?>
                    <option value="<?php echo $jt->ID ?>" data-ship = '<?php echo $jt->ship_info->ID ?>' data-room_types = '<?php echo $room_types ?>'> <?php echo $jt->post_title ?></option>
                    <?php
                } ?>
            </select>
            <div class="ctn_room_types">

            </div>
        </form>


        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                $('#journey_type').change(function () {
                    var id = $(this).val();
                    if(id !=''){
                        var room_types = $(this).find('option:selected').attr('data-room_types');
                        var html = '';
                        if(room_types){
                            room_types = JSON.parse(room_types);
                        }
                        $.each(room_types, function(key, value) {
                            html += '<input type="checkbox" name="room_type" value="'+value.id+'"> '+value.room_type_name+'<br>';
                        });
                        console.log(html);

                        $('.ctn_room_types').html(html);
                    }
                })
            });
        </script>

        <?php
    }

    public function save()
    {

    }

}