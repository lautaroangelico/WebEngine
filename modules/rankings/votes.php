<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.4
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2022 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

try {
	
	echo '<div class="page-title"><span><i class="fa-solid fa-trophy"></i> Top Votes</span></div>';
	
	$Rankings = new Rankings();
	$Rankings->rankingsMenu();
	loadModuleConfigs('rankings');
	
	if(!mconfig('rankings_enable_votes')) throw new Exception(lang('error_44',true));
	if(!mconfig('active')) throw new Exception(lang('error_44',true));
	
	$ranking_data = LoadCacheData('rankings_votes.cache');
	if(!is_array($ranking_data)) throw new Exception(lang('error_58',true));
	
	$showPlayerCountry = mconfig('show_country_flags') ? true : false;
	$charactersCountry = loadCache('character_country.cache');
	if(!is_array($charactersCountry)) $showPlayerCountry = false;
	
	if(mconfig('show_online_status')) $onlineCharacters = loadCache('online_characters.cache');
	if(!is_array($onlineCharacters)) $onlineCharacters = array();
	
	if(mconfig('rankings_class_filter')) $Rankings->rankingsFilterMenu();
	
	echo '<table class="table dataTableChar dataTable no-footer general-rank text-center mt-2" style="white-space: nowrap;" id="RankingGeneral" role="grid">';
	echo '<thead class="bg-primary text-white">';
	echo '<tr role="row">';
	if(mconfig('rankings_show_place_number')) {
		echo '<th style="font-weight:bold;"><i class="fas fa-list-ol"></i></th>';
	}
	echo '<td style="font-weight:bold;"><i class="fas fa-user"></i> '.lang('rankings_txt_10',true).'</td>';
	echo '<td style="font-weight:bold;"><i class="fa-solid fa-check-to-slot"></i> '.lang('rankings_txt_32',true).'</td>';
	if(mconfig('show_location')) echo '<td style="font-weight:bold;"><i class="fa-solid fa-location-dot"></i> '.lang('rankings_txt_34').'</td>';
	if($showPlayerCountry) echo '<td style="font-weight:bold;"><i class="fa-solid fa-globe"></i></td>';
	echo '</tr>';
	echo '</thead>';
	$i = 0;
	echo '<tbody>';
	foreach($ranking_data as $rdata) {
		if($i>=1) {
			$onlineStatus = mconfig('show_online_status') ? in_array($rdata[0], $onlineCharacters) ? 'default-char-online' : 'default-char-offline' : '';
		$characterIMG = getPlayerClassAvatar($rdata[2], true, true, 'rankings-class-image rounded-circle '.$onlineStatus.'');
		echo '<tr data-class-id="'.$rdata[2].'" class="align-middle">';
			if(mconfig('rankings_show_place_number')) {
				echo '<td class="rankings-table-place align-middle"><i class="fas fa-medal"></i> '.$i.'</td>';
			}
			echo '<td>';
				echo '<div class="row">';
					echo '<div class="col-md-6 text-end">';
						echo $characterIMG;
					echo '</div>';
					echo '<div class="col-md-6 text-start" style="padding:unset;">';
						echo '<div class="row">';
							echo '<div class="col-md-12">';
								echo playerProfile($rdata[0]);
							echo '</div>';
							echo '<div class="col-md-12">';
								echo '<span class="text-muted" style="font-size:12px;">'.getPlayerClass($rdata[1]).'</span>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
		  		echo '</div>';
			echo '</td>';
			echo '<td>'.number_format($rdata[1],0,",",".").'</td>';
			if(mconfig('show_location')) echo '<td>'.returnMapName($rdata[3]).'</td>';
			if($showPlayerCountry) echo '<td><img src="'.getCountryFlag($charactersCountry[$rdata[0]]).'" /></td>';
			echo '</tr>';
		}
		$i++;
	}
	echo '</tbody>';
	echo '</table>';
	if(mconfig('rankings_show_date')) {
		echo '<div class="alert alert-primary text-end" role="alert">';
		echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
		echo '</div>';
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}