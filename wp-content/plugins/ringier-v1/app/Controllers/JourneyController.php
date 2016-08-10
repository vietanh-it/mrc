<?php
namespace RVN\Controllers;

use RVN\Models\Journey;

class JourneyController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();


        add_action("wp_ajax_ajax_handler_journey", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_journey", [$this, "ajaxHandler"]);
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
        $journey = Journey::init();
        $journey_info = $journey->getInfo($journey_id);

        return $journey_info;
    }


    public function journeyDetail($journey_id)
    {
        return view('journey/detail');
    }


    public function journeyList($args = [])
    {
        $journey = Journey::init();
        $list_journey = $journey->getJourneyList($args);

        view('journey/list', compact('params', 'list_journey'));
    }

    public function ajaxGetJourneyByJourneyType($args){
        $journey = Journey::init();
        $list_journey = $journey->getJourneyList($args);

        return $list_journey;
    }

    public function getMonth(){
        $model = Journey::init();

        return $model->getMonthHaveJourney();
    }
}
