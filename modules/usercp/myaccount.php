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

(!isLoggedIn()) ? redirect(1,'login') : null;

echo '<div class="page-title"><span>'.lang('module_titles_txt_4',true).'</span></div>';

if(mconfig('active')) {

	// Retrieve Account Information
	$accountInfo = $common->accountInformation($_SESSION['userid']);

	if($accountInfo) {

		# account online status
		$onlineStatus = ($common->accountOnline($_SESSION['username']) ? '<span class="label label-success">'.lang('myaccount_txt_9',true).'</span>' : '<span class="label label-danger">'.lang('myaccount_txt_10',true).'</span>');

		# account status
		$accountStatus = ($accountInfo[_CLMN_BLOCCODE_] == 1 ? '<span class="label label-danger">'.lang('myaccount_txt_8',true).'</span>' : '<span class="label label-default">'.lang('myaccount_txt_7',true).'</span>');

		# characters info
		$Character = new Character();
		$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);
		
		echo '<table class="table myaccount-table">';
			echo '<tr>';
				echo '<td>'.lang('myaccount_txt_1',true).'</td>';
				echo '<td>'.$accountStatus.'</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>'.lang('myaccount_txt_2',true).'</td>';
				echo '<td>'.$accountInfo[_CLMN_USERNM_].'</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>'.lang('myaccount_txt_3',true).'</td>';
				echo '<td>'.$accountInfo[_CLMN_EMAIL_].' <a href="'.__BASE_URL__.'usercp/myemail/" class="btn btn-xs btn-primary">'.lang('myaccount_txt_6',true).'</a></td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>'.lang('myaccount_txt_4',true).'</td>';
				echo '<td>&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226; <a href="'.__BASE_URL__.'usercp/mypassword/" class="btn btn-xs btn-primary">'.lang('myaccount_txt_6',true).'</a></td>';
			echo '</tr>';
			
			if(config('server_files', true) == 'MUE') {
				// MASTER KEY
				echo '<tr>';
					echo '<td>'.lang('myaccount_txt_13',true).'</td>';
					echo '<td>&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226; <a href="'.__BASE_URL__.'usercp/mymasterkey/" class="btn btn-xs btn-primary">'.lang('myaccount_txt_14',true).'</a></td>';
				echo '</tr>';
			}
			
			echo '<tr>';
				echo '<td>'.lang('myaccount_txt_5',true).'</td>';
				echo '<td>'.$onlineStatus.'</td>';
			echo '</tr>';

			echo '<tr valign="top">';
				echo '<td>'.lang('myaccount_txt_15',true).'</td>';
				echo '<td>';
					if(is_array($AccountCharacters)) {
						foreach($AccountCharacters as $char) {
							echo '<a href="'.__BASE_URL__.'profile/player/req/'.$char.'/" target="_blank">'.$char.'</a><br />';
						}
					} else {
						lang('myaccount_txt_16', false);
					}
				echo '</td>';
			echo '</tr>';
			
			try {
				$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);
				$creditCofigList = $creditSystem->showConfigs();
				if(is_array($creditCofigList)) {
					foreach($creditCofigList as $myCredits) {
						if(!$myCredits['config_display']) continue;
						
						$creditSystem->setConfigId($myCredits['config_id']);
						switch($myCredits['config_user_col_id']) {
							case 'userid':
								$creditSystem->setIdentifier($accountInfo[_CLMN_MEMBID_]);
								break;
							case 'username':
								$creditSystem->setIdentifier($accountInfo[_CLMN_USERNM_]);
								break;
							case 'email':
								$creditSystem->setIdentifier($accountInfo[_CLMN_EMAIL_]);
								break;
							default:
								continue;
						}
						
						$configCredits = $creditSystem->getCredits();
						
						echo '<tr>';
							echo '<td>'.$myCredits['config_title'].'</td>';
							echo '<td>'.number_format($configCredits).'</td>';
						echo '</tr>';
					}
				}
			} catch(Exception $ex) {}
		echo '</table>';

		
		
		
	} else {
		message('error', lang('error_12',true));
	}
} else {
	message('error', lang('error_47',true));
}