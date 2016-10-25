<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 12-Aug-16
 * Time: 2:07 PM
 */

if (!empty($_GET['payment_type']) && is_user_logged_in()) {

    global $post;
    $booking_model = \RVN\Models\Booking::init();
    $cart_info = $booking_model->getCartInfo(get_current_user_id(), $post->ID);

    $rate = 22300;
    $cart_info['total'] = $cart_info['total'] * $rate;

    $payment_type = $_GET['payment_type'];
    // $current_url = WP_SITEURL . (strtok($_SERVER["REQUEST_URI"], '?'));
    $current_url = 'https://' . $_SERVER['HTTP_HOST'] . (strtok($_SERVER["REQUEST_URI"], '?'));

    $vpcOrderInfo = $cart_info['cart_info']->booking_code . ' - ' . date('ymdHis');
    if (strlen($vpcOrderInfo) > 40) {
        $vpcOrderInfo = get_current_user_id() . ' - ' . date('Y-m-d H:i:s');
    }

    if ($payment_type == 'credit_card') {
        $vpc = [
            'vpc_Merchant'    => 'TESTONEPAY',
            'vpc_AccessCode'  => '6BEB2546',
            'vpc_Version'     => 2,
            'vpc_MerchTxnRef' => get_current_user_id() . '_' . date('YmdHis'),
            'vpc_OrderInfo'   => $vpcOrderInfo,
            'vpc_ReturnURL'   => $current_url . '?step=return',
            'vpc_Amount'      => $cart_info['total'] * 100,
            'vpc_Command'     => 'pay',
            'vpc_Locale'      => 'en',
            'vpc_TicketNo'    => $_SERVER ['REMOTE_ADDR'],
            'AgainLink'       => urlencode($current_url),
            'Title'           => 'Booking MRC Ticket'
        ];

        $vpcURL = "https://mtf.onepay.vn/vpcpay/vpcpay.op?";

        $md5HashData = "";
        ksort($vpc);
        $appendAmp = 0;

        foreach ($vpc as $key => $value) {

            // create the md5 input and URL leaving out any fields that have no value
            if (strlen($value) > 0) {

                // this ensures the first paramter of the URL is preceded by the '?' char
                if ($appendAmp == 0) {
                    $vpcURL .= urlencode($key) . '=' . urlencode($value);
                    $appendAmp = 1;
                }
                else {
                    $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
                }
                //$md5HashData .= $value; sử dụng cả tên và giá trị tham số để mã hóa
                if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                    $md5HashData .= $key . "=" . $value . "&";
                }
            }
        }
        $md5HashData = rtrim($md5HashData, "&");

        if (strlen(SECURE_SECRET_CC) > 0) {
            $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*', SECURE_SECRET_CC)));
        }

        wp_redirect($vpcURL);
        exit;
    }
    elseif ($payment_type == 'atm') {
        $vpc = [
            'vpc_Merchant'    => 'ONEPAY',
            'vpc_AccessCode'  => 'D67342C2',
            'vpc_Version'     => 2,
            'vpc_MerchTxnRef' => get_current_user_id() . '_' . date('YmdHis'),
            'vpc_OrderInfo'   => $vpcOrderInfo,
            'vpc_ReturnURL'   => $current_url . '?step=return',
            'vpc_Amount'      => $cart_info['total'] * 100,
            'vpc_Command'     => 'pay',
            'vpc_Locale'      => 'vn',
            'vpc_Currency'    => 'VND',
            'vpc_TicketNo'    => $_SERVER ['REMOTE_ADDR'],
            'AgainLink'       => urlencode($current_url),
            'Title'           => 'Booking MRC Ticket'
        ];

        $vpcURL = "https://mtf.onepay.vn/onecomm-pay/vpc.op?";

        $md5HashData = "";
        ksort($vpc);
        $appendAmp = 0;

        foreach ($vpc as $key => $value) {

            // create the md5 input and URL leaving out any fields that have no value
            if (strlen($value) > 0) {

                // this ensures the first paramter of the URL is preceded by the '?' char
                if ($appendAmp == 0) {
                    $vpcURL .= urlencode($key) . '=' . urlencode($value);
                    $appendAmp = 1;
                }
                else {
                    $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
                }
                //$md5HashData .= $value; sử dụng cả tên và giá trị tham số để mã hóa
                if ((strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                    $md5HashData .= $key . "=" . $value . "&";
                }
            }
        }
        $md5HashData = rtrim($md5HashData, "&");

        if (strlen(SECURE_SECRET_ATM) > 0) {
            $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*', SECURE_SECRET_ATM)));
        }

        wp_redirect($vpcURL);
        exit;
    }

}
else {
    wp_redirect(WP_SITEURL);
    exit;
}