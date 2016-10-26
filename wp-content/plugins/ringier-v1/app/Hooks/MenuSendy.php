<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 23-Sep-16
 * Time: 4:31 PM
 */

namespace RVN\Hooks;

class MenuSendy
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new MenuSendy();
        }

        return self::$instance;
    }


    function __construct()
    {
        add_action('admin_menu', [$this, 'initPages']);
    }


    public function initPages()
    {
        // add_menu_page('Sendy', 'Sendy', 'create_users', 'sendy-iframe', [$this, 'sendyIframe'], 'dashicons-email-alt', 50);
    }

    // Register Navigation Menus
    public function sendyIframe()
    { ?>

        <style>
            #wpcontent {
                padding-left: 0;
            }

            iframe {
                width: 100%;
                height: 600px;
            }
        </style>

        <iframe src="https://sendy.carodigital.studio/"></iframe>

    <?php }
}