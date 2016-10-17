<?php
namespace RVN\Hooks;

use RVN\Controllers\ShipController;
use RVN\Library\CPTColumns;
use RVN\Models\JourneyType;
use RVN\Models\Posts;
use RVN\Models\Ships;

class FilterBooking
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new FilterBooking();
        }

        return self::$instance;
    }


    function __construct()
    {
        add_action('restrict_manage_posts', [$this, 'showFilter']);
        add_filter('pre_get_posts', [$this, 'filter']);
        add_filter('posts_join', [$this, 'join']);
        add_filter('posts_where', [$this, 'where']);
    }


    public function showFilter()
    {
        $type = 'booking';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }

        //only add filter to post type you want
        if ('booking' == $type) {
            //change this to the list of values you want to show
            //in 'label' => 'value' format
            $values = [
                'cart'             => 'In Cart',
                'tato'             => 'TaTo Booking',
                'before-you-go'    => 'Before You Go',
                'ready-to-onboard' => 'Ready To On-board',
                'on-board'         => 'On-board',
                'finished'         => 'Finished'
            ];
            ?>

            <select name="booking_status">
                <option value="">All booking status</option>

                <?php
                $current_v = isset($_GET['booking_status']) ? $_GET['booking_status'] : '';
                foreach ($values as $value => $label) {
                    printf
                    (
                        '<option value="%s" %s>%s</option>',
                        $value,
                        $value == $current_v ? ' selected="selected"' : '',
                        $label
                    );
                }
                ?>
            </select>
            <?php
        }
    }


    public function filter()
    {
        global $pagenow, $wp_query;
        $type = 'booking';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }

        if ('booking' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['booking_status']) && $_GET['booking_status'] != '') {

        }
    }


    public function join($join)
    {
        global $pagenow, $wpdb;
        $type = 'booking';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }

        if ('booking' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['booking_status']) && $_GET['booking_status'] != '') {
            $join .= " INNER JOIN " . TBL_CART . " c ON {$wpdb->posts}.ID = c.id";
        }

        return $join;
    }


    public function where($where)
    {
        global $pagenow;
        $type = 'booking';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }

        if ('booking' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['booking_status']) && $_GET['booking_status'] != '') {
            $where .= " AND c.status = '" . $_GET['booking_status'] . "'";
        }

        return $where;
    }

}