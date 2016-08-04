<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 20-Jul-16
 * Time: 9:59 PM
 */


if (!is_user_logged_in()) {
    wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
    exit;
}

if (have_posts()) : the_post();
    global $post;
    get_header();

    $journey_ctrl = \RVN\Controllers\JourneyController::init();

    $journey_ctrl->journeyDetail($post->ID);

    get_footer();
endif;