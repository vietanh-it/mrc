<?php
/**
 * Template name: Before you go
 */
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}
$ctr = \RVN\Controllers\Account\AccountController::init();
$ctr->beforeYouGo();