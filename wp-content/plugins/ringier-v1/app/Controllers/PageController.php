<?php
namespace RVN\Controllers;

use RVN\Models\Offer;
use RVN\Models\Posts;
use RVN\Models\Ships;
use RVN\Models\Users;

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

    public function pageQA(){
        global  $post;

        $list_qa = get_post_meta($post->ID,'list_qa',true);
        view('page/qa',compact('list_qa'));
    }

    public function pageContact(){

        view('page/contact');
    }

    public function pageWhyUs(){
        view('page/why-us');
    }

    public function pageReferFriend(){
        $User = Users::init();
        $list_refer = $User->getListUserReferBY(get_current_user_id());

        view('page/refer-friend',compact('list_refer'));
    }
}
