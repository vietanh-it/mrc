<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 14-Aug-16
 * Time: 11:09 AM
 */

namespace RVN\Controllers;

use RVN\Models\Booking;

class AddonController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new AddonController();
        }

        return self::$instance;
    }


    public function ajaxSaveAddon($data)
    {
        $this->validate->rule('required', ['journey_id', 'room_id']);

        if ($this->validate->validate()) {
            $model = Booking::init();
            $rs = $model->saveCart($data);
            if (!empty($rs)) {
                $result = [
                    'status' => 'success',
                    'data'   => $rs
                ];
            }
        } else {
            $result = [
                'status' => 'fail',
                'data'   => $this->validate->errors()
            ];
        }

        return valueOrNull($result, []);
    }

}