<?php
namespace RVN\Hooks;

use RVN\Models\Booking;

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
        add_filter('posts_join', [$this, 'join']);
        add_filter('posts_where', [$this, 'where']);
        add_filter('manage_posts_columns', [$this, 'columnHead']);
        add_filter('manage_posts_custom_column', [$this, 'columnContent']);
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


    public function columnHead($columns)
    {
        global $pagenow;
        $type = 'booking';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }

        if ('booking' == $type && is_admin() && $pagenow == 'edit.php') {
            $columns['booking_status_column'] = 'Booking Status';
        }

        // Add button 'Add new TA/TO Booking' on page title
        ?>
        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                $('.wrap h1').append('<a href="<?php echo WP_SITEURL; ?>/wp-admin/post-new.php?post_type=booking&type=tato" class="page-title-action" style="margin-left: 10px;">Add New TA/TO Booking</a>');
            });
        </script>
        <?php
        return $columns;
    }


    public function columnContent($column_name)
    { ?>

        <style>
            .badge {
                padding: 4px 10px;
                border-radius: 4px;
            }

            .badge.badge-cart {
                background: #34495e;
                color: #ffffff;
            }

            .badge.badge-tato {
                background: #e67e22;
                color: #ffffff;
            }

            .badge.badge-cancel {
                background: #ecf0f1;
                color: #aaaaaa;
            }

            .badge.badge-before-you-go {
                background: #3498db;
                color: #ffffff;
            }

            .badge.badge-ready-to-onboard {
                background: #2ecc71;
                color: #ffffff;
            }

            .badge.badge-onboard {
                background: #1abc9c;
                color: #ffffff;
            }

            .badge.badge-finished {
                background: #aaaaaa;
                color: #ffffff;
            }
        </style>

        <?php if ($column_name == 'booking_status_column') {
        global $post;

        $m_booking = Booking::init();
        $booking_detail = $m_booking->getBookingDetail($post->ID);
        $status = $m_booking->getBookingStatusText($booking_detail->status);

        echo '<span class="badge badge-' . $booking_detail->status . '">' . $status . '</span>';
    }

    }

}