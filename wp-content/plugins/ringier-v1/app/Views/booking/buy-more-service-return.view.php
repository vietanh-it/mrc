<?php
global $post,$wpdb;
$booking = \RVN\Models\Booking::init();

$bank = \RVN\Models\Bank::init();
$vpc_AcqResponseCode = valueOrNull($_GET["vpc_AcqResponseCode"]);

if (isset($_GET['vpc_TxnResponseCode']) && $_GET['vpc_TxnResponseCode'] == '0') {
    $isValidHash = $bank->isValidHash($_GET);
    if ($isValidHash == 1) {
        $transaction_status = 'active';
    }
    else {
        $transaction_status = 'inactive';
    }

    $journey_id = $_GET['jid'];

    unset($_GET['step']);
    unset($_GET['jid']);

    $data = $_GET;
    $data['trans_status'] = $transaction_status;

    $result = $wpdb->update($wpdb->prefix.'cart_addon',array('status' => $transaction_status),array('cart_id' => $data['cart_id']));
    //$result = $booking->finishBooking(get_current_user_id(), $journey_id, $data);
}
else {
    $return_url = WP_SITEURL;
    if (isset($_GET['vpc_TxnResponseCode'])) {
        $return_url .= '?resp=' . $_GET['vpc_TxnResponseCode'];
    }
    wp_redirect($return_url);
    exit; ?>

<?php }