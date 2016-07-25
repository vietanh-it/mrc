<?php
namespace RVN\Controllers;

use RVN\Models\JourneyType;

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

    public function journeyTypeList()
    {
        $page = get_query_var('paged');
        $args = array(
            'limit' => 6,
            'page' => $page ? $page : 1,
        );
        $journey = JourneyType::init();
        $list_journey_type = $journey->getJourneyTypeList($args);

        view('journey-type/list', compact('list_journey_type'));
    }

    public function journeyTypeDetail($journey_id)
    {
        $journeyType = JourneyType::init();
        $journey_type_info = $journeyType->getInfo($journey_id);

        return view('journey-type/detail', compact('journey_type_info'));
    }

}
