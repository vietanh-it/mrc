<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 1-Oct-16
 * Time: 12:27 AM
 */

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
    exit;
}