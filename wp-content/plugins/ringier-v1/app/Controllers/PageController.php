<?php
namespace RVN\Controllers;

use RVN\Models\Offer;
use RVN\Models\Posts;
use RVN\Models\Ships;

class PageController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PageController();
        }

        return self::$instance;
    }

    public function defaultPage(){

        view('page/default');
    }

    public function pageListPost($post_type){
        $page = get_query_var("paged");
        if(empty($page)) $page =1;

        $args = array(
            'posts_per_page' => 6,
            'paged' => $page,
            'post_type' => $post_type,
            'is_paging' => 1,
        );

        $Post = Posts::init();
        $list_post = $Post->getList($args);

        view('page/list-post',compact('list_post'));
    }
}
