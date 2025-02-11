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

// File Name
$file_name = basename(__FILE__);

// load databases
$db = Connection::Database('Me_MuOnline');

// add country to accounts with no country
$accountList = $db->query_fetch("SELECT TOP 50 * FROM "._TBL_MS_." WHERE "._CLMN_MS_MEMBID_." NOT IN(SELECT account FROM ".WEBENGINE_ACCOUNT_COUNTRY.") AND "._CLMN_MS_IP_." IS NOT NULL");
if(is_array($accountList)) {
	$Account = new Account();
	foreach($accountList as $row) {
		$countryCode = getCountryCodeFromIp($row[_CLMN_MS_IP_]);
		if(!check_value($countryCode)) continue;
		$Account->setAccount($row[_CLMN_MS_MEMBID_]);
		$Account->setCountry($countryCode);
		$Account->insertAccountCountry();
	}
}

// UPDATE CRON
updateCronLastRun($file_name);