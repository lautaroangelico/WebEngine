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

function templateDisplayCSBanner() {
	loadModuleConfigs('castlesiege');
	if(!mconfig('active')) return;
	if(!mconfig('enable_banner')) return;
	
	$ranking_data = LoadCacheData('castle_siege.cache');
	if(!is_array($ranking_data)) return;
	
	$Rankings = new Rankings();
	
	$cs = cs_CalculateTimeLeft();
	if(!check_value($cs)) return;
	$timeleft = sec_to_hms($cs);
	
	/*
	echo '<div id="castle-siege">';
		echo '<table cellspacing="0" cellpadding="0">';
			echo '<tr>';
				echo '<td class="cs-logo">'.returnGuildLogo($ranking_data[1][1], 112).'</td>';
				echo '<td class="cs-guild-info">';
					echo '<span class="cs-guild-title">'.$ranking_data[1][0].'</span><br />';
					echo '<span>'.lang('csbanner_txt_1',true).'</span>';
				echo '</td>';
				echo '<td>';
					
				echo '</td>';
			echo '</tr>';
		echo '</table>';
	echo '</div>';
	*/
	
	echo '<div class="castle-siege-banner">';
		echo '<div class="col-xs-4">';
			echo '<div class="col-xs-6 text-center">';
				echo returnGuildLogo($ranking_data[1][1], 112);
			echo '</div>';
			echo '<div class="col-xs-6">';
				echo '<span class="cs-guild-title">'.$ranking_data[1][0].'</span><br />';
				echo '<span class="cs-guild-guildowner">'.lang('csbanner_txt_1',true).'</span>';
			echo '</div>';
		echo '</div>';
		echo '<div class="col-xs-8 text-center">';
			echo lang('csbanner_txt_2',true).'<br />';
			echo '<span class="cs-timeleft" id="cscountdown">';
				echo $timeleft[0] . '<span>h</span> ';
				echo $timeleft[1] . '<span>m</span> ';
				echo $timeleft[2] . '<span>s</span> ';
			echo '</span>';
		echo '</div>';
	echo '</div>';
}

function templateBuildNavbar() {
	$cfg = loadConfig('navbar');
	if(!is_array($cfg)) return;
	
	echo '<ul>';
	foreach($cfg as $element) {
		if(!is_array($element)) continue;
		
		# active
		if(!$element['active']) continue;
		
		# type
		$link = ($element['type'] == 'internal' ? __BASE_URL__ . $element['link'] : $element['link']);
		
		# title
		$title = (check_value(lang($element['phrase'], true)) ? lang($element['phrase'], true) : 'Unk_phrase');
		
		# visibility
		if($element['visibility'] == 'guest') if(isLoggedIn()) continue;
		if($element['visibility'] == 'user') if(!isLoggedIn()) continue;
		
		# print
		if($element['newtab']) {
			echo '<li><a href="'.$link.'" target="_blank">'.$title.'</a></li>';
		} else {
			echo '<li><a href="'.$link.'">'.$title.'</a></li>';
		}
	}
	echo '</ul>';
}

function templateBuildUsercp() {
	$cfg = loadConfig('usercp');
	if(!is_array($cfg)) return;
	
	echo '<ul>';
	foreach($cfg as $element) {
		if(!is_array($element)) continue;
		
		# active
		if(!$element['active']) continue;
		
		# type
		$link = ($element['type'] == 'internal' ? __BASE_URL__ . $element['link'] : $element['link']);
		
		# title
		$title = (check_value(lang($element['phrase'], true)) ? lang($element['phrase'], true) : 'Unk_phrase');
		
		# icon
		$icon = (check_value($element['icon']) ? __PATH_TEMPLATE_IMG__ . 'icons/' . $element['icon'] : __PATH_TEMPLATE_IMG__ . 'icons/usercp_default.png');
		
		# visibility
		if($element['visibility'] == 'guest') if(isLoggedIn()) continue;
		if($element['visibility'] == 'user') if(!isLoggedIn()) continue;
		
		# print
		if($element['newtab']) {
			echo '<li><img src="'.$icon.'"><a href="'.$link.'" target="_blank">'.$title.'</a></li>';
		} else {
			echo '<li><img src="'.$icon.'"><a href="'.$link.'">'.$title.'</a></li>';
		}
	}
	echo '</ul>';
}