<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

define('access', 'api');

include('../includes/webengine.php');

// common class
$common = new common();

// Load PayPal Settings
loadModuleConfigs('donation.paypal');

  $raw_post_data = file_get_contents('php://input');
  $raw_post_array = explode('&', $raw_post_data);
  $myPost = array();
  foreach ($raw_post_array as $keyval)
  {
      $keyval = explode ('=', $keyval);
      if (count($keyval) == 2)
         $myPost[$keyval[0]] = urldecode($keyval[1]);
  }
  $req = 'cmd=_notify-validate';
  if(function_exists('get_magic_quotes_gpc'))
  {
       $get_magic_quotes_exits = true;
  } 
  foreach ($myPost as $key => $value)
  {        
       if($get_magic_quotes_exits == true && get_magic_quotes_gpc() == 1)
       { 
            $value = urlencode(stripslashes($value)); 
       }
       else
       {
            $value = urlencode($value);
       }
       $req .= "&$key=$value";
  }

$ch = curl_init();

/* check if sandbox is enabled */
if(mconfig('paypal_enable_sandbox')) {
	curl_setopt($ch, CURLOPT_URL, 'https://www.sandbox.paypal.com/cgi-bin/webscr');
} else {
	curl_setopt($ch, CURLOPT_URL, 'https://www.paypal.com/cgi-bin/webscr');
}

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.paypal.com'));
$res = curl_exec($ch);
curl_close($ch);
 
// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number']; // order id = md5(time())
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$txn_type = $_POST['txn_type'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$account_id = $_POST['custom'];
$user_id = Decode($account_id); // decoded user id

if (strcmp ($res, "VERIFIED") == 0) {
	
	if(strtolower($receiver_email) == strtolower(mconfig('paypal_email'))) {
		if(($txn_type == 'web_accept' OR $txn_type == 'subscr_payment') AND $payment_status == 'Completed') {
			if($tax > 0) { $payment_amount -=$tax; }
			
			/* Donation amount */
			$add_credits = floor($payment_amount*mconfig('paypal_conversion_rate'));
			
			/* Add credits */
			try {
				# user id
				if(!Validator::UnsignedNumber($user_id)) throw new Exception("invalid userid");
				
				# account info
				$accountInfo = $common->accountInformation($user_id);
				if(!is_array($accountInfo)) throw new Exception("invalid account");
				
				$creditSystem = new CreditSystem();
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
				$_GET['subpage'] = 'paypal';
				
				$creditSystem->addCredits($add_credits);
			} catch (Exception $ex) {
				die();
			}
			
			/* Create transaction */
			$common->paypal_transaction($txn_id,$user_id,$payment_amount,$payer_email,$item_number);
			
		} elseif($payment_status == 'Reversed' OR $payment_status == 'Refunded') {
			
			/* block account */
			$common->blockAccount($user_id);
			
			/* update transaction */
			$common->paypal_transaction_reversed_updatestatus($item_number);
			
		}
	
	}
}
else if (strcmp ($res, "INVALID") == 0) {
	// log for manual investigation
}
