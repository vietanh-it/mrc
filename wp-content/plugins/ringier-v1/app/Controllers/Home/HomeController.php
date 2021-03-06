<?php
namespace RVN\Controllers\Home;

use RVN\Controllers\_BaseController;
use RVN\Controllers\DestinationController;
use RVN\Controllers\JourneyController;
use RVN\Controllers\PortController;
use RVN\Controllers\ShipController;
use RVN\Models\Destinations;
use RVN\Models\JourneyType;
use RVN\Models\Offer;
use RVN\Models\Ports;
use RVN\Models\Posts;
use RVN\Models\Ships;

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
        $objShip = ShipController::init();
        $objDestination = DestinationController::init();
        $objPort = PortController::init();
        $objJourney = JourneyController::init();
        $m_destination = Destinations::init();
        $m_port = Ports::init();
        $m_ship = Ships::init();

        $list_destination = $m_destination->getDestinationHaveJourney();
        $list_port = $m_port->getPortHaveJourney();
        $list_ship = $m_ship->getShipHaveJourney();
        $list_month = $objJourney->getMonth();

        $args = array(
            'limit' => 3,
        );
        $journey = JourneyType::init();
        $list_journey_type = $journey->getJourneyTypeList($args);

        $objOffer = Offer::init();
        $list_offer = $objOffer->getOfferList();

        $Post = Posts::init();
        $list_room_items_featured = $Post->getList(array(
            'post_type' => 'album',
            'post_status' => 'publish',
            'meta_key' => 'is_featured',
            'meta_value' => '1',
            'posts_per_page' => 1,
        ));

        $not_in = 0;
        $limit_room = 5;
        if (!empty($list_room_items_featured['data'])) {
            $limit_room = 4;
            $not_in = $list_room_items_featured['data'][0]->ID;
        }

        $list_room_items = $Post->getList(array(
            'post_type' => 'album',
            'post_status' => 'publish',
            'posts_per_page' => $limit_room,
            'post__not_in' => array($not_in),
        ));

        $objPost = Posts::init();
        $slider_page = get_page_by_path(PAGE_HOME_SLIDER_SLUG);
        $home_page_info = $objPost->getInfo($slider_page);

        $args = array(
            'posts_per_page' => 20,
            'post_type' => 'why_us',
        );
        $list_whyus = $Post->getList($args);

        return view('home/home',
            compact('list_destination', 'list_ship', 'list_port', 'list_journey_type', 'list_offer', 'list_month',
                'list_room_items', 'list_room_items_featured','list_whyus','home_page_info'));
    }
}

?>