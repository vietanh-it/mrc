<?php
namespace RVN\Controllers;

use RVN\Models\Posts;

class JourneyController extends _BaseController
{
    private static $instance;

    protected function __construct()
    {
        parent::__construct();
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new JourneyController();
        }

        return self::$instance;
    }

    public function getJourneyDetail($journey_id)
    {

        return view('journey/detail');
    }

    public function getJourneyList()
    {
        $journey = Posts::init();

        $params = $_GET;

        if($params){
            $destination = $params['_destination'] ? $params['_destination'] : '';
            $month = $params['_month'] ? $params['_month'] : '';
            $port = $params['_port'] ? $params['_port'] : '';
            $ship = $params['_ship'] ? $params['_ship'] : '';
        }

        $list_journey = array();
        
        return view('journey/list',compact('params','list_journey'));
    }
}
