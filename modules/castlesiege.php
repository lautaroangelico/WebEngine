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

echo '<div class="page-title"><span>'.lang('module_titles_txt_29',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$castleData = LoadCacheData('castle_siege.cache');
	
	echo '<div class="csinfo_container">';
		echo '<div class="csinfo_content">';
			echo '<div class="csinfo_glogo">'.returnGuildLogo($castleData[1][1], 180).'</div>';
			echo '<div class="csinfo_ginfo">';
				echo '<table>';
					echo '<tr><td>'.lang('castlesiege_txt_2',true).'</td><td><a href="'.__BASE_URL__.'profile/guild/req/'.$castleData[1][0].'" target="_blank">'.$castleData[1][0].'</a></td></tr>';
					echo '<tr><td>'.lang('castlesiege_txt_3',true).'</td><td>'.number_format(round($castleData[1][2])).'</td></tr>';
					echo '<tr><td>'.lang('castlesiege_txt_4',true).'</td><td>'.$castleData[1][3].'</td></tr>';
					echo '<tr><td>'.lang('castlesiege_txt_5',true).'</td><td>'.$castleData[1][4].'</td></tr>';
					echo '<tr><td>'.lang('castlesiege_txt_6',true).'</td><td>'.$castleData[1][5].'</td></tr>';
				echo '</table>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	if(is_array($castleData[2])) {
		echo '<div class="page-title"><span>'.lang('castlesiege_txt_7',true).'</span></div>';
		echo '<ul class="csinfo_glist">';
			foreach($castleData[2] as $guild) {
				echo '<li><a href="'.__BASE_URL__.'profile/guild/?req='.$guild.'" target="_blank">'.$guild.'</a></li>';
			}
		echo '</ul>';
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}