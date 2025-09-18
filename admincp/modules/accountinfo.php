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

$accountInfoConfig['showGeneralInfo'] = true;
$accountInfoConfig['showStatusInfo'] = true;
$accountInfoConfig['showIpInfo'] = true;
$accountInfoConfig['showCharacters'] = true;

if(isset($_GET['u'])) {
	try {
		$Account = new Account();
		$userId = $Account->retrieveUserID($_GET['u']);
		if(check_value($userId)) {
			redirect(3, admincp_base('accountinfo&id='.$userId));
		}
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

if(isset($_GET['id'])) {
	try {
		if(isset($_POST['editaccount_submit'])) {
			try {
				if(!isset($_POST['action'])) throw new Exception("Invalid request.");
				$sendEmail = (isset($_POST['editaccount_sendmail']) && $_POST['editaccount_sendmail'] == 1 ? true : false);
				$accountInfo = $common->accountInformation($_GET['id']);
				if(!$accountInfo) throw new Exception("Could not retrieve account information (invalid account).");
				switch($_POST['action']) {
					case "changepassword":
						if(!isset($_POST['changepassword_newpw'])) throw new Exception("Please enter the new password.");
						if(!Validator::PasswordLength($_POST['changepassword_newpw'])) throw new Exception("Invalid password.");
						if(!$common->changePassword($accountInfo['memb_guid'], $accountInfo['memb___id'], $_POST['changepassword_newpw'])) throw new Exception("Could not change password.");
						message('success', 'Password updated!');
						
						# send new password
						if(isset($_POST['editaccount_sendmail'])) {
							$email = new Email();
							$email->setTemplate('ADMIN_CHANGE_PASSWORD');
							$email->addVariable('{USERNAME}', $accountInfo['memb___id']);
							$email->addVariable('{NEW_PASSWORD}', $_POST['changepassword_newpw']);
							$email->addAddress($accountInfo['mail_addr']);
							$email->send();
						}
						break;
					case "changeemail":
						if(!isset($_POST['changeemail_newemail'])) throw new Exception("Please enter the new email.");
						if(!Validator::Email($_POST['changeemail_newemail'])) throw new Exception("Invalid email address.");
						if($common->emailExists($_POST['changeemail_newemail'])) throw new Exception("Another account with the same email already exists.");
						if(!$common->updateEmail($accountInfo['memb_guid'], $_POST['changeemail_newemail'])) throw new Exception("Could not update email.");
						message('success', 'Email address updated!');
						
						# send new email to current email
						if(isset($_POST['editaccount_sendmail'])) {
							$email = new Email();
							$email->setTemplate('ADMIN_CHANGE_EMAIL');
							$email->addVariable('{USERNAME}', $accountInfo['memb___id']);
							$email->addVariable('{NEW_EMAIL}', $_POST['changeemail_newemail']);
							$email->addAddress($accountInfo['mail_addr']);
							$email->send();
						}
						break;
					default:
						throw new Exception("Invalid request.");
				}
			} catch(Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
	
		$accountInfo = $common->accountInformation($_GET['id']);
		if(!$accountInfo) throw new Exception("Could not retrieve account information (invalid account).");
		
		echo '<h1 class="page-header">Account Information: <small>'.$accountInfo['memb___id'].'</small></h1>';
		
		echo '<div class="row">';
			echo '<div class="col-md-6">';
			
				if($accountInfoConfig['showGeneralInfo']) {
					// GENERAL ACCOUNT INFORMATION
					echo '<div class="panel panel-primary">';
					echo '<div class="panel-heading">General Information</div>';
					echo '<div class="panel-body">';
					
						$isBanned = ($accountInfo['bloc_code'] == 0 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Banned</span>');
						echo '<table class="table table-no-border table-hover">';
							echo '<tr>';
								echo '<th>ID:</th>';
								echo '<td>'.$accountInfo['memb_guid'].'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>Username:</th>';
								echo '<td>'.$accountInfo['memb___id'].'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<th>Email:</th>';
								echo '<td>'.$accountInfo['mail_addr'].'</td>';
							echo '</tr>';
							
							if(strtolower(config('server_files',true)) == 'mue') {
								echo '<tr>';
									echo '<th>Credits:</th>';
									echo '<td>'.$accountInfo['credits'].'</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<th>TempCredits:</th>';
									echo '<td>'.$accountInfo['credits_temp'].'</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<th>Master Key:</th>';
									echo '<td>'.$accountInfo['master_key'].'</td>';
								echo '</tr>';
							}
							
							echo '<tr>';
								echo '<th>Banned:</th>';
								echo '<td>'.$isBanned.'</td>';
							echo '</tr>';
						echo '</table>';
					echo '</div>';
					echo '</div>';
				}
				
				if($accountInfoConfig['showStatusInfo']) {
					// ACCOUNT STATUS
					$statusdb = (config('SQL_USE_2_DB', true) == true ? $dB2 : $dB);
					$statusData = $statusdb->query_fetch_single("SELECT * FROM MEMB_STAT WHERE memb___id = ?", array($accountInfo['memb___id']));
					echo '<div class="panel panel-info">';
					echo '<div class="panel-heading">Status Information</div>';
					echo '<div class="panel-body">';
						if(is_array($statusData)) {
							$onlineStatus = ($statusData['ConnectStat'] == 1 ? '<span class="label label-success">Online</span>' : '<span class="label label-danger">Offline</span>');
							echo '<table class="table table-no-border table-hover">';
								echo '<tr>';
									echo '<td>Status:</td>';
									echo '<td>'.$onlineStatus.'</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<td>Server:</td>';
									echo '<td>'.$statusData['ServerName'].'</td>';
								echo '</tr>';
							echo '</table>';
						} else {
							message('warning', 'No data found in <strong>'.'MEMB_STAT'.'</strong> for this account.', ' ');
						}
					echo '</div>';
					echo '</div>';
				}
				
				if($accountInfoConfig['showCharacters']) {
					// ACCOUNT CHARACTERS
					$Character = new Character();
					$accountCharacters = $Character->AccountCharacter($accountInfo['memb___id']);
					echo '<div class="panel panel-default">';
					echo '<div class="panel-heading">Characters</div>';
					echo '<div class="panel-body">';
						if(is_array($accountCharacters)) {
							echo '<table class="table table-no-border table-hover">';
								foreach($accountCharacters as $characterName) {
									echo '<tr>';
										echo '<td><a href="'.admincp_base("editcharacter&name=".$characterName).'">'.$characterName.'</a></td>';
									echo '</tr>';
								}
							echo '</table>';
						} else {
							message('warning', 'No characters found.', ' ');
						}
					echo '</div>';
					echo '</div>';
				}
				
				// CHANGE PASSWORD
				echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">Change Account\'s Password</div>';
				echo '<div class="panel-body">';
					echo '<form role="form" method="post">';
					echo '<input type="hidden" name="action" value="changepassword"/>';
						echo '<div class="form-group">';
							echo '<label for="input_1">New Password:</label>';
							echo '<input type="text" class="form-control" id="input_1" name="changepassword_newpw" placeholder="New password">';
						echo '</div>';
						echo '<div class="checkbox">';
							echo '<label><input type="checkbox" name="editaccount_sendmail" value="1" checked> Send email to user about this change.</label>';
						echo '</div>';
						echo '<button type="submit" name="editaccount_submit" class="btn btn-success" value="ok">Change Password</button>';
					echo '</form>';
				echo '</div>';
				echo '</div>';
				
				// CHANGE EMAIL
				echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">Change Account\'s Email</div>';
				echo '<div class="panel-body">';
					echo '<form role="form" method="post">';
					echo '<input type="hidden" name="action" value="changeemail"/>';
						echo '<div class="form-group">';
							echo '<label for="input_2">New Email:</label>';
							echo '<input type="email" class="form-control" id="input_2" name="changeemail_newemail" placeholder="New email address">';
						echo '</div>';
						echo '<div class="checkbox">';
							echo '<label><input type="checkbox" name="editaccount_sendmail" value="1" checked> Send email to user about this change.</label>';
						echo '</div>';
						echo '<button type="submit" name="editaccount_submit" class="btn btn-success" value="ok">Change Email</button>';
					echo '</form>';
				echo '</div>';
				echo '</div>';
				
			echo '</div>';
			echo '<div class="col-md-6">';
				
				if($accountInfoConfig['showIpInfo']) {
					
					if(defined('_TBL_LOGEX_')) {
						// ACCOUNTS IP ADDRESS (MuEngine - MuLogEx tbl)
						$checkMuLogEx = $dB2->query_fetch_single("SELECT * FROM sysobjects WHERE xtype = 'U' AND name = ?", array('LOGEX'));
						echo '<div class="panel panel-default">';
						echo '<div class="panel-heading">Account\'s IP Address (MuEngine)</div>';
						echo '<div class="panel-body">';
							if($checkMuLogEx) {
								$accountIpAddress = $common->retrieveAccountIPs($accountInfo['memb___id']);
								if(is_array($accountIpAddress)) {
									echo '<table class="table table-no-border table-hover">';
										foreach($accountIpAddress as $accountIp) {
											echo '<tr>';
												echo '<td><a href="http://whatismyipaddress.com/ip/'.urlencode($accountIp['IP']).'" target="_blank">'.$accountIp['IP'].'</a></td>';
											echo '</tr>';
										}
									echo '</table>';
								} else {
									message('warning', 'No IP address found.');
								}
							} else {
								message('warning', 'Could not find table <strong>LOGEX</strong> in the database.');
							}
						echo '</div>';
						echo '</div>';
					}
					
					if(defined('_TBL_CH_')) {
						$accountDB = config('SQL_USE_2_DB', true) == true ? $dB2 : $dB;
						
						// ACCOUNT IP LIST
						echo '<div class="panel panel-default">';
						echo '<div class="panel-heading">Account\'s IP Address</div>';
						echo '<div class="panel-body">';
							
							$accountIpHistory = $accountDB->query_fetch("SELECT DISTINCT(IP) FROM Character WHERE AccountID = ?", array($accountInfo['memb___id']));
							if(is_array($accountIpHistory)) {
								echo '<table class="table table-no-border table-hover">';
									foreach($accountIpHistory as $accountIp) {
										echo '<tr>';
											echo '<td><a href="http://whatismyipaddress.com/ip/'.urlencode($accountIp['IP']).'" target="_blank">'.$accountIp['IP'].'</a></td>';
										echo '</tr>';
									}
								echo '</table>';
							} else {
								message('warning', 'No IP addresses found in the database.');
							}
							
						echo '</div>';
						echo '</div>';
						
						// ACCOUNT CONNECTION HISTORY
						echo '<div class="panel panel-default">';
						echo '<div class="panel-heading">Account Connection History (last 25)</div>';
						echo '<div class="panel-body">';
							
							$accountConHistory = $accountDB->query_fetch("SELECT TOP 25 * FROM Character WHERE AccountID = ? AND State = ? ORDER BY ID DESC", array($accountInfo['memb___id'], 'Connect'));
							if(is_array($accountConHistory)) {
								echo '<table class="table table-no-border table-hover">';
									echo '<tr>';
										echo '<th>Date</th>';
										echo '<th class="hidden-xs">Server</th>';
										echo '<th>IP</th>';
										echo '<th>HWID</th>';
									echo '</tr>';
									foreach($accountConHistory as $connection) {
										echo '<tr>';
											echo '<td>'.$connection['Date'].'</td>';
											echo '<td class="hidden-xs">'.$connection['ServerName'].'</td>';
											echo '<td>'.$connection['IP'].'</td>';
											echo '<td>'.$connection['HWID'].'</td>';
										echo '</tr>';
									}
								echo '</table>';
							} else {
								message('warning', 'No connection history found for account.');
							}
							
						echo '</div>';
						echo '</div>';
					}
					
				}
				
			echo '</div>';
		echo '</div>';
		
	} catch(Exception $ex) {
		echo '<h1 class="page-header">Account Information</h1>';
		message('error', $ex->getMessage());
	}
	
} else {
	echo '<h1 class="page-header">Account Information</h1>';
	message('error', 'Please provide a valid user id.');
}