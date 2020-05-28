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

// load database
$me = Connection::Database('Me_MuOnline');
$mu = Connection::Database('MuOnline');

$onlineAccounts = $me->query_fetch("SELECT "._CLMN_MS_MEMBID_." FROM "._TBL_MS_." WHERE "._CLMN_CONNSTAT_." = ?", array(1));
foreach($onlineAccounts as $row) {
	$onlineAccountList[] = '\''.utf8_encode($row[_CLMN_MS_MEMBID_]).'\'';
}

$result = array();
$onlineAccountListString = implode(',', $onlineAccountList);
$characterIDC = $mu->query_fetch("SELECT "._CLMN_GAMEIDC_." FROM "._TBL_AC_." WHERE "._CLMN_AC_ID_." IN(".$onlineAccountListString.")");
if(is_array($characterIDC)) {
	foreach($characterIDC as $idc) {
		$result[] = $idc[_CLMN_GAMEIDC_];
	}
}

$cacheData = encodeCache($result);
updateCacheFile('online_characters.cache', $cacheData);

// UPDATE CRON
updateCronLastRun($file_name);