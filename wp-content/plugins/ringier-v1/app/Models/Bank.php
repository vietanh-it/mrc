<?php
/**
 * Created by PhpStorm.
 * User: VietAnh
 * Date: 2016-30-08
 * Time: 07:49 PM
 */
namespace RVN\Models;

class Bank
{
    private static $instance;


    /**
     * Users constructor.
     */
    function __construct()
    {

    }


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Bank();
        }

        return self::$instance;
    }


    public function getResponseDescription($responseCode)
    {
        // $responseCode = 0 : success

        switch ($responseCode) {
            case "0" :
                $result = "Transaction Successful";
                break;
            case "?" :
                $result = "Transaction status is unknown";
                break;
            case "1" :
                $result = "Bank system reject";
                break;
            case "2" :
                $result = "Bank Declined Transaction";
                break;
            case "3" :
                $result = "No Reply from Bank";
                break;
            case "4" :
                $result = "Expired Card";
                break;
            case "5" :
                $result = "Insufficient funds";
                break;
            case "6" :
                $result = "Error Communicating with Bank";
                break;
            case "7" :
                $result = "Payment Server System Error";
                break;
            case "8" :
                $result = "Transaction Type Not Supported";
                break;
            case "9" :
                $result = "Bank declined transaction (Do not contact Bank)";
                break;
            case "A" :
                $result = "Transaction Aborted";
                break;
            case "C" :
                $result = "Transaction Cancelled";
                break;
            case "D" :
                $result = "Deferred transaction has been received and is awaiting processing";
                break;
            case "F" :
                $result = "3D Secure Authentication failed";
                break;
            case "I" :
                $result = "Card Security Code verification failed";
                break;
            case "L" :
                $result = "Shopping Transaction Locked (Please try the transaction again later)";
                break;
            case "N" :
                $result = "Cardholder is not enrolled in Authentication scheme";
                break;
            case "P" :
                $result = "Transaction has been received by the Payment Adaptor and is being processed";
                break;
            case "R" :
                $result = "Transaction was not processed - Reached limit of retry attempts allowed";
                break;
            case "S" :
                $result = "Duplicate SessionID (OrderInfo)";
                break;
            case "T" :
                $result = "Address Verification Failed";
                break;
            case "U" :
                $result = "Card Security Code Failed";
                break;
            case "V" :
                $result = "Address Verification and Card Security Code Failed";
                break;
            case "99" :
                $result = "User Cancel";
                break;
            default  :
                $result = "Unable to be determined";
        }

        return $result;
    }


    public function isValidHash($get)
    {
        $vpc_SecureHash = valueOrNull($get['vpc_SecureHash']);
        $vpc_TxnResponseCode = isset($get['vpc_TxnResponseCode']) ? $get['vpc_TxnResponseCode'] : false;

        $result = 0;
        if (!empty($vpc_SecureHash) && ($vpc_TxnResponseCode !== false) && !empty($get)) {

            if ($vpc_TxnResponseCode == "0") {
                ksort($get);
                $md5HashData = "";
                foreach ($_GET as $key => $value) {
                    if ($key != "vpc_SecureHash"
                        && (strlen($value) > 0)
                        && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))
                    ) {
                        $md5HashData .= $key . "=" . $value . "&";
                    }
                }
                $md5HashData = rtrim($md5HashData, "&");

                if (strtoupper($vpc_SecureHash) == strtoupper(hash_hmac('SHA256', $md5HashData, pack('H*', SECURE_SECRET)))) {
                    $result = 1;
                }
                else {
                    $result = 2;
                }
            }

        }

        return $result;
    }

}
