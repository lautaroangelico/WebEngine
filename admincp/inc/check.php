<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9.4
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

$configError = array();
$writablePaths = array(
	'cache/',
	'cache/news/',
	'cache/profiles/guilds/',
	'cache/profiles/players/',
	'cache/castle_siege.cache',
	'cache/cron.cache',
	'cache/downloads.cache',
	'cache/news.cache',
	'cache/plugins.cache',
	'cache/rankings_gens.cache',
	'cache/rankings_gr.cache',
	'cache/rankings_guilds.cache',
	'cache/rankings_level.cache',
	'cache/rankings_master.cache',
	'cache/rankings_online.cache',
	'cache/rankings_pk.cache',
	'cache/rankings_pvplaststand.cache',
	'cache/rankings_resets.cache',
	'cache/rankings_votes.cache',
	'cache/server_info.cache',
	'config/email.xml',
	'config/navbar.json',
	'config/usercp.json',
	'config/webengine.json',
	'config/modules/castlesiege.xml',
	'config/modules/contact.xml',
	'config/modules/donation.paymentwall.xml',
	'config/modules/donation.paypal.xml',
	'config/modules/donation.superrewards.xml',
	'config/modules/donation.westernunion.xml',
	'config/modules/donation.xml',
	'config/modules/downloads.xml',
	'config/modules/forgotpassword.xml',
	'config/modules/login.xml',
	'config/modules/news.xml',
	'config/modules/profiles.xml',
	'config/modules/rankings.xml',
	'config/modules/register.xml',
	'config/modules/usercp.addstats.xml',
	'config/modules/usercp.buyzen.xml',
	'config/modules/usercp.clearpk.xml',
	'config/modules/usercp.clearskilltree.xml',
	'config/modules/usercp.myaccount.xml',
	'config/modules/usercp.myemail.xml',
	'config/modules/usercp.mymasterkey.xml',
	'config/modules/usercp.mypassword.xml',
	'config/modules/usercp.reset.xml',
	'config/modules/usercp.resetstats.xml',
	'config/modules/usercp.unstick.xml',
	'config/modules/usercp.vip.xml',
	'config/modules/usercp.vote.xml',
);

// File permission check
foreach($writablePaths as $thisPath) {
	if(file_exists(__PATH_INCLUDES__ . $thisPath)) {
		if(!is_writable(__PATH_INCLUDES__ . $thisPath)) {
			$configError[] = "<span style=\"color:#aaaaaa;\">[Permission Error]</span> " . $thisPath . " <span style=\"color:red;\">(file must be writable)</span>";
		}
	} else {
		$configError[] = "<span style=\"color:#aaaaaa;\">[Not Found]</span> " . $thisPath. " <span style=\"color:orange;\">(re-upload file)</span>";
	}
}

// Encryption hash check
if(!check_value($config['encryption_hash'])) {
	$configError[] = "<span style=\"color:#aaaaaa;\">[Configuration]</span> encryption_hash <span style=\"color:green;\">(must be configured)</span>";
} else {
	if(!in_array(strlen($config['encryption_hash']), array(16,24,32))) {
		$configError[] = "<span style=\"color:#aaaaaa;\">[Configuration]</span> encryption_hash <span style=\"color:green;\">(must have 16, 24 or 32 characters)</span>";
	}
}

// Check cURL
if(!function_exists('curl_version')) $configError[] = "<span style=\"color:#aaaaaa;\">[PHP]</span> <span style=\"color:green;\">curl not loaded (WebEngine required cURL)</span>";

if(count($configError) >= 1) {
	throw new Exception("<strong>The following errors ocurred:</strong><br /><br />" . implode("<br />", $configError));
}