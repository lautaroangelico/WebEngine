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
<h1 class="page-header">Last 50 Bans</h1>
<?php
	$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
	
	if(check_value($_GET['liftban'])) {
		try {
			if(!Validator::UnsignedNumber($_GET['liftban'])) throw new Exception("Invalid ban id.");
			
			// Retrieve Ban Information
			$banInfo = $database->query_fetch_single("SELECT * FROM ".WEBENGINE_BAN_LOG." WHERE id = ?", array($_GET['liftban']));
			if(!is_array($banInfo)) throw new Exception("Ban ID does not exist.");
			
			// Check account status
			//if($common->accountOnline($banInfo['account_id'])) throw new Exception("The account is online.");
			
			// Unban Account
			$unban = $database->query("UPDATE "._TBL_MI_." SET "._CLMN_BLOCCODE_." = 0 WHERE "._CLMN_USERNM_." = ?", array($banInfo['account_id']));
			if(!$unban) throw new Exception("Could not update account information (unban).");
			
			// Remove Ban log
			$database->query("DELETE FROM ".WEBENGINE_BAN_LOG." WHERE account_id = ?", array($banInfo['account_id']));
			$database->query("DELETE FROM ".WEBENGINE_BANS." WHERE account_id = ?", array($banInfo['account_id']));
			
			message('success', 'Account ban lifted');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel-body">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs">
				<li class="active"><a href="#temp" data-toggle="tab" aria-expanded="true">Temporal</a>
				</li>
				<li class=""><a href="#perm" data-toggle="tab" aria-expanded="false">Permanent</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane fade active in" id="temp"><br />
				<?php
					$tBans = $database->query_fetch("SELECT TOP 25 * FROM ".WEBENGINE_BAN_LOG." WHERE ban_type = ? ORDER BY id DESC", array("temporal"));
					if(is_array($tBans)) {
						echo '<table class="table table-condensed">';
							echo '<thead>';
							echo '<tr>';
								echo '<th>Account</th>';
								echo '<th>Banned By</th>';
								echo '<th>Date</th>';
								echo '<th>Days</th>';
								echo '<th>Reason</th>';
								echo '<th></th>';
							echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
							foreach($tBans as $temporalBan) {
								echo '<tr>';
									echo '<td>'.$temporalBan['account_id'].'</td>';
									echo '<td>'.$temporalBan['banned_by'].'</td>';
									echo '<td>'.date("Y-m-d H:i", $temporalBan['ban_date']).'</td>';
									echo '<td>'.$temporalBan['ban_days'].'</td>';
									echo '<td>'.$temporalBan['ban_reason'].'</td>';
									echo '<td style="text-align:right;"><a href="index.php?module='.$_REQUEST['module'].'&liftban='.$temporalBan['id'].'" class="btn btn-danger btn-xs">Lift Ban</a></td>';
								echo '</tr>';
							}
						echo '</tbody>';
						echo '</table>';
					} else {
						message('warning', 'There are no temporal bans logged.', ' ');
					}
				?>
				</div>
				<div class="tab-pane fade" id="perm"><br />
				<?php
					$pBans = $database->query_fetch("SELECT TOP 25 * FROM ".WEBENGINE_BAN_LOG." WHERE ban_type = ? ORDER BY id DESC", array("permanent"));
					if(is_array($pBans)) {
						echo '<table class="table table-condensed">';
							echo '<thead>';
							echo '<tr>';
								echo '<th>Account</th>';
								echo '<th>Banned By</th>';
								echo '<th>Date</th>';
								echo '<th>Reason</th>';
								echo '<th></th>';
							echo '</tr>';
							echo '</thead>';
							echo '<tbody>';
							foreach($pBans as $permanentBan) {
								echo '<tr>';
									echo '<td>'.$permanentBan['account_id'].'</td>';
									echo '<td>'.$permanentBan['banned_by'].'</td>';
									echo '<td>'.date("Y-m-d H:i", $permanentBan['ban_date']).'</td>';
									echo '<td>'.$permanentBan['ban_reason'].'</td>';
									echo '<td style="text-align:right;"><a href="index.php?module='.$_REQUEST['module'].'&liftban='.$permanentBan['id'].'" class="btn btn-danger btn-xs">Lift Ban</a></td>';
								echo '</tr>';
							}
						echo '</tbody>';
						echo '</table>';
					} else {
						message('warning', 'There are no permanent bans logged.', ' ');
					}
				?>
				</div>
			</div>
		</div>
	</div>
</div>