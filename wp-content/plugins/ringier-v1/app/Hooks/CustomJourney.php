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

        add_action('add_meta_boxes', array($this, 'add_journey_detail'));
        add_action('save_post', array($this, 'save'));
    }


    public function add_journey_detail()
    {
        add_meta_box('journey_detail', 'Journey Detail', array($this, 'show'), 'journey', 'normal', 'high');
    }


    public function show()
    { ?>

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

        </style>
        <?php
        global $post;
        global $wpdb;
        $Posts = Posts::init();

        $list_journey_type = $Posts->getList(array(
            'post_status'    => array('publish'),
            'posts_per_page' => DEFAULT_LIMIT,
            'paged'          => 1,
            'post_type'      => 'journey_type',
            'is_cache'       => false
        ));
        $current_ship = get_post_meta($post->ID, '_ship', true);

        if ($list_journey_type['data']) { ?>
            <div class="box-detail">
                <label for="_ship"> Ship </label>
                <select name="_ship" id="_ship">
                    <option value=""> --- Select ship ---</option>
                    <?php foreach ($list_journey_type['data'] as $ship) { ?>
                        <option
                            value="<?php echo $ship->ID ?>" <?php echo $current_ship == $ship->ID ? "selected" : "" ?>> <?php echo $ship->post_title ?> </option>
                    <?php } ?>
                </select>
            </div>
        <?php }

    }

    public function save()
    {
        global $wpdb;

        if (!empty($_POST['_destination'])) {
            $_destination = get_post_meta($_POST['post_ID'], '_destination', true);

            if ($_destination) {
                update_post_meta($_POST['post_ID'], '_destination', $_POST['_destination']);
            } else {
                add_post_meta($_POST['post_ID'], '_destination', $_POST['_destination']);
            }
        }

        if (!empty($_POST['_ship'])) {
            $_destination = get_post_meta($_POST['post_ID'], '_ship', true);

            if ($_destination) {
                update_post_meta($_POST['post_ID'], '_ship', $_POST['_ship']);
            } else {
                add_post_meta($_POST['post_ID'], '_ship', $_POST['_ship']);
            }
        }


        if (!empty($_POST['_port'])) {
            $_destination = get_post_meta($_POST['post_ID'], '_port', true);

            if ($_destination) {
                update_post_meta($_POST['post_ID'], '_port', $_POST['_port']);
            } else {
                add_post_meta($_POST['post_ID'], '_port', $_POST['_port']);
            }
        }

    }


}