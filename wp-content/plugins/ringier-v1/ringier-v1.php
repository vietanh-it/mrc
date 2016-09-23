<?php
/*
Plugin Name: Ringier Vietnam v1
Plugin URI: http://www.ringier.vn
Description: Ringier Vietnam v1
Author: RVN     <ringier-digital@ringier.com.vn>
Version: 1.0
Author URI: http://www.ringier.vn
*/


define("FACEBOOK_APP_ID", "913210768811539");
define("FACEBOOK_SECRET", "5f955f910212ee23d8d5fab846e6b050");

define("GOOGLE_CLIENT_ID", "1016359682208-k6m91deb6th0vgk1khl4op3ojuotj52b.apps.googleusercontent.com");
define("GOOGLE_CLIENT_SECRET", "RDJcby4lI0vodEt7mmTM-fG5");

define("PATH_VIEW", __DIR__ . '/app/Views/');
define("VIEW_URL", WP_SITEURL . '/wp-content/plugins/ringier-v1/app/Views/_assets');

if (!defined('CACHEGROUP')) {
    define('CACHEGROUP', 'default');
}
if (!defined('CACHETIME')) {
    define('CACHETIME', '3600');
}

//if ( !defined('SENDY_LIST') ) define("SENDY_LIST", 'OSoGqkr5RWlOmKS892JEXDYQ');

if (!defined('AVATAR_DEFAULT')) {
    define('AVATAR_DEFAULT', get_template_directory_uri() . "/images/default_avatar.jpg");
}
if (!defined('PASSWORD_DEFAULT')) {
    define('PASSWORD_DEFAULT', 'mrc1234');
}

if (!defined('SECURE_SECRET_ATM')) {
    define('SECURE_SECRET_ATM', 'A3EFDFABA8653DF2342E8DAC29B51AF0');
}

if (!defined('SECURE_SECRET_CC')) {
    define('SECURE_SECRET_CC', '6D0870CDE5F24F34F3915FB0045120DB');
}


// Current secure secret - accept one payment method (credit card)
if (!defined('SECURE_SECRET')) {
    define('SECURE_SECRET', SECURE_SECRET_CC);
}

if (!defined('EMAIL_PATH')) {
    define('EMAIL_PATH', ABSPATH . '_email/');
}

if (!defined('SUBSCRIBE_LIST')) {
    define('SUBSCRIBE_LIST', 'RGc7bPC3b2UkfX4CsOw2HQ');
}


add_action('plugins_loaded', 'mrcLoad', 500, 1);

function mrcLoad()
{
    require_once __DIR__ . '/vendor/autoload.php';

    //call hooks
    RVN\Hooks\Rewrite::init();
    RVN\Hooks\BackendUI::init();
    RVN\Hooks\MenuSettings::init();
    RVN\Hooks\Shortcode::init();
    RVN\Hooks\CustomJourney::init();
    RVN\Hooks\CustomJourneyType::init();
    RVN\Hooks\CustomOffer::init();
    RVN\Hooks\CustomShips::init();
    RVN\Hooks\Users::init();
    RVN\Hooks\MetaboxAddon::init();
    RVN\Hooks\BoxJourneySeries::init();
    RVN\Hooks\CustomQA::init();

    // call ajax
    \RVN\Controllers\ShipController::init();
    \RVN\Controllers\JourneyController::init();
    \RVN\Controllers\BookingController::init();
    \RVN\Controllers\Account\AccountController::init();
    \RVN\Controllers\JourneySeriesController::init();
}