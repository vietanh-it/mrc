<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 3/21/2016
 * Time: 4:31 PM
 */
namespace RVN\Models;

use RVN\Library\Images;

class Destinations
{
    private static $instance;

    private $_wpdb;
    private $_prefix;


    /**
     * Users constructor.
     */
    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Destinations();
        }

        return self::$instance;
    }


    public function getInfo($destination)
    {
        if (is_numeric($destination)) {
            $destination = get_post($destination);
        }

        $query = "SELECT * FROM {$this->_wpdb->posts} p INNER JOIN " . TBL_POST_INFO . " pi ON p.ID = pi.object_id WHERE p.ID = {$destination->ID}";
        $result = $this->_wpdb->get_row($query);

        if (!empty($result)) {
            $countries = unserialize($result->countries);
            $result->countries = $countries;
        }

        return $result;
    }


    public function getDestinationHaveJourney()
    {
        $m_journey_type = JourneyType::init();
        $m_journey = Journey::init();

        $jouney_type = $m_journey_type->getJourneyTypeList();
        if (!empty($jouney_type['data'])) {

            foreach ($jouney_type['data'] as $k => $v) {

            }

        }
    }
}
