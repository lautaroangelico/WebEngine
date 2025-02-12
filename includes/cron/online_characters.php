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

// load database
$me = Connection::Database('Me_MuOnline');
$charactersDB = config('SQL_DB_NAME', true);
$accountsDB = config('SQL_USE_2_DB', true) == true ? config('SQL_DB_2_NAME', true) : $CharactersDB;

$result = array();

$query = "SELECT t2."._CLMN_GAMEIDC_." FROM ".$accountsDB.".[dbo]."._TBL_MS_." t1 INNER JOIN ".$charactersDB.".[dbo]."._TBL_AC_." t2 ON t1."._CLMN_MS_MEMBID_." = t2."._CLMN_AC_ID_." WHERE t1."._CLMN_CONNSTAT_." = 1";

$onlineCharactersList = $me->query_fetch($query);
if(is_array($onlineCharactersList)) {
	foreach($onlineCharactersList as $onlineCharacterData) {
		if(in_array($onlineCharacterData[_CLMN_GAMEIDC_], $result)) continue;
		$result[] = $onlineCharacterData[_CLMN_GAMEIDC_];
	}
}

$cacheData = encodeCache($result);
updateCacheFile('online_characters.cache', $cacheData);

// UPDATE CRON
updateCronLastRun($file_name);