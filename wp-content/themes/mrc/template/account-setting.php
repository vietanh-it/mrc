<?php
/**
 * Template name: Account setting
 */

if(!is_user_logged_in()) header('#');
$ctr = \RVN\Controllers\Account\AccountController::init();
$ctr->userInfo(get_current_user_id());