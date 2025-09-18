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
$accountsDB = config('SQL_USE_2_DB', true) == true ? config('SQL_DB_2_NAME', true) : $charactersDB;

$result = array();

$query = "SELECT t2.GameIDC FROM ".$accountsDB.".[dbo].".'MEMB_STAT'." t1 INNER JOIN ".$charactersDB.".[dbo].AccountCharacter t2 ON t1.memb___id = t2.Id WHERE t1.ConnectStat = 1";

$onlineCharactersList = $me->query_fetch($query);
if(is_array($onlineCharactersList)) {
	foreach($onlineCharactersList as $onlineCharacterData) {
		if(in_array($onlineCharacterData['GameIDC'], $result)) continue;
		$result[] = $onlineCharacterData['GameIDC'];
	}
}

$cacheData = encodeCache($result);
updateCacheFile('online_characters.cache', $cacheData);

// UPDATE CRON
updateCronLastRun($file_name);