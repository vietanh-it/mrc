<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 09-Sep-16
 * Time: 1:14 PM
 */

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(WP_SITEURL . '/account/profile'));
    exit();
}

wp_redirect(WP_SITEURL . '/account/profile');
exit();