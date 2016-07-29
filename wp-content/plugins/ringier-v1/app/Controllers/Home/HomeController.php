<?php
namespace RVN\Controllers\Home;

use RVN\Controllers\_BaseController;
use RVN\Controllers\DestinationController;
use RVN\Controllers\PortController;
use RVN\Controllers\ShipController;
use RVN\Models\JourneyType;
use RVN\Models\Offer;

class HomeController extends _BaseController
{
    private static $instance;

    protected function __construct()
    {
        parent::__construct();
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new HomeController();
        }
        return self::$instance;
    }

    public function index()
    {
        if (!is_user_logged_in()) {
            wp_redirect(wp_login_url());
        }

        if (!user_can(wp_get_current_user(), 'administrator')) {
            die('Website is under construction.');
        }
        $objShip = ShipController::init();
        $objDestination = DestinationController::init();
        $objPort = PortController::init();

        $list_destination = $objDestination->getDestinationList();
        $list_port = $objPort->getPortList();
        $list_ship = $objShip->getSipList();

        $args = array(
            'limit' => 3,
        );
        $journey = JourneyType::init();
        $list_journey_type = $journey->getJourneyTypeList($args);

        $objOffer = Offer::init();
        $list_offer = $objOffer->getListOffer(array());

        return view('home/home',compact('list_destination','list_ship','list_port','list_journey_type','list_offer'));
    }
}

?>