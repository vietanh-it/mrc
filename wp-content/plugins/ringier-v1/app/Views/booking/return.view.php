<?php
// *********************
// START OF MAIN PROGRAM
// *********************

global $post;
$booking = \RVN\Models\Booking::init();

$bank = \RVN\Models\Bank::init();
$vpc_AcqResponseCode = valueOrNull($_GET["vpc_AcqResponseCode"]);

if (isset($_GET['vpc_TxnResponseCode']) && $_GET['vpc_TxnResponseCode'] == '0') {
    $isValidHash = $bank->isValidHash($_GET);
    if ($isValidHash == 1) {
        $transaction_status = 'success';
    }
    elseif ($isValidHash == 2) {
        $transaction_status = 'pending';
    }
    else {
        $transaction_status = 'failed';
    }

    unset($_GET['step']);
    $data = $_GET;
    $data['trans_status'] = $transaction_status;
    $result = $booking->finishBooking(get_current_user_id(), $post->ID, $data);
}
else {
    wp_redirect(WP_SITEURL);
    exit; ?>

<?php }