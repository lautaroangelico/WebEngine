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
$mu = Connection::Database('MuOnline');
$me = Connection::Database('Me_MuOnline');

$characters = $mu->query_fetch("SELECT "._CLMN_CHR_NAME_.", "._CLMN_CHR_ACCID_." FROM "._TBL_CHR_."");
if(is_array($characters)) {
	foreach($characters as $row) {
		$accountCountry = $me->query_fetch_single("SELECT * FROM ".WEBENGINE_ACCOUNT_COUNTRY." WHERE account = ?", array($row[_CLMN_CHR_ACCID_]));
		if(!is_array($accountCountry)) continue;
		$result[$row[_CLMN_CHR_NAME_]] = $accountCountry['country'];
	}
	
	$cacheData = encodeCache($result);
	updateCacheFile('character_country.cache', $cacheData);
}

// UPDATE CRON
updateCronLastRun($file_name);