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

        return view('journey/list');
    }
}
