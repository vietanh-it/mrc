<?php
/**
 * Template name: Before you go
 */
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}
/*if(empty($_GET['id'])){
    wp_redirect(WP_SITEURL);
    exit;
}*/
$ctr = \RVN\Controllers\BookingController::init();
$ctr->beforeYouGo(!empty($_GET['id']) ? $_GET['id'] : 0);