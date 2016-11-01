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

    $journey_id = $_GET['jid'];

    unset($_GET['step']);
    unset($_GET['jid']);

    $data = $_GET;
    $data['trans_status'] = $transaction_status;
    $result = $booking->finishBooking(get_current_user_id(), $journey_id, $data);
}
else {
    $return_url = WP_SITEURL;
    if (isset($_GET['vpc_TxnResponseCode'])) {
        $return_url .= '?resp=' . $_GET['vpc_TxnResponseCode'];
    }
    wp_redirect($return_url);
    exit; ?>

<?php }