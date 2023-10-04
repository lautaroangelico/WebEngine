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
	
	if(!mconfig('rankings_enable_gens')) throw new Exception(lang('error_44',true));
	if(!mconfig('active')) throw new Exception(lang('error_44',true));
	
	$ranking_data = LoadCacheData('rankings_gens.cache');
	if(!is_array($ranking_data)) throw new Exception(lang('error_58',true));
	
	$showPlayerCountry = mconfig('show_country_flags') ? true : false;
	$charactersCountry = loadCache('character_country.cache');
	if(!is_array($charactersCountry)) $showPlayerCountry = false;
	
	if(mconfig('show_online_status')) $onlineCharacters = loadCache('online_characters.cache');
	if(!is_array($onlineCharacters)) $onlineCharacters = array();
	
	if(mconfig('rankings_class_filter')) $Rankings->rankingsFilterMenu();
	
	echo '<table class="rankings-table">';
	echo '<tr>';
	if(mconfig('rankings_show_place_number')) { 
		echo '<td style="font-weight:bold;"></td>';
	}
	if($showPlayerCountry) echo '<td style="font-weight:bold;">'.lang('rankings_txt_33').'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_11').'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_29').'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_10').'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_30').'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_31').'</td>';
	if(mconfig('show_location')) echo '<td style="font-weight:bold;">'.lang('rankings_txt_34').'</td>';
	echo '</tr>';
	$i = 0;
	foreach($ranking_data as $rdata) {
		if($i>=1) {
			$characterClass = getPlayerClassAvatar($rdata[5], true, true, 'rankings-class-image');
			$gensType = $rdata[1] == 1 ? '<img class="rankings-gens-img" src="'.__PATH_TEMPLATE_IMG__.'gens_1.png" title="'.lang('rankings_txt_26',true).'" alt="'.lang('rankings_txt_26',true).'"/>' : '<img class="rankings-gens-img" src="'.__PATH_TEMPLATE_IMG__.'gens_2.png" title="'.lang('rankings_txt_27',true).'" alt="'.lang('rankings_txt_27',true).'"/>';
			$onlineStatus = mconfig('show_online_status') ? in_array($rdata[0], $onlineCharacters) ? '<img src="'.__PATH_ONLINE_STATUS__.'" class="online-status-indicator"/>' : '<img src="'.__PATH_OFFLINE_STATUS__.'" class="online-status-indicator"/>' : '';
			echo '<tr data-class-id="'.$rdata[5].'>';
			if(mconfig('rankings_show_place_number')) {
				echo '<td class="rankings-table-place">'.$i.'</td>';
			}
			if($showPlayerCountry) echo '<td><img src="'.getCountryFlag(array_key_exists($rdata[0], $charactersCountry) ? $charactersCountry[$rdata[0]] : 'default').'" /></td>';
			echo '<td>'.$characterClass.'</td>';
			echo '<td>'.$gensType.'</td>';
			echo '<td>'.playerProfile($rdata[0]).$onlineStatus.'</td>';
			echo '<td>'.$rdata[3].'</td>';
			echo '<td>'.number_format($rdata[2]).'</td>';
			if(mconfig('show_location')) echo '<td>'.returnMapName($rdata[5]).'</td>';
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