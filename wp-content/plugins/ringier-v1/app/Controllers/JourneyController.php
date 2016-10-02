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
        $page = get_query_var("paged");
        if (empty($page)) {
            $page = 1;
        }

        $args['page'] = $page;
        $args['limit'] = 6;
        $args['is_paging'] = 1;

        $journey = Journey::init();
        $list_journey = $journey->getJourneyList($args);

        view('journey/list', compact('params', 'list_journey'));
    }


    public function ajaxGetJourneys($args)
    {
        $journey = Journey::init();
        $list_journey = $journey->getJourneyList($args);

        if (empty($list_journey['status'])) {
            $list_journey['status'] = 'success';
        }

        return $list_journey;
    }


    public function ajaxGetRoomTypes($args)
    {
        $journey = Journey::init();
        $list_room_type = $journey->getRoomTypes($args['journey_id']);

        $result = [
            'status' => 'success',
            'data'   => $list_room_type
        ];

        return $result;
    }


    public function ajaxGetAvailableRooms($args)
    {
        $journey = Journey::init();
        $list_room = $journey->getAvailableRooms($args);

        $result = [
            'status' => 'success',
            'data'   => $list_room
        ];

        return $result;
    }


    public function ajaxGetJourneyInfo($args)
    {
        $journey = Journey::init();
        $info = $journey->getInfo($args['object_id']);

        $rs = [
            'status' => 'success',
            'data'   => $info
        ];

        return $rs;
    }


    public function ajaxGetJourneyByJourneySeries($args)
    {
        $journey = Journey::init();
        $list_journey = $journey->getJourneyList($args);

        return $list_journey;
    }


    public function getMonth()
    {
        $model = Journey::init();

        return $model->getMonthHaveJourney();
    }
}
