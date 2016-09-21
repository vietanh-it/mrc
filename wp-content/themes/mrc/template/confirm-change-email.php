<?php
/**
 * Template name: Confirm change email
 */

/*if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}*/
$ctr = \RVN\Controllers\Account\AccountController::init();
$ctr->confirm_change_email();