<?php
// single-journey.php
// get header

$user_id = get_current_user_id();

// Step
if (empty($_GET['step'])) {
    view('booking/select_room');
} elseif ($_GET['step'] == 'services-addons') {
    view('booking/booking_addon');
} elseif ($_GET['step'] == 'booking-review') {
    view('booking/review');
} elseif ($_GET['step'] == 'process') {
    view('booking/process');
} elseif ($_GET['step'] == 'buy-more-service') {
    view('booking/buy-more-service');
} else {
    // Redirect to step select room
    $url = WP_SITEURL . strtok($_SERVER["REQUEST_URI"], '?');
    wp_redirect($url);
    exit;
}

// get footer