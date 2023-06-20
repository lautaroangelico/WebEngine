<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.2
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

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
		$icon = (check_value($element['icon']) ?  $element['icon'] : 'fa-solid fa-xmark');
		
		# visibility
		if($element['visibility'] == 'guest') if(isLoggedIn()) continue;
		if($element['visibility'] == 'user') if(!isLoggedIn()) continue;
		
		# print
		if($element['newtab']) {
			echo '<li><a href="'.$link.'" target="_blank"><i class="'.$icon.' border border-primary p-1 me-1 rounded-3 text-primary"></i> '.$title.'</a></li>';
		} else {
			echo '<li><a href="'.$link.'"><i class="'.$icon.' border border-primary p-1 me-1 rounded-3 text-primary"></i> '.$title.'</a></li>';
		}
	}
	echo '</ul>';
}

function templateCastleSiegeWidget() {
	$castleSiege = new CastleSiege();
	if(!$castleSiege->showWidget()) return;
	$siegeData = $castleSiege->siegeData();
	if(!is_array($siegeData)) return;
	
	if($siegeData['castle_data'][_CLMN_MCD_OCCUPY_] == 1) {
		$guildOwner = guildProfile($siegeData['castle_data'][_CLMN_MCD_GUILD_OWNER_]);
		$guildOwnerMark = $siegeData['castle_owner_alliance'][0][_CLMN_GUILD_LOGO_];
		$guildMaster = playerProfile($siegeData['castle_owner_alliance'][0][_CLMN_GUILD_MASTER_]);
	} else {
		$guildOwner = '-';
		$guildOwnerMark = '1111111111111111111111111114411111144111111111111111111111111111';
		$guildMaster = '-';
	}

	echo '<div class="card-group">';
		echo '<div class="card">';
			echo '<div class="card-header bg-primary text-white">';
				echo '<i class="fa-solid fa-chess-rook"></i> '.lang('castlesiege_widget_title').'';
			echo '</div>';
			echo '<div class="card-body">';
				echo '<center>';
				echo returnGuildLogo($guildOwnerMark, 270);
				echo '</center><br>';
				echo '<div class="d-grid gap-2 castle-siege-buttons">';
					echo '<p class="btn btn-outline-primary fs-3" style="margin-bottom:unset;">'.$guildOwner.'</p>';
					echo '<p class="btn btn-outline-dark fs-6 btn-sm">'.$guildMaster.'</p>';
				echo '</div>';	
				echo '<h5 class="card-text fw-lighter">';
					echo '<div class="row">';
						echo '<div class="col"><hr></div>';
						echo '<div class="col-auto text-primary">'.lang('castlesiege_txt_21').'</div>';
						echo '<div class="col"><hr></div>';
					echo '</div>';
				echo '</h5>';
				echo '<p class="card-text text-center">'.$siegeData['current_stage']['title'].'</p>';
				echo '<h5 class="card-text fw-lighter">';
					echo '<div class="row">';
						echo '<div class="col"><hr></div>';
						echo '<div class="col-auto text-primary">'.lang('castlesiege_txt_1').'</div>';
						echo '<div class="col"><hr></div>';
					echo '</div>';
				echo '</h5>';
				echo '<p class="card-text text-center">'.$siegeData['warfare_stage_countdown'].'</p>';
			echo '</div>';
			echo '<div class="card-footer text-center">';
				echo '<small class="text-muted"><a href="'.__BASE_URL__.'castlesiege" class="btn btn-primary btn-sm">'.lang('castlesiege_txt_7').'</a></small>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	echo '<hr>';
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
			echo '<li><a href="'.$link.'" target="_blank">'.$element['icon'].'<br>'.$title.'</a></li>';
		} else {
			echo '<li><a href="'.$link.'">'.$element['icon'].'<br>'.$title.'</a></li>';
		}
	}
	echo '</ul>';
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
		echo '<li><a href="'.__BASE_URL__.'language/switch/to/'.strtolower($lang).'" title="'.$langList[$lang][0].'"><img src="'.getCountryFlag($langList[$lang][1]).'" /> '.strtoupper($lang).'</a></li>&nbsp;';
		foreach($langList as $language => $languageInfo) {
			if($language == $lang) continue;
			echo '<li><a href="'.__BASE_URL__.'language/switch/to/'.strtolower($language).'" title="'.$languageInfo[0].'"><img src="'.getCountryFlag($languageInfo[1]).'" /> '.strtoupper($language).'</a></li>&nbsp;';
		}
	echo '</ul>';
}

function CheckGS($ip, $port) {
	if(!@fsockopen($ip,$port,$err,$err_str,0.1)) {
		return false;
	} else {
		return true;
	}
}