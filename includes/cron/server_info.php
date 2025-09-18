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

# total accounts
$totalAccounts = 0;
$countAccounts = $me->query_fetch_single("SELECT COUNT(*) as totalAccounts FROM MEMB_INFO");
if(is_array($countAccounts)) $totalAccounts = $countAccounts['totalAccounts'];
$serverInfo[] = $totalAccounts;

# total characters
$totalCharacters = 0;
$countCharacters = $mu->query_fetch_single("SELECT COUNT(*) as totalCharacters FROM Character");
if(is_array($countCharacters)) $totalCharacters = $countCharacters['totalCharacters'];
$serverInfo[] = $totalCharacters;

# total guilds
$totalGuilds = 0;
$countGuilds = $mu->query_fetch_single("SELECT COUNT(*) as totalGuilds FROM Guild");
if(is_array($countGuilds)) $totalGuilds = $countGuilds['totalGuilds'];
$serverInfo[] = $totalGuilds;

# total online
$totalOnline = 0;
$countOnline = $me->query_fetch_single("SELECT COUNT(*) as totalOnline FROM MEMB_STAT WHERE ConnectStat = 1");
if(is_array($countOnline)) $totalOnline = $countOnline['totalOnline'];
$serverInfo[] = $totalOnline;
	
if(is_array($serverInfo)) {
	$cacheDATA = implode("|",$serverInfo);
	UpdateCache('server_info.cache',$cacheDATA);
}

// UPDATE CRON
updateCronLastRun($file_name);