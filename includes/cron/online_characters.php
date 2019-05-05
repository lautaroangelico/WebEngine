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

// load database
$me = Connection::Database('Me_MuOnline');

$onlineAccounts = $me->query_fetch("SELECT "._CLMN_MS_MEMBID_." FROM "._TBL_MS_." WHERE "._CLMN_CONNSTAT_." = ?", array(1));
if(is_array($onlineAccounts)) {
	$Character = new Character();
	foreach($onlineAccounts as $row) {
		$characterIDC = $Character->AccountCharacterIDC($row[_CLMN_MS_MEMBID_]);
		if(!check_value($characterIDC)) continue;
		$result[] = $characterIDC;
	}
	
	$cacheData = encodeCache($result);
	updateCacheFile('online_characters.cache', $cacheData);
}

// UPDATE CRON
updateCronLastRun($file_name);