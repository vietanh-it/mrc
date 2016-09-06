<?php
namespace RVN\Hooks;

use RVN\Models\Journey;

class Rewrite {
    private static $instance;

    public static function init()
    {
        if ( ! isset( self::$instance )) {
            self::$instance = new Rewrite();
        }

        return self::$instance;
    }

    function __construct(){
        add_action( 'init', array($this, 'rewrite'), 8, 0 );

        add_filter('wpseo_breadcrumb_links', array($this,'marry_wpseo_breadcrumb_links'), 10, 1);

    }

    public function rewrite(){
        // danh cho wedding day
        /*$slug = 'wedding-day';
        add_rewrite_rule(
            $slug . '/gian-hang/?$',
            'index.php?pagename='. $slug ,
            'top'
        );*/

        // directory

    }


    function marry_wpseo_breadcrumb_links($links)
    {
        if (is_singular()) {
            $post = end($links);
            $postId = $post['id'];
 
            $postObject = get_post($postId);

            switch ($postObject->post_type) {

                case 'journey':
                    $Journey  = Journey::init();
                    $journey_info = $Journey->getInfo($postObject);
                    $link_lv1 = array(
                        'url' => '/journeys/',
                        'text' => 'journeys'
                    );

                    $link_lv2 = array(
                        'url' => $journey_info->journey_type_info->permalink,
                        'text' => $journey_info->journey_type_info->post_title
                    );

                    $last_link = array_pop($links);
                    array_push($links, $link_lv1, $link_lv2, $last_link);
                    break;
                case 'journey_type':
                    $link_lv1 = array(
                        'url' => '/journeys/',
                        'text' => 'journeys'
                    );

                    $last_link = array_pop($links);
                    array_push($links, $link_lv1, $last_link);
                    break;
            }
        }
        return $links;
    }

}