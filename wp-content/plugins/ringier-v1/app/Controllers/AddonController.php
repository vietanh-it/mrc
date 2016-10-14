<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 14-Aug-16
 * Time: 11:09 AM
 */

namespace RVN\Controllers;

use RVN\Models\Addon;
use RVN\Models\Posts;

class AddonController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

        add_action("wp_ajax_ajax_handler_addon", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_addon", [$this, "ajaxHandler"]);
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new AddonController();
        }

        return self::$instance;
    }


    public function ajaxGetAddonList($args)
    {
        $m_addon = Addon::init();
        $result = $m_addon->getList($args);

        return [
            'status' => 'success',
            'data'   => $result['data']
        ];
    }

    public function tour_addon_detail(){
        $Post = Posts::init();
        global $post;

        $list_related = $Post->getList(array(
            'posts_per_page' => 5,
            'post_type' => $post->post_type,
            'post__not_in' => array($post->ID),
        ));

        view('tour-addon/detail',compact('list_related'));
    }

}