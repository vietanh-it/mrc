<?php
namespace RVN\Hooks;

use RVN\Models\Journey;
use RVN\Models\Offer;

class CustomJourney
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomJourney();
        }

        return self::$instance;
    }


    function __construct()
    {
        add_action('add_meta_boxes', [$this, 'journey_list']);
        add_action('save_post', [$this, 'save']);
        add_action('wp_trash_post', [$this, 'trashJourneySeries']);
        add_action('untrash_post', [$this, 'untrashJourneySeries']);
        add_action('delete_post', [$this, 'deleteJourneySeries']);
    }


    public function journey_list()
    {
        add_meta_box('journey_list', 'Journey Series', [$this, 'show'], 'journey', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        ?>

        <style>

        </style>

        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {

            });
        </script>

        <?php
    }


    public function save()
    {

    }


    public function trashJourneySeries($jseries_id)
    {
        global $wpdb;

        $m_journey = Journey::init();
        $m_offer = Offer::init();
        $journey_list = $m_journey->getJourneyListBySeries($jseries_id);

        foreach ($journey_list as $k => $v) {
            // Trash journey
            wp_trash_post($v->object_id);

            // Trash offer theo journey
            $offer = $m_offer->getOfferByJourney($v->object_id);
            wp_trash_post($offer->ID);
        }
    }


    public function untrashJourneySeries($jseries_id)
    {
        global $wpdb;

        $m_journey = Journey::init();
        $m_offer = Offer::init();
        $journey_list = $m_journey->getJourneyListBySeries($jseries_id);

        foreach ($journey_list as $k => $v) {
            // Untrash journey
            wp_untrash_post($v->object_id);

            // Untrash offer theo journey
            $offer = $m_offer->getOfferByJourney($v->object_id);
            wp_untrash_post($offer->ID);
        }
    }


    public function deleteJourneySeries($jseries_id)
    {
        global $wpdb;

        $m_journey = Journey::init();
        $m_offer = Offer::init();
        $journey_list = $m_journey->getJourneyListBySeries($jseries_id);

        foreach ($journey_list as $k => $v) {
            wp_delete_post($v->object_id);

            // Delete journey info
            $wpdb->delete($wpdb->prefix . 'journey_info', ['object_id' => $v->object_id]);

            // Delete offer theo journey
            $offer = $m_offer->getOfferByJourney($v->object_id);
            wp_delete_post($offer->ID);
        }
    }

}