<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 20-Jul-16
 * Time: 9:59 PM
 */

if (have_posts()) : the_post();
    global $post;
    get_header();

    $journey_ctrl = \RVN\Controllers\JourneyController::init();

    $journey_ctrl->journeyDetail($post->ID);

    get_footer();
endif;