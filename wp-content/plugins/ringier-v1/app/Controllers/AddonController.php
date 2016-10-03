<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 14-Aug-16
 * Time: 11:09 AM
 */

namespace RVN\Controllers;

use RVN\Models\Addon;

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
            'data'   => $result
        ];
    }

}