<?php
namespace RVN\Controllers;

use RVN\Models\JourneyType;
use RVN\Models\Posts;
use RVN\Models\Addon;

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

    public function journeyTypeList($args)
    {
        $page = get_query_var('paged');
        $args['limit'] = 6;
        $args['page'] = $page ? $page : 1;
        $journey = JourneyType::init();
        $list_journey_type = $journey->getJourneyTypeList($args);

        view('journey-type/list', compact('list_journey_type'));
    }

    public function journeyTypeDetail($journey_id)
    {
        $journeyType = JourneyType::init();
        $journey_type_info = $journeyType->getInfo($journey_id);

        $objTourAddon = Addon::init();
        $list_add_on = $objTourAddon->getList(
            [
                'journey_type_id' => $journey_id,
                'limit'           => 6,
            ]);

        return view('journey-type/detail', compact('journey_type_info', 'list_add_on'));
    }

}
