<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.0.9.8
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

// File Name
$file_name = basename(__FILE__);

// Gather database data

# castle data
$castleData = $dB->query_fetch("SELECT t1."._CLMN_MCD_GUILD_OWNER_.", t2."._CLMN_GUILD_LOGO_.", t1."._CLMN_MCD_MONEY_.", t1."._CLMN_MCD_TRC_.", t1."._CLMN_MCD_TRS_.", t1."._CLMN_MCD_THZ_." FROM "._TBL_MUCASTLE_DATA_." as t1 INNER JOIN "._TBL_GUILD_." as t2 ON t2."._CLMN_GUILD_NAME_." = t1."._CLMN_MCD_GUILD_OWNER_."");

# registered guilds
$castleGuilds = $dB->query_fetch("SELECT "._CLMN_MCRS_GUILD_." FROM "._TBL_MUCASTLE_RS_);
if(is_array($castleGuilds)) {
	$guildList = array();
	foreach($castleGuilds as $row) {
		$guildList[] = $row[_CLMN_MCRS_GUILD_];
	}
	
	$data = array($castleData[0], $guildList);
}

# data
$data = array($castleData[0]);
	
if(is_array($data)) {
	$cacheDATA = BuildCacheData($data);
	UpdateCache('castle_siege.cache',$cacheDATA);
}

// UPDATE CRON
updateCronLastRun($file_name);
