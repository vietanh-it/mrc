<?php
namespace RVN\Controllers;

use RVN\Models\Offer;
use RVN\Models\Posts;
use RVN\Models\Ships;

class ResourceController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ResourceController ();
        }

        return self::$instance;
    }

    public function listResource(){
        $page = get_query_var("paged");
        if(empty($page)) $page =1;

        $args = array(
            'posts_per_page' => 6,
            'paged' => $page,
            'post_type' => 'resource',
            'is_paging' => 1,
        );

        $Post = Posts::init();
        $list_post = $Post->getList($args);

        view('resource/list',compact('list_post'));
    }

    public function detail($id){
        $Post = Posts::init();
        $list_related = $Post->getList(array(
            'posts_per_page' => 5,
            'post_type' => 'resource',
            'post__not_in' => array($id),
        ));

        view('resource/detail',compact('list_related'));
    }
}
