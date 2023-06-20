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
?>
<h1 class="page-header">Banear Cuenta</h1>
<?php
	$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
	
	// Add ban system cron if doesn't exist
	$banCron = "INSERT INTO ".WEBENGINE_CRON." (cron_name, cron_description, cron_file_run, cron_run_time, cron_status, cron_protected, cron_file_md5) VALUES ('Ban System', 'Scheduled task to lift temporal bans', 'temporal_bans.php', '3600', 1, 1, '1a3787c5179afddd1bfb09befda3d1c7')";
	$checkBanCron = $database->query_fetch_single("SELECT * FROM ".WEBENGINE_CRON." WHERE cron_file_run = ?", array("temporal_bans.php"));
	if(!is_array($checkBanCron)) $database->query($banCron);
	
	if(check_value($_POST['submit_ban'])) {
		try {
			if(!check_value($_POST['ban_account'])) throw new Exception("Please enter the account username.");
			if(!$common->userExists($_POST['ban_account'])) throw new Exception("Invalid account username.");
			if(!check_value($_POST['ban_days'])) throw new Exception("Please enter the amount of days.");
			if(!Validator::UnsignedNumber($_POST['ban_days'])) throw new Exception("Invalid ban days.");
			if(check_value($_POST['ban_reason'])) {
				if(!Validator::Length($_POST['ban_reason'], 100, 1)) throw new Exception("Invalid ban reason.");
			}
			
			// Check Online Status
			if($common->accountOnline($_POST['ban_account'])) throw new Exception("The account is currently online.");
			
			// Account Information
			$userID = $common->retrieveUserID($_POST['ban_account']);
			$accountData = $common->accountInformation($userID);
			
			// Check if aready banned
			if($accountData[_CLMN_BLOCCODE_] == 1) throw new Exception("This account is already banned.");
			
			// Ban Type
			$banType = ($_POST['ban_days'] >= 1 ? "temporal" : "permanent");
			
			// Log Ban
			$banLogData = array(
				'acc' => $_POST['ban_account'],
				'by' => $_SESSION['username'],
				'type' => $banType,
				'date' => time(),
				'days' => $_POST['ban_days'],
				'reason' => (check_value($_POST['ban_reason']) ? $_POST['ban_reason'] : "")
			);
			
			$logBan = $database->query("INSERT INTO ".WEBENGINE_BAN_LOG." (account_id, banned_by, ban_type, ban_date, ban_days, ban_reason) VALUES (:acc, :by, :type, :date, :days, :reason)", $banLogData);
			if(!$logBan) throw new Exception("Could not log ban (check tables)[1].");
			
			// Add temporal ban
			if($banType == "temporal") {
				$tempBanData = array(
					'acc' => $_POST['ban_account'],
					'by' => $_SESSION['username'],
					'date' => time(),
					'days' => $_POST['ban_days'],
					'reason' => (check_value($_POST['ban_reason']) ? $_POST['ban_reason'] : "")
				);
				$tempBan = $database->query("INSERT INTO ".WEBENGINE_BANS." (account_id, banned_by, ban_date, ban_days, ban_reason) VALUES (:acc, :by, :date, :days, :reason)", $tempBanData);
				if(!$tempBan) throw new Exception("Could not add temporal ban (check tables)[2]. - " . $database->error);
			}
			
			// Ban Account
			$banAccount = $database->query("UPDATE "._TBL_MI_." SET "._CLMN_BLOCCODE_." = ? WHERE "._CLMN_USERNM_." = ?", array(1, $_POST['ban_account']));
			if(!$banAccount) throw new Exception("Could not ban account.");
			
			message('success', 'Account Banned');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
?>
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<form action="" method="post" role="form">
					<div class="input-group mb-3">
						<span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
						<span class="input-group-text" id="basic-addon1">Cuenta</span>
						<input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" name="ban_account" id="acc">
					</div>
					<div class="input-group mb-3">
						<span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar"></i></span>
						<span class="input-group-text" id="basic-addon2">Dias</span>
						<input type="number" class="form-control" placeholder="Dias (0 para permanente)" aria-label="Dias" aria-describedby="basic-addon2" name="ban_days" id="days">
					</div>
					<div class="input-group mb-3">
						<span class="input-group-text" id="basic-addon3"><i class="fas fa-exclamation-triangle"></i></span>
						<span class="input-group-text" id="basic-addon3">Razon</span>
						<input type="text" class="form-control" placeholder="Razon (Opcional)" aria-label="Razon" aria-describedby="basic-addon3" name="ban_reason" id="reason">
					</div>
					<input type="submit" name="submit_ban" class="btn btn-primary" value="Banear Cuenta"/>
				</form>
			</div>
		</div>
	</div>
</div>