<?php
namespace RVN\Controllers;

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
}
