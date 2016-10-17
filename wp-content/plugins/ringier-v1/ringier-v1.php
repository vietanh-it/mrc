<?php
/*
Plugin Name: Ringier Vietnam v1
Plugin URI: http://www.ringier.vn
Description: Ringier Vietnam v1
Author: RVN     <ringier-digital@ringier.com.vn>
Version: 1.0
Author URI: http://www.ringier.vn
*/

require_once __DIR__ . '/define.php';

add_action('plugins_loaded', 'mrcLoad', 500, 1);

function mrcLoad()
{
    require_once __DIR__ . '/vendor/autoload.php';

    //call hooks
    RVN\Hooks\Rewrite::init();
    RVN\Hooks\BackendUI::init();
    // RVN\Hooks\MenuSettings::init();
    RVN\Hooks\PageTATO::init();
    RVN\Hooks\Shortcode::init();
    RVN\Hooks\CustomJourney::init();
    RVN\Hooks\CustomJourneyType::init();
    RVN\Hooks\CustomOffer::init();
    RVN\Hooks\CustomShips::init();
    RVN\Hooks\CustomTATO::init();
    RVN\Hooks\Users::init();
    RVN\Hooks\MetaboxAddon::init();
    RVN\Hooks\BoxJourneySeries::init();
    RVN\Hooks\CustomQA::init();
    RVN\Hooks\FilterBooking::init();

    // call ajax
    \RVN\Controllers\ShipController::init();
    \RVN\Controllers\JourneyController::init();
    \RVN\Controllers\BookingController::init();
    \RVN\Controllers\Account\AccountController::init();
    \RVN\Controllers\JourneySeriesController::init();
    \RVN\Controllers\AddonController::init();
    \RVN\Controllers\MediaController::init();
}