<?php
namespace RVN\Controllers;

use RVN\Models\Journey;
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

        $journey  = Journey::init();
        $journey_info = $journey->getInfo($journey_id);

        return view('journey/detail',compact('journey_info'));
    }

    public function getJourneyList()
    {
        $params = $_GET;

        if($params){
            $destination = $params['_destination'] ? $params['_destination'] : '';
            $month = $params['_month'] ? $params['_month'] : '';
            $port = $params['_port'] ? $params['_port'] : '';
            $ship = $params['_ship'] ? $params['_ship'] : '';
        }

        $journey  = Journey::init();
        $list_journey = $journey->getJourneyList($params);

        return view('journey/list',compact('params','list_journey'));
    }
}
