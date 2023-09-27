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

function templateCastleSiegeWidget() {
	$castleSiege = new CastleSiege();
	if(!$castleSiege->showWidget()) return;
	$siegeData = $castleSiege->siegeData();
	if(!is_array($siegeData)) return;
	if(!is_array($siegeData['castle_data'])) return;
	
	if($siegeData['castle_data'][_CLMN_MCD_OCCUPY_] == 1) {
		$guildOwner = guildProfile($siegeData['castle_data'][_CLMN_MCD_GUILD_OWNER_]);
		$guildOwnerMark = $siegeData['castle_owner_alliance'][0][_CLMN_GUILD_LOGO_];
		$guildMaster = playerProfile($siegeData['castle_owner_alliance'][0][_CLMN_GUILD_MASTER_]);
	} else {
		$guildOwner = '-';
		$guildOwnerMark = '1111111111111111111111111114411111144111111111111111111111111111';
		$guildMaster = '-';
	}
	
	echo '<div class="panel castle-owner-widget">';
		echo '<div class="panel-heading">';
			echo '<h3 class="panel-title">'.lang('castlesiege_widget_title').'</h3>';
		echo '</div>';
		echo '<div class="panel-body">';
			echo '<div class="row">';
				echo '<div class="col-sm-6 text-center">';
					echo returnGuildLogo($guildOwnerMark, 100);
				echo '</div>';
				echo '<div class="col-sm-6">';
					echo '<span class="alt">'.lang('castlesiege_txt_2').'</span><br />';
					echo $guildOwner . '<br /><br />';
					echo '<span class="alt">'.lang('castlesiege_txt_12').'</span><br />';
					echo $guildMaster;
				echo '</div>';
			echo '</div>';
			echo '<div class="row" style="margin-top: 20px;">';
				echo '<div class="col-sm-12 text-center">';
					echo '<span class="alt">'.lang('castlesiege_txt_21').'</span><br />';
					echo $siegeData['current_stage']['title'] . '<br /><br />';
					echo '<span class="alt">'.lang('castlesiege_txt_1').'</span><br />';
					echo $siegeData['warfare_stage_countdown'] . '<br /><br />';
					echo '<a href="'.__BASE_URL__.'castlesiege" class="btn btn-castlewidget btn-xs">'.lang('castlesiege_txt_7').'</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

function templateLanguageSelector() {
	$langList = array(
		'en' => array('English', 'US'),
		'es' => array('Español', 'ES'),
		'ph' => array('Filipino', 'PH'),
		'br' => array('Português', 'BR'),
		'ro' => array('Romanian', 'RO'),
		'cn' => array('Simplified Chinese', 'CN'),
		'ru' => array('Russian', 'RU'),
		'lt' => array('Lithuanian', 'LT'),
	);
	
	if(isset($_SESSION['language_display'])) {
		$lang = $_SESSION['language_display'];
	} else {
		$lang = config('language_default', true);
	}
	
	echo '<ul class="webengine-language-switcher">';
		echo '<li><a href="'.__BASE_URL__.'language/switch/to/'.strtolower($lang).'" title="'.$langList[$lang][0].'"><img src="'.getCountryFlag($langList[$lang][1]).'" /> '.strtoupper($lang).'</a></li> ';
		foreach($langList as $language => $languageInfo) {
			if($language == $lang) continue;
			echo '<li><a href="'.__BASE_URL__.'language/switch/to/'.strtolower($language).'" title="'.$languageInfo[0].'"><img src="'.getCountryFlag($languageInfo[1]).'" /> '.strtoupper($language).'</a></li> ';
		}
	echo '</ul>';
}