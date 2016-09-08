<?php
// *********************
// START OF MAIN PROGRAM
// *********************

global $post;
$booking = \RVN\Models\Booking::init();

$bank = \RVN\Models\Bank::init();
$vpc_AcqResponseCode = valueOrNull($_GET["vpc_AcqResponseCode"]);

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
$booking->finishBooking(get_current_user_id(), $post->ID, $data);

// if (!empty($data)) {
// echo $bank->getResponseDescription($data['vpc_TxnResponseCode']);
// }