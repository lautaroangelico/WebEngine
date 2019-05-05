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

echo '<div class="page-title"><span>'.lang('module_titles_txt_29',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$castleData = loadCache('castle_siege.cache');
	
	echo '<div class="csinfo_container">';
		echo '<div class="csinfo_content">';
			echo '<div class="csinfo_glogo">'.returnGuildLogo($castleData['castle'][_CLMN_GUILD_LOGO_], 180).'</div>';
			echo '<div class="csinfo_ginfo">';
				echo '<table>';
					echo '<tr><td>'.lang('castlesiege_txt_2',true).'</td><td>'.guildProfile($castleData['castle'][_CLMN_MCD_GUILD_OWNER_]).'</td></tr>';
					echo '<tr><td>'.lang('castlesiege_txt_3',true).'</td><td>'.number_format(round($castleData['castle'][_CLMN_MCD_MONEY_])).'</td></tr>';
					echo '<tr><td>'.lang('castlesiege_txt_4',true).'</td><td>'.$castleData['castle'][_CLMN_MCD_TRC_].'</td></tr>';
					echo '<tr><td>'.lang('castlesiege_txt_5',true).'</td><td>'.$castleData['castle'][_CLMN_MCD_TRS_].'</td></tr>';
					echo '<tr><td>'.lang('castlesiege_txt_6',true).'</td><td>'.$castleData['castle'][_CLMN_MCD_THZ_].'</td></tr>';
				echo '</table>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	
	if(is_array($castleData['guilds'])) {
		echo '<div class="page-title"><span>'.lang('castlesiege_txt_7',true).'</span></div>';
		echo '<ul class="csinfo_glist">';
			foreach($castleData['guilds'] as $guild) {
				echo '<li>'.guildProfile($guild).'</li>';
			}
		echo '</ul>';
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}