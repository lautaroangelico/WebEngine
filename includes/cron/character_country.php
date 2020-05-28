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

// File Name
$file_name = basename(__FILE__);

// load databases
$mu = Connection::Database('MuOnline');
$me = Connection::Database('Me_MuOnline');

$characterCountryCache = loadCache('character_country.cache');
$characters = $mu->query_fetch("SELECT "._CLMN_CHR_NAME_.", "._CLMN_CHR_ACCID_." FROM "._TBL_CHR_."");
if(is_array($characters)) {
	foreach($characters as $row) {
		if(array_key_exists($row[_CLMN_CHR_NAME_], $characterCountryCache)) continue;
		$accountList[] = utf8_encode($row[_CLMN_CHR_ACCID_]);
	}
}

$accountList = array_unique($accountList);
if(is_array($accountList)) {
	foreach($accountList as $row) {
		$accountListArray[] = '\''.$row.'\'';
	}
	$accountListString = implode(',', $accountListArray);
	
	$accountCountry = $me->query_fetch("SELECT * FROM ".WEBENGINE_ACCOUNT_COUNTRY." WHERE account IN(".$accountListString.")");
	if(is_array($accountCountry)) {
		foreach($accountCountry as $row) {
			$accountCountryList[utf8_encode($row['account'])] = $row['country'];
		}
	}
	
	if(is_array($accountCountryList)) {
		$result = $characterCountryCache;
		foreach($characters as $row) {
			if(array_key_exists($row[_CLMN_CHR_NAME_], $characterCountryCache)) continue;
			if(!array_key_exists($row[_CLMN_CHR_ACCID_], $accountCountryList)) continue;
			$result[utf8_encode($row[_CLMN_CHR_NAME_])] = $accountCountryList[$row[_CLMN_CHR_ACCID_]];
		}
	}
}

if(is_array($result)) {
	$cacheData = encodeCache($result);
	updateCacheFile('character_country.cache', $cacheData);
}

// UPDATE CRON
updateCronLastRun($file_name);