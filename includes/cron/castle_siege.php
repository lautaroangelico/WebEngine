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
$db = Connection::Database('MuOnline');

# castle data
$castleData = $db->query_fetch_single("SELECT t1."._CLMN_MCD_GUILD_OWNER_.", t2."._CLMN_GUILD_MASTER_.", CONVERT(varchar(max), t2."._CLMN_GUILD_LOGO_.", 2) as "._CLMN_GUILD_LOGO_.", t1."._CLMN_MCD_MONEY_.", t1."._CLMN_MCD_TRC_.", t1."._CLMN_MCD_TRS_.", t1."._CLMN_MCD_THZ_." FROM "._TBL_MUCASTLE_DATA_." as t1 INNER JOIN "._TBL_GUILD_." as t2 ON t2."._CLMN_GUILD_NAME_." = t1."._CLMN_MCD_GUILD_OWNER_."");
if(is_array($castleData)) {	
	# registered guilds
	$castleGuilds = $db->query_fetch("SELECT "._CLMN_MCRS_GUILD_." FROM "._TBL_MUCASTLE_RS_);
	if(is_array($castleGuilds)) {
		foreach($castleGuilds as $row) {
			$guildList[] = $row[_CLMN_MCRS_GUILD_];
		}
	}
	
	$result = array(
		'castle' => $castleData,
		'guilds' => $guildList
	);
	
	$cacheDATA = encodeCache($result);
	updateCacheFile('castle_siege.cache', $cacheDATA);
}

// UPDATE CRON
updateCronLastRun($file_name);