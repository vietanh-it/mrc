<?php
namespace RVN\Controllers;

use RVN\Models\Booking;
use RVN\Models\Journey;
use RVN\Models\JourneySeries;

class JourneySeriesController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();


        add_action("wp_ajax_ajax_handler_journey_series", [$this, "ajaxHandler"]);
        add_action("wp_ajax_nopriv_ajax_handler_journey_series", [$this, "ajaxHandler"]);
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new JourneySeriesController();
        }

        return self::$instance;
    }

    public function ajaxCountNewDeparture($data)
    {
        $result = '';
        if (!empty($data['departure']) && !empty($data['duration'])) {
            $result['date'] = date('Y-m-d', strtotime($data['departure']) + intval($data['duration']) * 24 * 60 * 60);
            $result['raw_date'] = date('ymd', strtotime($data['departure']) + intval($data['duration']) * 24 * 60 * 60);
        }

        return $result;
    }


    public function ajaxGetJourneySeries($args)
    {
        $journey_series = JourneySeries::init();
        $list_series = $journey_series->getList($args);

        return [
            'status' => 'success',
            'data' => $list_series
        ];
    }

    public function ajaxCheckJourneyHasBooking($data){
        $result = array(
            'status' =>'error',
            'message' => array('An error, please try again.'),
        );
        if(!empty($data['journey_id'])){
            $objBooking = Booking::init();
            $check_journey_have_booking = $objBooking->checkJourneyHaveBooking($data['journey_id']);
            if($check_journey_have_booking){
                $result = array(
                    'status' =>'error',
                    'message' => array('This journey has been set before, you can not delete it !!!'),
                );
            }else{
                $result = array(
                    'status' => 'success',
                    'message' => 'success'
                );
            }
        }

        return $result;
    }
}
