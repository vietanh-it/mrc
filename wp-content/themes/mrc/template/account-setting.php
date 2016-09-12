<?php
/**
 * Template name: Account setting
 */

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}
$ctr = \RVN\Controllers\Account\AccountController::init();
$ctr->userInfo(get_current_user_id());