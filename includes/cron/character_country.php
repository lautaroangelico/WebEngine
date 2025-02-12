<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

// File Name
$file_name = basename(__FILE__);

// load databases
$me = Connection::Database('Me_MuOnline');
$charactersDB = config('SQL_DB_NAME', true);
$accountsDB = config('SQL_USE_2_DB', true) == true ? config('SQL_DB_2_NAME', true) : $CharactersDB;

$query = "SELECT t2."._CLMN_CHR_NAME_.", t1.country FROM ".$accountsDB.".[dbo].".WEBENGINE_ACCOUNT_COUNTRY." t1 INNER JOIN ".$charactersDB.".[dbo]."._TBL_CHR_." t2 ON t1.account = t2."._CLMN_CHR_ACCID_."";

$charactersCountryList = $me->query_fetch($query);
$result = array();
if(is_array($charactersCountryList)) {
	foreach($charactersCountryList as $characterCountryData) {
		$result[$characterCountryData[_CLMN_CHR_NAME_]] = $characterCountryData['country'];
	}
}

$cacheData = encodeCache($result);
updateCacheFile('character_country.cache', $cacheData);

// UPDATE CRON
updateCronLastRun($file_name);