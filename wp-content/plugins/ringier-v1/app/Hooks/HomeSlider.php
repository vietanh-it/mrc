<?php
namespace RVN\Hooks;

class HomeSlider
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new HomeSlider();
        }

        return self::$instance;
    }

    function __construct()
    {

        add_action('admin_menu', array($this,'add_menu_items'));
    }

    public function add_menu_items(){
        add_menu_page(
            'Home Slider And Cover',
            'Home Slider And Cover',
            'create_users',
            'home_slider',
            [$this, 'home_slider_show'],
            '',
            6
        );
    }

    public function home_slider_show(){
        $slider_page = get_page_by_path(PAGE_HOME_SLIDER_SLUG);
        if(!empty($slider_page)){
            wp_redirect(WP_SITEURL.'/wp-admin/post.php?post='.$slider_page->ID.'&action=edit');
        }
    }

}



