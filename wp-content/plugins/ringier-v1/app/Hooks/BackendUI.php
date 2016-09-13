<?php
namespace RVN\Hooks;

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
        add_action('admin_head', [$this, 'adminHead']);
        add_action('admin_footer', [$this, 'adminFooter']);
        add_action('admin_menu', [$this, 'adminMenu'], 9999);
        add_filter('acf/load_field/name=countries', [$this, 'loadFieldDestinationCountries']);
        add_filter('wp_mail', [$this, 'wpMail']);
    }


    public function wpMail($args)
    {
        if (empty($args['headers'])) {
            $header_html = file_get_contents(EMAIL_PATH . 'header.html');
            $footer_html = file_get_contents(EMAIL_PATH . 'footer.html');

            $content = $header_html . $args['message'] . $footer_html;
            $args['message'] = $content;
            $args['headers'] = 'Content-type: text/html';

            return $args;
        }
    }


    public function adminHead()
    {
        echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css"  rel="stylesheet">';
        echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css"  rel="stylesheet">';
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">';
    }


    public function adminFooter()
    {
        echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>';
        echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>';
        ?>

        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                $('.select2').select2();
            });
        </script>

        <?php
    }


    public function adminMenu()
    {
        remove_menu_page('link-manager.php');
        if (!current_user_can('administrator')) {
            remove_menu_page('tools.php');
            remove_menu_page('themes.php');
            remove_menu_page('plugins.php');
            // remove_menu_page('edit.php?post_type=page');
            remove_menu_page('edit-comments.php');
            remove_menu_page('edit.php');
            remove_action('admin_notices', 'update_nag', 3);
        }

        // remove_menu_page('upload.php');
        // remove_menu_page('edit.php?post_type=acf');
    }


    public function loadFieldDestinationCountries($field)
    {
        // reset array
        $field['choices'] = [];

        // get countries
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}countries";
        $result = $wpdb->get_results($query);

        foreach ($result as $key => $item) {
            $field['choices'][$item->alpha_2] = $item->name;
        }

        return $field;
    }
}