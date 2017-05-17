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

if(!isLoggedIn()) redirect(1,'login');

try {
	
	// Load Paymentwall Settings
	loadModuleConfigs('donation.paymentwall');
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	# Load Paymentwall Widget
	require_once(__PATH_CLASSES__ . 'paymentwall/paymentwall.php');
	Paymentwall_Base::setApiType(Paymentwall_Base::API_VC);
	Paymentwall_Base::setAppKey(mconfig('app_key'));
	Paymentwall_Base::setSecretKey(mconfig('secret_key'));

	$widget = new Paymentwall_Widget(
		$_SESSION['userid'], 
		'p10',
		array()
	);
	echo $widget->getHtmlCode(array('width' => 636, 'height' => 500));
	
} catch (Exception $ex) {
	message('error', $ex->getMessage());
}