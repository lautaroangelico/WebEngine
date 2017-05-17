<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

include('../includes/webengine.php');

// Load PayPal Settings
loadModuleConfigs('donation.paypal');

//read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
}

//post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Host: www.paypal.com\r\n";
$header .= "Connection: close\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$txn_type = $_POST['txn_type'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$account_id = $_POST['custom'];
$user_id = Decode($account_id); // decoded user id

//error connecting to paypal
if (!$fp) {
    //
}

//successful connection
if ($fp) {
    fputs ($fp, $header . $req);

    while (!feof($fp)) {
        $res = fgets ($fp, 1024);
        $res = trim($res); //NEW & IMPORTANT

        if (strcmp($res, "VERIFIED") == 0) {

            if(strtolower($receiver_email) == strtolower(mconfig('paypal_email'))) {
				if(($txn_type == 'web_accept' OR $txn_type == 'subscr_payment') AND $payment_status == 'Completed') {

					$add_credits = floor($payment_amount*mconfig('paypal_conversion_rate'));
					
					/* Add credits */
					try {
						# user id
						if(!Validator::UnsignedNumber($user_id)) throw new Exception("invalid userid");

						# account info
						$accountInfo = $common->accountInformation($user_id);
						if(!is_array($accountInfo)) throw new Exception("invalid account");

						$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);
						$creditSystem->setConfigId(mconfig('credit_config'));
						$configSettings = $creditSystem->showConfigs(true);
						switch($configSettings['config_user_col_id']) {
							case 'userid':
								$creditSystem->setIdentifier($accountInfo[_CLMN_MEMBID_]);
								break;
							case 'username':
								$creditSystem->setIdentifier($accountInfo[_CLMN_USERNM_]);
								break;
							case 'email':
								$creditSystem->setIdentifier($accountInfo[_CLMN_EMAIL_]);
								break;
							default:
								throw new Exception("invalid identifier");
						}
						
						$_GET['page'] = 'api';
						$_GET['subpage'] = 'paypal(win)';
							
						$creditSystem->addCredits($add_credits);
					} catch (Exception $ex) {
						die();
					}
					
					$common->paypal_transaction($txn_id,$user_id,$payment_amount,$payer_email,$item_number);
					
				} elseif($payment_status == 'Reversed' OR $payment_status == 'Refunded') {
					$common->blockAccount($user_id);
					$common->paypal_transaction_reversed_updatestatus($item_number);
				}
			}


        }

        if (strcmp ($res, "INVALID") == 0) {
            // INVALID
        }
    }

    fclose($fp);
}

?>
