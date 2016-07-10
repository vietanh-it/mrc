<?php
namespace RVN\Hooks;

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

}