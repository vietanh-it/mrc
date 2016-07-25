<?php
namespace RVN\Controllers;

use RVN\Models\Posts;

class DestinationController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

        add_action("wp_ajax_ajax_handler_ship", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_ship", [$this, "ajaxHandler"]);
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DestinationController();
        }

        return self::$instance;
    }

    public function getDestinationList()
    {
        $post = Posts::init();
        $result = $post->getList(array(
            'post_type' => 'destination',
        ));

        return $result;

    }

}
