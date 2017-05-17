<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

/* BAN SYSTEM CRON JOB */

// File Name
$file_name = basename(__FILE__);

// Build Directory Path
$dir_path = str_replace('\\','/',dirname(dirname(__FILE__))).'/';

// Load WebEngine
include($dir_path . 'webengine.php');

$temporalBans = $dB->query_fetch("SELECT * FROM WEBENGINE_BANS");
if(is_array($temporalBans)) {
	foreach($temporalBans as $tempBan) {
		$banTimestamp = $tempBan['ban_days']*86400+$tempBan['ban_date'];
		if(time() > $banTimestamp) {
			// lift ban
			$db = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
			$unban = $db->query("UPDATE "._TBL_MI_." SET "._CLMN_BLOCCODE_." = 0 WHERE "._CLMN_USERNM_." = ?", array($tempBan['account_id']));
			if($unban) {
				$dB->query("DELETE FROM WEBENGINE_BAN_LOG WHERE account_id = ?", array($tempBan['account_id']));
				$dB->query("DELETE FROM WEBENGINE_BANS WHERE account_id = ?", array($tempBan['account_id']));
			}
		}
	}
}

// UPDATE CRON
updateCronLastRun($file_name);
