<?php
namespace RVN\Controllers;

use RVN\Models\Journey;
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
        $args['is_paging'] = 1;
        $args['page'] = $page ? $page : 1;
        $journey = JourneyType::init();
        $list_journey_type = $journey->getJourneyTypeList($args);

        view('journey-type/list', compact('list_journey_type'));
    }


    public function journeyTypeDetail($journey_id)
    {
        $journeyType = JourneyType::init();
        $m_journey = Journey::init();

        $journey_type_info = $journeyType->getInfo($journey_id);

        $min_price = $journeyType->getJourneyTypeMinPrice($journey_type_info->ID);

        $objTourAddon = Addon::init();
        $list_add_on = $objTourAddon->getList(
            [
                'journey_type_id' => $journey_id,
                'limit'           => 6,
            ]);


        // List Journey
        $journey_list = $m_journey->getJourneyList(['journey_type_id' => $journey_id]);


        return view('journey-type/detail',
            compact('journey_type_info', 'journey_list', 'list_add_on', 'min_price'));
    }


    public function getJourneyMinPrice($journey_id, $type = '')
    {
        $journeyType = JourneyType::init();

        return $journeyType->getJourneyMinPrice($journey_id, $type);
    }

}
