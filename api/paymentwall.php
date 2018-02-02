<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.0.9.9
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2013-2018 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * https://opensource.org/licenses/MIT
 */

define('access', 'api');

include('../includes/webengine.php');

try {
	// Load Paymentwall Settings
	loadModuleConfigs('donation.paymentwall');
	
	if(!mconfig('active')) throw new Exception('Not active.');
	
	# Load Paymentwall Widget
	require_once(__PATH_CLASSES__ . 'paymentwall/paymentwall.php');
	Paymentwall_Base::setApiType(Paymentwall_Base::API_VC);
	Paymentwall_Base::setAppKey(mconfig('app_key'));
	Paymentwall_Base::setSecretKey(mconfig('secret_key'));

	$pingback = new Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);

	if($pingback->validate()) {
		$productId = $pingback->getProduct()->getId();
		if($pingback->isDeliverable()) {
			// deliver the product
			# account info
			$accountInfo = $common->accountInformation($_GET['uid']);
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
			$_GET['subpage'] = 'paymentwall';
			
			$creditSystem->addCredits($_GET['currency']);
		
			throw new Exception('OK');
		} else if($pingback->isCancelable()) {
			// withdraw the product
			$common->blockAccount($_GET['uid']);
			throw new Exception('OK');
		}
	} else {
		throw new Exception($pingback->getErrorSummary());
	}
} catch (Exception $ex) {
	echo $ex->getMessage();
}