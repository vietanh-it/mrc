<?php
namespace RVN\Hooks;

class BackendUI
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new BackendUI();
        }

        return self::$instance;
    }

    function __construct()
    {
        add_action('admin_head', [$this, 'adminHead']);
        add_action('admin_menu', [$this, 'adminMenu'], 9999);
    }

    public function adminHead()
    {
        echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css"  rel="stylesheet">';
    }

    public function adminMenu()
    {
        remove_menu_page('link-manager.php');
        remove_menu_page('tools.php');
        // remove_menu_page('themes.php');
        // remove_menu_page('plugins.php');
        // remove_menu_page('upload.php');
        // remove_menu_page('edit.php?post_type=acf');
    }
}