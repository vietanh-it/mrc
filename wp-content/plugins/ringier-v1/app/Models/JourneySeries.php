<?php
namespace RVN\Models;

use RVN\Library\Images;

class JourneySeries
{
    private static $instance;

    private $_wpdb;
    private $_prefix;
    private $_tbl_journey_series_info;
    private $_tbl_journey_detail;
    private $_tbl_journey_type_info;

    function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_prefix = $wpdb->prefix;

        $this->_tbl_journey_series_info = $this->_prefix . 'journey_series_info';
        $this->_tbl_journey_type_info = $this->_prefix . 'journey_type_info';
        $this->_tbl_journey_detail = $this->_prefix . 'journey_info';
    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new JourneySeries();
        }

        return self::$instance;
    }


    public function getListJourneySeries()
    {
        $select = "SELECT jsi.* FROM " . $this->_tbl_journey_series_info . " as jsi INNER JOIN " . $this->_wpdb->posts . " as p ON p.ID = jsi.object_id WHERE p.post_status = 'publish'";

        return $this->_wpdb->get_results($select);
    }


    public function getList($args = [])
    {
        $query = "SELECT * FROM {$this->_wpdb->posts} p INNER JOIN {$this->_tbl_journey_series_info} jsi ON p.ID = jsi.object_id WHERE 1 = 1";

        if (!empty($args['post_status'])) {
            $query .= ' AND p.post_status = ' . $args['post_status'];
        } else {
            $query .= ' AND p.post_status = "publish"';
        }

        if (!empty($args['object_id'])) {
            $query .= ' AND jsi.object_id = ' . $args['object_id'];
        }

        if (!empty($args['journey_type_id'])) {
            $query .= ' AND jsi.journey_type_id = ' . $args['journey_type_id'];
        }

        $result = $this->_wpdb->get_results($query);
        return $result;
    }


    public function getJourneySeriesInfo($object_id)
    {
        $select = "SELECT * FROM " . $this->_tbl_journey_series_info . ' WHERE object_id = ' . $object_id;

        return $this->_wpdb->get_row($select);
    }


    public function saveJourneySeriesInfo($data)
    {
        $result = false;
        if ($data['object_id']) {
            $object_id = $data['object_id'];
            $journey_series_info = $this->getJourneySeriesInfo($object_id);
            if (($journey_series_info)) {
                unset($data['object_id']);
                $result = $this->_wpdb->update($this->_tbl_journey_series_info, $data, array('object_id' => $object_id));
            } else {
                $result = $this->_wpdb->insert($this->_tbl_journey_series_info, $data);
            }
        }

        return $result;
    }

}