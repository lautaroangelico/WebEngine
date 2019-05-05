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
$database = Connection::Database('Me_MuOnline');

$temporalBans = $database->query_fetch("SELECT * FROM ".WEBENGINE_BANS."");
if(is_array($temporalBans)) {
	foreach($temporalBans as $tempBan) {
		$banTimestamp = $tempBan['ban_days']*86400+$tempBan['ban_date'];
		if(time() > $banTimestamp) {
			// lift ban
			$unban = $database->query("UPDATE "._TBL_MI_." SET "._CLMN_BLOCCODE_." = 0 WHERE "._CLMN_USERNM_." = ?", array($tempBan['account_id']));
			if($unban) {
				$database->query("DELETE FROM ".WEBENGINE_BAN_LOG." WHERE account_id = ?", array($tempBan['account_id']));
				$database->query("DELETE FROM ".WEBENGINE_BANS." WHERE account_id = ?", array($tempBan['account_id']));
			}
		}
	}
}

// UPDATE CRON
updateCronLastRun($file_name);