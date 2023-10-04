<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.5
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2023 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

try {
	
	echo '<div class="page-title"><span>'.lang('module_titles_txt_10',true).'</span></div>';
	
	$Rankings = new Rankings();
	$Rankings->rankingsMenu();
	loadModuleConfigs('rankings');
	
	if(!mconfig('rankings_enable_guilds')) throw new Exception(lang('error_44',true));
	if(!mconfig('active')) throw new Exception(lang('error_44',true));
	
	$ranking_data = LoadCacheData('rankings_guilds.cache');
	if(!is_array($ranking_data)) throw new Exception(lang('error_58',true));
	
	if(mconfig('show_online_status')) $onlineCharacters = loadCache('online_characters.cache');
	if(!is_array($onlineCharacters)) $onlineCharacters = array();
	
	echo '<table class="rankings-table">';
	echo '<tr>';
	if(mconfig('rankings_show_place_number')) {
		echo '<td style="font-weight:bold;"></td>';
	}
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_17',true).'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_28',true).'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_18',true).'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_19',true).'</td>';
	echo '</tr>';
	$i = 0;
	foreach($ranking_data as $rdata) {
		if($i>=1) {
			$onlineStatus = mconfig('show_online_status') ? in_array($rdata[1], $onlineCharacters) ? '<img src="'.__PATH_ONLINE_STATUS__.'" class="online-status-indicator"/>' : '<img src="'.__PATH_OFFLINE_STATUS__.'" class="online-status-indicator"/>' : '';
			$multiplier = mconfig('guild_score_formula') == 1 ? 1 : mconfig('guild_score_multiplier');
			echo '<tr>';
			if(mconfig('rankings_show_place_number')) {
				echo '<td class="rankings-table-place">'.$i.'</td>';
			}
			echo '<td>'.guildProfile($rdata[0]).'</td>';
			echo '<td>'.returnGuildLogo($rdata[3], 40).'</td>';
			echo '<td>'.playerProfile($rdata[1]).$onlineStatus.'</td>';
			echo '<td>'.number_format(floor($rdata[2]*$multiplier)).'</td>';
			echo '</tr>';
		}
		$i++;
	}
	echo '</table>';
	if(mconfig('rankings_show_date')) {
		echo '<div class="rankings-update-time">';
		echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
		echo '</div>';
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}