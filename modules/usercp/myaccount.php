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

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_4').'</span></div>';

// module status
if(!mconfig('active')) throw new Exception(lang('error_47'));
	
// common class
$common = new common();

// Retrieve Account Information
$accountInfo = $common->accountInformation($_SESSION['userid']);
if(!is_array($accountInfo)) throw new Exception(lang('error_12'));

# account online status
$onlineStatus = ($common->accountOnline($_SESSION['memb___id']) ? '<span class="label label-success">'.lang('myaccount_txt_9').'</span>' : '<span class="label label-danger">'.lang('myaccount_txt_10').'</span>');

# account status
$accountStatus = ($accountInfo['bloc_code'] == 1 ? '<span class="label label-danger">'.lang('myaccount_txt_8').'</span>' : '<span class="label label-default">'.lang('myaccount_txt_7').'</span>');

# characters info
$Character = new Character();
$AccountCharacters = $Character->AccountCharacter($_SESSION['memb___id']);

// Account Information
echo '<table class="table myaccount-table">';
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_1').'</td>';
		echo '<td>'.$accountStatus.'</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_2').'</td>';
		echo '<td>'.$accountInfo['memb___id'].'</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_3').'</td>';
		echo '<td>'.$accountInfo['mail_addr'].' <a href="'.__BASE_URL__.'usercp/myemail/" class="btn btn-xs btn-primary pull-right">'.lang('myaccount_txt_6').'</a></td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_4').'</td>';
		echo '<td>&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226; <a href="'.__BASE_URL__.'usercp/mypassword/" class="btn btn-xs btn-primary pull-right">'.lang('myaccount_txt_6').'</a></td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_5').'</td>';
		echo '<td>'.$onlineStatus.'</td>';
	echo '</tr>';
	
	try {
		$creditSystem = new CreditSystem();
		$creditCofigList = $creditSystem->showConfigs();
		if(is_array($creditCofigList)) {
			foreach($creditCofigList as $myCredits) {
				if(!$myCredits['config_display']) continue;
				
				$creditSystem->setConfigId($myCredits['config_id']);
				switch($myCredits['config_user_col_id']) {
					case 'userid':
						$creditSystem->setIdentifier($accountInfo["ID"]);
						break;
					case 'username':
						$creditSystem->setIdentifier($accountInfo["Username"]);
						break;
					case 'email':
						$creditSystem->setIdentifier($accountInfo["Email"]);
						break;
					default:
						continue 2;
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

// Account Characters
echo '<div class="page-title"><span>'.lang('myaccount_txt_15').'</span></div>';
if(is_array($AccountCharacters)) {
	$onlineCharacters = loadCache('online_characters.cache') ? loadCache('online_characters.cache') : array();
	echo '<div class="row text-center">';
		foreach($AccountCharacters as $characterName) {
			$characterData = $Character->CharacterData($characterName);
			if(!is_array($characterData)) continue;
			
			if(defined('"MasterSkillTree"')) {
				if('MasterSkillTree' != 'Character') {
					$characterMLData = $Character->getMasterLevelInfo($characterName);
					if(is_array($characterMLData)) {
						$characterData["cLevel"] += $characterMLData["MasterLevel"];
					}
				} else {
					$characterData["cLevel"] += $characterData["MasterLevel"];
				}
			}
			
			$characterClassAvatar = getPlayerClassAvatar($characterData["Class"], false);
			$characterOnlineStatus = in_array($characterName, $onlineCharacters) ? '<img src="'.__PATH_ONLINE_STATUS__.'" class="online-status-indicator"/>' : '<img src="'.__PATH_OFFLINE_STATUS__.'" class="online-status-indicator"/>';
			echo '<div class="col-xs-3">';
				echo '<div class="myaccount-character-name">'.playerProfile($characterName).$characterOnlineStatus.'</div>';
				echo '<div class="myaccount-character-block">';
					echo '<a href="'.playerProfile($characterName, true).'" target="_blank">';
						echo '<img src="'.$characterClassAvatar.'" />';
					echo '</a>';
				echo '</div>';
				echo '<div class="myaccount-character-block-location">'.returnMapName($characterData["MapNumber"]).'<br />'.$characterData["MapPosX"].', '.$characterData["MapPosY"].'</div>';
				echo '<span class="myaccount-character-block-level">'.$characterData['cLevel'].'</span>';
			echo '</div>';
		}
	echo '</div>';
} else {
	message('warning', lang('error_46'));
}

// Connection History (IGCN)
/*
if(defined('_TBL_CH_')) {
	echo '<div class="page-title"><span>'.lang('myaccount_txt_16').'</span></div>';
	$me = Connection::Database('Me_MuOnline');
	$connectionHistory = $me->query_fetch("SELECT TOP 10 * FROM "._TBL_CH_." WHERE "._CLMN_CH_ACCID_." = ? ORDER BY "._CLMN_CH_ID_." DESC", array($_SESSION['username']));
	if(is_array($connectionHistory)) {
		echo '<table class="table table-condensed general-table-ui">';
			echo '<tr>';
				echo '<td>'.lang('myaccount_txt_13').'</td>';
				echo '<td>'.lang('myaccount_txt_17').'</td>';
				echo '<td>'.lang('myaccount_txt_18').'</td>';
				echo '<td>'.lang('myaccount_txt_19').'</td>';
			echo '</tr>';
			foreach($connectionHistory as $row) {
				echo '<tr>';
					echo '<td>'.$row[_CLMN_CH_DATE_].'</td>';
					echo '<td>'.$row[_CLMN_CH_SRVNM_].'</td>';
					echo '<td>'.$row[_CLMN_CH_IP_].'</td>';
					echo '<td>'.$row[_CLMN_CH_STATE_].'</td>';
				echo '</tr>';
			}
		echo '</table>';
	}
}
*/