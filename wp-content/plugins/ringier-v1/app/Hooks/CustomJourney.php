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

        add_action('add_meta_boxes', [$this, 'journey_list']);
        add_action('save_post', [$this, 'save']);
    }


    public function journey_list()
    {
        add_meta_box('journey_list', 'Journey Series', [$this, 'show'], 'journey', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        ?>

        <style>

        </style>

        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {

            });
        </script>

        <?php
    }

    public function save()
    {

    }

}