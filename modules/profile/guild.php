<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

echo '<div class="page-title"><span>'.lang('profiles_txt_1',true).'</span></div>';

loadModuleConfigs('profiles');
if(mconfig('active')) {
	if(isset($_GET['req'])) {
		try {
			$weProfiles = new weProfiles();
			$weProfiles->setType("guild");
			$weProfiles->setRequest($_GET['req']);
			$guildData = $weProfiles->data();
			
			$guildMembers = explode(",", $guildData[5]);
			$displayData = array(
				'gname' => $guildData[1],
				'glogo' => returnGuildLogo($guildData[2],150),
				'gmaster' => $guildData[4],
				'gscore' => $guildData[3],
				'gmembers' => count($guildMembers),
			);
			
			echo '<div class="profiles_guild_card">';
				// Guild Information
				echo '<div class="row">';
					echo '<div class="col-6 text-center">';
						echo '<span class="guild_logo">'.$displayData['glogo'].'</span>';
					echo '</div>';
					echo '<div class="col-6 text-center">';
						echo '<span class="guild_name ">'.$displayData['gname'].'</span>';
						echo '<table class="table table-borderless">';
							echo '<tr>';
								echo '<td class="text-end text-white">'.lang('profiles_txt_3',true).'</td>';
								echo '<td class="text-start">'.playerProfile($displayData['gmaster']).'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td class="text-end text-white">'.lang('profiles_txt_4',true).'</td>';
								echo '<td class="text-start text-white">'.number_format($displayData['gscore']).'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td class="text-end text-white">'.lang('profiles_txt_5',true).'</td>';
								echo '<td class="text-start text-white">'.number_format($displayData['gmembers']).'</td>';
							echo '</tr>';
						echo '</table>';
					echo '</div>';
				echo '</div>';
				echo '<hr>';
				// Guild Members
				echo '<div class="row">';
					echo '<div class="col-12 text-center">';
						echo '<span class="guild_members">'.lang('profiles_txt_6',true).'</span>';
						echo '<div class="row guild_members_list">';
							if(is_array($guildMembers)) {
								foreach($guildMembers as $memberName) {
									echo '<div class="col-6 col-sm-4 col-md-3">'.playerProfile($memberName).'</div>';
								}
							}
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';

		} catch(Exception $e) {
			message('error', $e->getMessage());
		}
	} else {
		message('error', lang('error_25',true));
	}
} else {
	message('error', lang('error_47',true));
}