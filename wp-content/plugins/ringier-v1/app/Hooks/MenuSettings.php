<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 23-Sep-16
 * Time: 4:31 PM
 */

namespace RVN\Hooks;

class MenuSettings
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new MenuSettings();
        }

        return self::$instance;
    }


    function __construct()
    {
        add_action('admin_menu', [$this, 'initPages']);
    }


    public function initPages()
    {
        add_menu_page('MRC Settings', 'MRC Settings', 'manage_options', 'mrc-settings', [$this, 'mrcSettings'], '', 50);
    }

    // Register Navigation Menus
    public function mrcSettings()
    { ?>

        <form action='' method='post'>

            <h2>MRC Settings</h2>

            <section class="wrap">
                <h3>Food & Beverage Gallery</h3>
                <div class="content-wrapper">

                </div>
            </section>

            <?php
            submit_button();
            ?>

        </form>

    <?php }
}