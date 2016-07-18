<?php
namespace RVN\Controllers;

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

    }

    public function getJourneyList()
    {


        return view('journey/list');
    }
}
