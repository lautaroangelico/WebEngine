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

/* -----------------------------------------------------------
	Super Rewards API
	
		Parameters:
					id = transaction id
					new = amount of points
					total = total number of points accumulated
					uid = user id
					oid = SuperRewards offer identifier
					sig = security hash
					
		Response: 1 | 0
		
		Error Codes:
					100 = missing parameters
					101 = signature integrity
					102 = invalid account
					103 = account online
					104 = transaction already processed
		
----------------------------------------------------------- */

// Load WebEngine
include('../includes/webengine.php');

// Load Super Rewards Settings
loadModuleConfigs('donation.superrewards');

// Load Configs
$sr['secret'] = mconfig('sr_secret');

// SR.Parameters
$id = $_REQUEST['id'];
$new = $_REQUEST['new'];
$total = $_REQUEST['total'];
$uid = $_REQUEST['uid']; // username
$oid = $_REQUEST['oid'];
$sig = $_REQUEST['sig'];

// SR.Signature
$signature = md5($id . ':' . $new . ':' . $uid . ':' . $sr['secret']);

// Catch ERROR
$error = false;

// Check Parameters
if(!check_value($id) || !check_value($new) || !check_value($total) || !check_value($uid) || !check_value($oid) || !check_value($sig)) {
	$error = true;
	$code = 100;
}

// Validate Signature Integrity
if($sig != $signature) {
	$error = true;
	$code = 101;
}

// Check if Account Exists
if(!$common->userExists($uid)) {
	$error = true;
	$code = 102;
}

// Check if Account is Online
if($common->accountOnline($uid) && mconfig('check_online')) {
	$error = true;
	$code = 103;
}

// check if transaction id exists
$checkTID = $dB->query_fetch_single("SELECT * FROM WEBENGINE_SR_TRANSACTIONS WHERE transaction_id = ?", array($id));
if(is_array($checkTID)) {
	$error = true;
	$code = 104;
}

// Retrieve user ID
$user_id = $common->retrieveUserID($uid);
	
// Check Error(s)
if(!$error) {
	
	// Add Credits
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
		$_GET['subpage'] = 'superrewards';
		
		$creditSystem->addCredits($new);
		
		// Add Logs
		$add_logs_data = array(
			$id,
			$user_id,
			$new,
			time()
		);
		
		$add_logs = $dB->query("INSERT INTO WEBENGINE_SR_TRANSACTIONS (transaction_id,user_id,credits_amount,transaction_date) VALUES (?, ?, ?, ?)", $add_logs_data);
		
		// Response
		die('1');
		
	} catch (Exception $ex) {
		// Log Error + code
		$add_error_logs_data = array(
			$id,
			$user_id,
			$new,
			time(),
			$code
		);
		
		$add_error_logs = $dB->query("INSERT INTO WEBENGINE_SR_ERROR_LOGS (transaction_id,user_id,credits_amount,transaction_date,error_code) VALUES (?, ?, ?, ?, ?)", $add_error_logs_data);
		
		// Response
		die('0');
	}
} else {
	// Log Error + code
	$add_error_logs_data = array(
		$id,
		$user_id,
		$new,
		time(),
		$code
	);
		
	$add_error_logs = $dB->query("INSERT INTO WEBENGINE_SR_ERROR_LOGS (transaction_id,user_id,credits_amount,transaction_date,error_code) VALUES (?, ?, ?, ?, ?)", $add_error_logs_data);
		
	// Response
	die('0');
}


?>