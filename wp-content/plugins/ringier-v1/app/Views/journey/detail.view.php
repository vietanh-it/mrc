<?php

$user_id = get_current_user_id();

get_header();

// Step
if (!empty($_GET['step']) && $_GET['step'] == 'services-addons') {
    view('booking/booking_addon');
} else {
    view('booking/select_room');
}

get_footer();
