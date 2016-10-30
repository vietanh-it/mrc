<?php
namespace RVN\Models;

class Location
{
    protected $_wpdb;
    protected $_table_country;

    private static $instance;

    /**
     * Location constructor.
     *
     */
    protected function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_table_country = $wpdb->prefix . "countries";
    }

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Location();
        }

        return self::$instance;
    }

    //region city
    public function getCountryList()
    {
        $cacheId = __CLASS__ . __METHOD__;
        $result = wp_cache_get($cacheId);
        if ($result === false) {
            $query = "SELECT * FROM {$this->_table_country}";
            $result = $this->_wpdb->get_results($query);
            wp_cache_set($cacheId, $result, CACHEGROUP, CACHETIME);
        }

        return $result;
    }

    public function getCountryName($code)
    {
        $query = "SELECT name FROM {$this->_table_country} WHERE alpha_2 = '{$code}'";
        return $this->_wpdb->get_var($query);
    }
}