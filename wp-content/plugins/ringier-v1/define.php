<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 9/25/2016
 * Time: 5:51 PM
 */


define("FACEBOOK_APP_ID", "913210768811539");
define("FACEBOOK_SECRET", "5f955f910212ee23d8d5fab846e6b050");

define("GOOGLE_CLIENT_ID", "1016359682208-k6m91deb6th0vgk1khl4op3ojuotj52b.apps.googleusercontent.com");
define("GOOGLE_CLIENT_SECRET", "RDJcby4lI0vodEt7mmTM-fG5");

define("PATH_VIEW", __DIR__ . '/app/Views/');
define("VIEW_URL", WP_SITEURL . '/wp-content/plugins/ringier-v1/app/Views/_assets');

define('PAGE_HOME_SLIDER_SLUG', 'home-slider');

define('CURRENCY_RATE', 22300);

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
    define('SECURE_SECRET_ATM', 'B476481454B1E893B1F078FD6363F122');
}

if (!defined('SECURE_SECRET_CC')) {
    define('SECURE_SECRET_CC', '15D01C23137AC8A7B4FF07C88DC4A8F6');
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


// TABLE NAME
if (!defined('DB_PREFIX')) {
    global $wpdb;
    define('DB_PREFIX', $wpdb->prefix);
}

define('TBL_ADDON_OPTIONS', DB_PREFIX . 'addon_options');
define('TBL_CART', DB_PREFIX . 'cart');
define('TBL_CART_ADDON', DB_PREFIX . 'cart_addon');
define('TBL_CART_DETAIL', DB_PREFIX . 'cart_detail');
define('TBL_OFFER_INFO', DB_PREFIX . 'offer_info');
define('TBL_JOURNEY_INFO', DB_PREFIX . 'journey_info');
define('TBL_JOURNEY_TYPE_INFO', DB_PREFIX . 'journey_type_info');
define('TBL_POST_INFO', DB_PREFIX . 'post_info');
define('TBL_JOURNEY_TYPE_PORT', DB_PREFIX . 'journey_type_port');
define('TBL_TATO', DB_PREFIX . 'tato');
define('TBL_ROOMS', DB_PREFIX . 'rooms');
define('TBL_ROOM_TYPES', DB_PREFIX . 'room_types');