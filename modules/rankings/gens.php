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

try {
	
	echo '<div class="page-title"><span>'.lang('module_titles_txt_10',true).'</span></div>';
	
	$Rankings = new Rankings();
	$Rankings->rankingsMenu();
	$Character = new Character();
	loadModuleConfigs('rankings');
	
	if(!mconfig('rankings_enable_gens')) throw new Exception(lang('error_44',true));
	if(!mconfig('active')) throw new Exception(lang('error_44',true));
	
	$ranking_data = LoadCacheData('rankings_gens.cache');
	if(!is_array($ranking_data)) throw new Exception(lang('error_58',true));
	
	echo '<table class="rankings-table">';
	echo '<tr>';
	if(mconfig('rankings_show_place_number')) { 
		echo '<td style="font-weight:bold;"></td>';
	}
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_11',true).'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_29',true).'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_10',true).'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_30',true).'</td>';
	echo '<td style="font-weight:bold;">'.lang('rankings_txt_31',true).'</td>';
	echo '</tr>';
	$i = 0;
	foreach($ranking_data as $rdata) {
		$characterIMG = $Character->GenerateCharacterClassAvatar($rdata[4]);
		if($rdata[2] == 1) {
			$rdata[2] = '<img class="rankings-gens-img" src="'.__PATH_TEMPLATE_IMG__.'gens_1.png" title="'.lang('rankings_txt_26',true).'" alt="'.lang('rankings_txt_26',true).'"/>';
		} else {
			$rdata[2] = '<img class="rankings-gens-img" src="'.__PATH_TEMPLATE_IMG__.'gens_2.png" title="'.lang('rankings_txt_27',true).'" alt="'.lang('rankings_txt_27',true).'"/>';
		}
		if($i>=1) {
			echo '<tr>';
			if(mconfig('rankings_show_place_number')) {
				echo '<td class="rankings-table-place">'.$i.'</td>';
			}
			echo '<td>'.$characterIMG.'</td>';
			echo '<td>'.$rdata[2].'</td>';
			echo '<td>'.$rdata[0].'</td>';
			echo '<td>'.getGensRank($rdata[3]).'</td>';
			echo '<td>'.$rdata[1].'</td>';
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