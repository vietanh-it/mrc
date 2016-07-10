<?php
namespace RVN\Hooks;

use RVN\Library\CPTColumns;

class BackendUI
{
    private static $instance;

    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new BackendUI();
        }

        return self::$instance;
    }

    function __construct()
    {
        $this->remove_action();
        add_filter('wp_headers', array($this, 'wp_headers'));
        add_action('admin_head', array($this, 'admin_head'));
        add_action('admin_footer', array($this, 'admin_footer'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_filter('show_admin_bar', array($this, 'show_admin_bar'));
        add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));

        add_filter('image_resize_dimensions', array($this, 'image_resize_dimensions'), 10, 6);

        // editor
        //add_filter('mce_buttons', array($this, 'mce_buttons') );


        // UI
        $this->add_columns_featured_image();
        $this->add_columns_extra();

        add_filter('manage_edit-comments_columns', array($this, 'manage_edit_comments_columns'));
        add_action('manage_comments_custom_column', array($this, 'manage_comments_custom_column'), 10, 2);

        // comments
        add_action('restrict_manage_comments', array($this, 'restrict_manage_comments'));
        add_filter('comments_clauses', array('comments_clauses'));
    }

    function restrict_manage_comments()
    {
        //$screen = get_current_screen();
        //var_dump($screen);
//    global $wp_query;
        switch ($_GET['comment_kind']) {
            case 'comment':
                $selectedComments = "selected";
                $selectedReviews = "";
                break;
            case 'review':
                $selectedComments = "";
                $selectedReviews = "selected";
                break;
            default:
                $selectedComments = "";
                $selectedReviews = "";
        }

        $html = '<select name="comment_kind">
                        <option value="all">Show all comment types</option>
                        <option value="comment" ' . $selectedComments . '>Comments</option>
                        <option value="review" ' . $selectedReviews . '>Reviews</option>
                    </select>';
        echo $html;

        switch ($_GET['comment_reported']) {
            case 'reported':
                $selectedComments = "selected";
                break;
            default:
                $selectedComments = "";
        }
        $html = '<select name="comment_reported">
                        <option value="all">No filter reported</option>
                        <option value="reported" ' . $selectedComments . '>Reported comments</option>
                    </select>';
        echo $html;
    }

    function comments_clauses($clauses)
    {
        global $pagenow;
        if (is_admin() && $pagenow == 'edit-comments.php') {

            if (!empty($_GET['comment_kind']) && $_GET['comment_kind'] != 'all') {
                $kind = ($_GET['comment_kind']);
                if ($kind == 'comment') {
                    $kind = '';
                }
                $clauses['where'] .= " AND `comment_type` = '$kind' ";
            }

            if (!empty($_GET['comment_reported']) && $_GET['comment_reported'] != 'all') {
                $clauses['join'] .= " LEFT JOIN `m_commentmeta` cm ON (cm.`comment_id` = m_comments.`comment_ID`) ";
                $clauses['where'] .= " AND `meta_key` = 'report' AND `meta_value` > 0 ";
            }


        }
        return $clauses;
    }

    public function remove_action()
    {
        remove_action('wp_head',
            'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }

    /**
     * @param $headers
     * @return mixed
     */
    public function wp_headers($headers)
    {
        unset($headers['X-Pingback']);
        return $headers;
    }

    /**
     * @param $content
     * @return bool
     */
    public function show_admin_bar($content)
    {
        $current_user = wp_get_current_user();

        if (empty($current_user)) {
            return false;
        }

        if (in_array(@$current_user->roles[0], array('subscriber'))) {
            return false;
        } else {
            return $content;
        }
    }

    function admin_head()
    {
        echo "<style type='text/css'>
                #timelinediv  div.tabs-panel{ max-height: 500px}
                .tablenav.top .actions select[name='comment_type'] {display: none !important;}
                </style>";
    }

    function admin_footer()
    {
        global $pagenow;
        $array_page = array('users.php', 'edit-comments.php', 'edit.php');

        if (is_admin() && in_array($pagenow, $array_page)) {
            ?>
            <link rel="stylesheet" href="<?php echo THEME_URL ?>/css/blitzer/jquery-ui-1.10.4.custom.min.css">
            <script src="<?php echo THEME_URL ?>/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script type="text/javascript">
                jQuery(document).ready(function () {

                    // Filter Date
                    jQuery("#from_date").datepicker({
                        dateFormat: 'dd/mm/yy',
                        changeMonth: true,
                        changeYear: true,
                        onClose: function (selectedDate) {
                            jQuery("#to_date").datepicker("option", "minDate", selectedDate);
                        }
                    });
                    jQuery("#to_date").datepicker({
                        dateFormat: 'dd/mm/yy',
                        changeMonth: true,
                        changeYear: true,
                        onClose: function (selectedDate) {
                            jQuery("#from_date").datepicker("option", "maxDate", selectedDate);
                        }
                    });

                });
            </script>
            <?php
        }
    }

    /**
     * @param $buttons
     * @return mixed
     */
    function mce_buttons($buttons)
    {
        //array_splice so we can insert the new item without overwriting an existing button
        array_splice($buttons, 15, 0, 'wp_page');
        return $buttons;
    }

    /**
     *
     */
    function wp_dashboard_setup()
    {
        global $wp_meta_boxes;

        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
        //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
        //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
        //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    }

    /**
     *
     */
    function admin_menu()
    {
        remove_menu_page('link-manager.php');
        remove_menu_page('tools.php');
        //remove_menu_page('edit-comments.php');
    }

    /**
     *
     * crop image top-center
     *
     * @param $payload
     * @param $orig_w
     * @param $orig_h
     * @param $dest_w
     * @param $dest_h
     * @param $crop
     * @return array|bool
     */
    function image_resize_dimensions($payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop)
    {

        // Change this to a conditional that decides whether you
        // want to override the defaults for this image or not.
        if (false) {
            return $payload;
        }

        if ($crop) {
            // crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
            $aspect_ratio = $orig_w / $orig_h;
            $new_w = min($dest_w, $orig_w);
            $new_h = min($dest_h, $orig_h);

            if (!$new_w) {
                $new_w = intval($new_h * $aspect_ratio);
            }

            if (!$new_h) {
                $new_h = intval($new_w / $aspect_ratio);
            }

            $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

            $crop_w = round($new_w / $size_ratio);
            $crop_h = round($new_h / $size_ratio);

            //$s_x = 0; // [[ formerly ]] ==> floor( ($orig_w - $crop_w) / 2 );
            $s_x = floor(($orig_w - $crop_w) / 2);
            $s_y = 0; // [[ formerly ]] ==> floor( ($orig_h - $crop_h) / 2 );
        } else {
            // don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
            $crop_w = $orig_w;
            $crop_h = $orig_h;

            $s_x = 0;
            $s_y = 0;

            list($new_w, $new_h) = wp_constrain_dimensions($orig_w, $orig_h, $dest_w, $dest_h);
        }

        // if the resulting image would be the same size or larger we don't want to resize it
        if ($new_w >= $orig_w && $new_h >= $orig_h) {
            return false;
        }

        // the return array matches the parameters to imagecopyresampled()
        // int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
        return array(0, 0, (int)$s_x, (int)$s_y, (int)$new_w, (int)$new_h, (int)$crop_w, (int)$crop_h);

    }

    function add_columns_featured_image()
    {
        //$objImages = Images::init();
        //var_dump($objImages);
        $post_columns = new CPTColumns('post');
        $post_columns->add_column('post_thumb',
            array(
                'label' => 'Featured Image',
                'type'  => 'thumb'
            )
        );

        $post_columns = new CPTColumns('vendor');
        $post_columns->add_column('post_thumb',
            array(
                'label' => 'Featured Image',
                'type'  => 'thumb'
            )
        );
        $post_columns = new CPTColumns('vendor_promotion');
        $post_columns->add_column('post_thumb',
            array(
                'label' => 'Featured Image',
                'type'  => 'thumb'
            )
        );

        $post_columns = new CPTColumns('blog');
        $post_columns->add_column('post_thumb',
            array(
                'label' => 'Featured Image',
                'type'  => 'thumb'
            )
        );
    }

    function add_columns_extra()
    {
        $post_columns = new CPTColumns('post');
        $post_columns->add_column('id',
            array(
                'label'    => 'ID',
                'type'     => 'custom_value',
                'callback' => function ($post_id) {
                    echo $post_id;
                }
            )
        );
        /*$post_columns->add_column('views',
            array(
                'label'    => 'Views',
                'type'     => 'custom_value',
                'callback' => function($post_id){
                    $objPost = new articles();
                    $info = $objPost->get_article_info($post_id);
                    echo $info->views;
                }
            )
        );*/

    }

    // ADD REPORT COLUMN TO COMMENTS
    function manage_edit_comments_columns($columns)
    {
        $columns['report_comment'] = 'Reported';
        return $columns;
    }

    function manage_comments_custom_column($column_name, $id)
    {
        if ($column_name == 'report_comment') {
            echo get_comment_meta($id, 'report', true);
        }
    }

}