<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 15-Sep-16
 * Time: 9:41 AM
 */

if (!empty($_GET['p'])) {
    global $wpdb;
    $param = $_GET['p'];
    $param = mrcDecrypt($param);
    $user_id = username_exists($param);

    if (!empty($user_id)) {
        $wpdb->update(
            $wpdb->prefix . "user_info",
            ['is_subscribe' => 0],
            ['user_id' => $user_id]
        );
    }
    else {
        $wpdb->delete(
            $wpdb->prefix . "newsletter",
            ["email" => $param]
        );
    }

    unsubscribeSendy(['user_email' => $param]);
}
wp_redirect(WP_SITEURL);
exit;