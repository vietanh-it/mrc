<?php
namespace RVN\Controllers;

class JourneyTypeController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new JourneyTypeController();
        }

        return self::$instance;
    }


    public function getJourneyTypeInfo($journey_id)
    {
        $model = JourneyType::init();
        $result = $model->getJourneyTypeInfo($journey_id);

        return $result;
    }
}
