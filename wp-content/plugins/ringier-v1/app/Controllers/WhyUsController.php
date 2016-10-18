<?php
namespace RVN\Controllers;

use RVN\Models\Offer;
use RVN\Models\Posts;
use RVN\Models\Ships;

class WhyUsController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new WhyUsController ();
        }

        return self::$instance;
    }

    public function listWhyUs(){
        $Post = Posts::init();

        $args = array(
            'posts_per_page' => 20,
            'post_type' => 'why_us',
        );
        $list_post = $Post->getList($args);

        view('why-us/list',compact('list_post'));
    }

    public function detail($id){
        $Post = Posts::init();

        $list_related = $Post->getList(array(
            'posts_per_page' => 5,
            'post_type' => 'why_us',
            'post__not_in' => array($id),
        ));

        view('why-us/detail',compact('list_related'));
    }
}
