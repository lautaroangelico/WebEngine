<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

// Access
define('access', 'api');

// Load WebEngine
if(!@include_once(rtrim(str_replace('\\','/', dirname(__DIR__)), '/') . '/includes/webengine.php')) throw new Exception('Could not load WebEngine.');

// Load PayPal Configurations
$cfg = loadConfigurations('donation.paypal');
if(!is_array($cfg)) {
	header("HTTP/1.1 500 Internal Server Error");
	die();
}

// PayPal Sandbox
$enable_sandbox = $cfg['paypal_enable_sandbox'];

// PayPal Seller Email
$seller_email = $cfg['paypal_email'];

// Instance
$ipn = new PaypalIPN();
if($enable_sandbox == 1) {
	$ipn->useSandbox();
}

// Verification
$verified = $ipn->verifyIPN();

// IPN
$paypal_ipn_status = "VERIFICATION FAILED";
if($verified) {
	try {
		
		// Check receiver email
		if(strtolower($_POST["receiver_email"]) != strtolower($seller_email)) throw new Exception('RECEIVER EMAIL MISMATCH');
		
		// common class
		$common = new common();
		
		// data
		$item_name = $_POST['item_name'];
		$item_number = $_POST['item_number'];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$txn_type = $_POST['txn_type'];
		$receiver_email = $_POST['receiver_email'];
		$payer_email = $_POST['payer_email'];
		$user_id = $_POST['custom'];
		
		// Process payment
		try {
			
			if($_POST['payment_status'] == 'Completed') {
				
				// donation amount
				$add_credits = floor($payment_amount*$cfg['paypal_conversion_rate']);
				
				// account
				if(!Validator::UnsignedNumber($user_id)) throw new Exception("invalid userid");
				
				# account info
				$Account = new Account();
				$accountInfo = $Account->accountInformation($user_id);
				if(!is_array($accountInfo)) throw new Exception("invalid account");
				
				$creditSystem = new CreditSystem();
				$creditSystem->setConfigId($cfg['credit_config']);
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
				$_GET['subpage'] = 'paypal';
				
				$creditSystem->addCredits($add_credits);
				
				$paypal_ipn_status = "Completed Successfully";
				
				// paypal log
				$common->paypal_transaction($txn_id,$user_id,$payment_amount,$payer_email,$item_number);
			
			} else {
				
				/* block account */
				$common->blockAccount($user_id);
				
				/* update transaction */
				$common->paypal_transaction_reversed_updatestatus($item_number);
				
			}
			
		} catch(Exception $ex) {
			$paypal_ipn_status = $ex->getMessage();
		}
		
	} catch(Exception $ex) {
		$paypal_ipn_status = $ex->getMessage();
	}
} elseif($enable_sandbox) {
    if($_POST["test_ipn"] != 1) {
        $paypal_ipn_status = "RECEIVED FROM LIVE WHILE SANDBOXED";
    }
} elseif($_POST["test_ipn"] == 1) {
    $paypal_ipn_status = "RECEIVED FROM SANDBOX WHILE LIVE";
}

// OK
header("HTTP/1.1 200 OK");