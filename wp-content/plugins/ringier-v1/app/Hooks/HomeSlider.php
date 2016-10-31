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

        add_action('add_meta_boxes', [$this, 'itinerary']);
        add_action('save_post', [$this, 'save']);
    }

    public function add_menu_items(){
        add_menu_page(
            'Slider, Introduction And Cover',
            'Slider, Introduction And Cover',
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


    public function itinerary()
    {
        add_meta_box('home_slider_ct', 'Setting', [$this, 'show'], 'page', 'normal', 'high');
    }


    public function show()
    {
        global $post;

        if ($post->post_name == PAGE_HOME_SLIDER_SLUG) { ?>
            <style>
                #postdivrich,#pageparentdiv,#postimagediv,#misc-publishing-actions,#revisionsdiv,#minor-publishing,#delete-action,#edit-slug-box,#wpseo_meta,h1,#titlediv,#screen-meta-links, #submitdiv button.button-link, #submitdiv h2{
                    display: none !important;
                }
                #submitdiv{
                    margin-top: 20px;
                }
                ul#adminmenu a.wp-has-current-submenu:after, ul#adminmenu>li.current>a.current:after{
                    display: none;
                }
                #adminmenu .current div.wp-menu-image:before, #adminmenu .wp-has-current-submenu div.wp-menu-image:before, #adminmenu a.current:hover div.wp-menu-image:before, #adminmenu a.wp-has-current-submenu:hover div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before{
                    color: #9a9a9a;
                }
                /*#poststuff #post-body.columns-2{
                    margin-right: 0;
                }*/
                #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu{
                    background: none;
                    color: rgb(238, 238, 238);
                }
                #adminmenu .wp-has-current-submenu .wp-submenu{
                    display: none;
                }
                #adminmenu #toplevel_page_home_slider div.wp-menu-image:before{
                    color: white !important;
                }
                .toplevel_page_home_slider{
                    background : #0073aa;
                }
            </style>

        <?php }
        ?>
        <style>
            #home_slider_ct{
                display: none;
            }
        </style>
    <?php
    }


    public function save()
    {

    }
}



