<?php
// *********************
// START OF MAIN PROGRAM
// *********************

global $post;
$booking = \RVN\Models\Booking::init();

$bank = \RVN\Models\Bank::init();
$vpc_AcqResponseCode = valueOrNull($_GET["vpc_AcqResponseCode"]);

var_dump($_GET);
$isValidHash = $bank->isValidHash($_GET);
var_dump($isValidHash);
if ($isValidHash == true) {

    // Standard Receipt Data
    $data = [];
    $data['vpc_Amount'] = valueOrNull($_GET["vpc_Amount"], "empty value");
    $data['vpc_Locale'] = valueOrNull($_GET["vpc_Locale"], "empty value");
    $data['vpc_BatchNo'] = valueOrNull($_GET["vpc_BatchNo"], "empty value");
    $data['vpc_Command'] = valueOrNull($_GET["vpc_Command"], "empty value");
    $data['vpc_Message'] = valueOrNull($_GET["vpc_Message"], "empty value");
    $data['vpc_Version'] = valueOrNull($_GET["vpc_Version"], "empty value");
    $data['vpc_Card'] = valueOrNull($_GET["vpc_Card"], "empty value");
    $data['vpc_OrderInfo'] = valueOrNull($_GET["vpc_OrderInfo"], "empty value");
    $data['vpc_ReceiptNo'] = valueOrNull($_GET["vpc_ReceiptNo"], "empty value");
    $data['vpc_Merchant'] = valueOrNull($_GET["vpc_Merchant"], "empty value");

    $data['Title'] = valueOrNull($_GET["Title"], "empty value");

    // $authorizeID = null2unknown($_GET["vpc_AuthorizeId"]);
    $data['vpc_MerchTxnRef'] = valueOrNull($_GET["vpc_MerchTxnRef"], "empty value");
    $data['vpc_TransactionNo'] = valueOrNull($_GET["vpc_TransactionNo"], "empty value");
    $data['vpc_AcqResponseCode'] = valueOrNull($_GET["vpc_AcqResponseCode"], "empty value");
    $data['vpc_TxnResponseCode'] = valueOrNull($_GET["vpc_TxnResponseCode"], "empty value");

    // 3-D Secure Data
    $data['vpc_VerType'] = array_key_exists("vpc_VerType", $_GET) ? $_GET["vpc_VerType"] : "empty value";
    $data['vpc_VerStatus'] = array_key_exists("vpc_VerStatus", $_GET) ? $_GET["vpc_VerStatus"] : "empty value";
    $data['vpc_VerToken'] = array_key_exists("vpc_VerToken", $_GET) ? $_GET["vpc_VerToken"] : "empty value";
    $data['vpc_VerSecurityLevel'] = array_key_exists("vpc_VerSecurityLevel", $_GET) ? $_GET["vpc_VerSecurityLevel"] : "empty value";
    $data['vpc_3DSenrolled'] = array_key_exists("vpc_3DSenrolled", $_GET) ? $_GET["vpc_3DSenrolled"] : "empty value";
    $data['vpc_3DSXID'] = array_key_exists("vpc_3DSXID", $_GET) ? $_GET["vpc_3DSXID"] : "empty value";
    $data['vpc_3DSECI'] = array_key_exists("vpc_3DSECI", $_GET) ? $_GET["vpc_3DSECI"] : "empty value";
    $data['vpc_3DSstatus'] = array_key_exists("vpc_3DSstatus", $_GET) ? $_GET["vpc_3DSstatus"] : "empty value";

    $booking->finishBooking(get_current_user_id(), $post->ID, $data);
}

$transStatus = "";
if ($hashValidated == "CORRECT" && $txnResponseCode == "0") {
    $transStatus = "Giao dịch thành công";
}
elseif ($hashValidated == "INVALID HASH" && $txnResponseCode == "0") {
    $transStatus = "Giao dịch Pendding";
}
else {
    $transStatus = "Giao dịch thất bại";
}

echo $transStatus . "<br/>";

if (!empty($data)) {
    echo $bank->getResponseDescription($data['txnResponseCode']);
}