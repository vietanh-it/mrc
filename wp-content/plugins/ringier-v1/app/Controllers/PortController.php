<?php
namespace RVN\Controllers;

use RVN\Models\Posts;

class PortController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PortController();
        }

        return self::$instance;
    }


    public function getPortList()
    {
        $post = Posts::init();
        $result = $post->getList(array(
            'post_type' => 'port',
        ));

        return $result;

    }

}
