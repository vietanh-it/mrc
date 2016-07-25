<?php
namespace RVN\Controllers;

use RVN\Models\Posts;

class RiverController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new RiverController();
        }

        return self::$instance;
    }

    public function getRiverList()
    {
        $post = Posts::init();
        $result = $post->getList(array(
            'post_type' => 'river',
        ));

        return $result;
    }

}
