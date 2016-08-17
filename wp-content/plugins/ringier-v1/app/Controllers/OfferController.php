<?php
namespace RVN\Controllers;

use RVN\Models\JourneyType;
use RVN\Models\Offer;
use RVN\Models\Posts;
use RVN\Models\Ships;

class OfferController extends _BaseController
{
    private static $instance;


    protected function __construct()
    {
        parent::__construct();

    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new OfferController();
        }

        return self::$instance;
    }

    public function offerList(){

        $objOffer = Offer::init();
        $argc = array('limit' => 6);
        $list_offer = $objOffer->getListOffer($argc);

        view('offer/list', compact('params', 'list_offer'));
    }

    public function offerDetail($id){
        $objOffer = Offer::init();
        $offer_info = $objOffer->getOfferInfo($id);

        $objJourneyType = JourneyType::init();
        $list_journey_type_related = $objJourneyType->getJourneyTypeList(
            array('offer_id' => $id)
        );

       // var_dump($list_journey_type_related);

        return view('offer/detail', compact('offer_info','list_journey_type_related'));
    }
}
