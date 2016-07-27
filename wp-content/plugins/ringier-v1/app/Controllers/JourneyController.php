<?php
namespace RVN\Controllers;

use RVN\Models\Journey;

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


    public function journeyDetail($journey_id)
    {
        $journey = Journey::init();
        $journey_info = $journey->getInfo($journey_id);

        return view('journey/detail', compact('journey_info'));
    }


    public function journeyList($args = [])
    {
        $journey = Journey::init();
        $list_journey = $journey->getJourneyList($args);

        view('journey/list', compact('params', 'list_journey'));
    }
}
