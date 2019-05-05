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

function templateDisplayCSBanner() {
	loadModuleConfigs('castlesiege');
	if(!mconfig('active')) return;
	if(!mconfig('enable_banner')) return;
	
	$ranking_data = loadCache('castle_siege.cache');
	if(is_array($ranking_data)) {
		$logo = returnGuildLogo($ranking_data['castle'][_CLMN_GUILD_LOGO_], 100);
		$guild = guildProfile($ranking_data['castle'][_CLMN_MCD_GUILD_OWNER_]);
		$master = playerProfile($ranking_data['castle'][_CLMN_GUILD_MASTER_]);
	} else {
		$logo = returnGuildLogo('1111111111111111111111111114411111144111111111111111111111111111', 100);
		$guild = '-';
		$master = '-';
	}
	
	$cs = cs_CalculateTimeLeft();
	$timeleft = sec_to_dhms($cs);
	
	echo '<div class="castle-siege-banner">';
		echo '<div class="col-xs-2 text-center vcenter">';
			echo $logo;
		echo '</div>';
		echo '<div class="col-xs-3 text-center vcenter">';
			echo ''.lang('csbanner_txt_1',true).'<br />';
			echo '<span class="guild_owner">'.$guild.'</span>';
		echo '</div>';
		echo '<div class="col-xs-3 text-center vcenter">';
			echo ''.lang('csbanner_txt_3',true).'<br />';
			echo '<span class="guild_master">'.$master.'</span>';
		echo '</div>';
		echo '<div class="col-xs-4 text-center vcenter">';
			echo lang('csbanner_txt_2',true).'<br />';
			echo '<span class="guild_countdown" id="cscountdown">';
				if($cs > 86400) echo $timeleft[0] . '<span>d</span> ';
				if($cs > 3600) echo $timeleft[1] . '<span>h</span> ';
				if($cs > 60) echo $timeleft[2] . '<span>m</span> ';
				echo $timeleft[3] . '<span>s</span> ';
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