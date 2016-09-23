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
        $Post = Posts::init();

        $args = array(
            'posts_per_page' => 6,
            'paged' => $page,
            'post_type' => 'resource',
            'is_paging' => 1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'resource_category',
                    'field'    => 'slug',
                    'terms'    => 'human-resources',
                    'operator' => 'NOT IN',
                ),
            )
        );
        $list_post = $Post->getList($args);

        $list_rs_people =  $Post->getList(array(
            'posts_per_page' => 5,
            'paged' => $page,
            'post_type' => 'resource',
            'tax_query' => array(
                array(
                    'taxonomy' => 'resource_category',
                    'field'    => 'slug',
                    'terms'    => 'human-resources',
                ),
            ),
        ));

        $list_rs_services =  $Post->getList(array(
            'posts_per_page' => 3,
            'paged' => $page,
            'post_type' => 'resource',
            'tax_query' => array(
                array(
                    'taxonomy' => 'resource_category',
                    'field'    => 'slug',
                    'terms'    => 'onboard-services',
                ),
            ),
        ));
        $list_rs_food =  $Post->getList(array(
            'posts_per_page' => 6,
            'paged' => $page,
            'post_type' => 'resource',
            'tax_query' => array(
                array(
                    'taxonomy' => 'resource_category',
                    'field'    => 'slug',
                    'terms'    => 'food-and-beverage',
                ),
            ),
        ));

        view('resource/list',compact('list_post','list_rs_people','list_rs_services','list_rs_food'));
    }

    public function detail($id){
        $Post = Posts::init();
        $terms = wp_get_post_terms( $id, 'resource_category' );
        if(!empty($terms)) $terms = array_shift($terms);

        $list_related = $Post->getList(array(
            'posts_per_page' => 5,
            'post_type' => 'resource',
            'post__not_in' => array($id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'resource_category',
                    'field'    => 'term_id',
                    'terms'    => $terms->term_id,
                ),
            )
        ));

        view('resource/detail',compact('list_related'));
    }
}
