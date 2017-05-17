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
?>
<div class="page-container">
	<div class="page-title"><span><?php lang('profiles_txt_1'); ?></span></div>
	<div class="page-content">
		<?php
			loadModuleConfigs('profiles');
			if(mconfig('active')) {
				if(check_value($_GET['req'])) {
					try {
						$weProfiles = new weProfiles($dB,$common);
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
						
						echo '<table class="profiles_guild_table">';
							echo '<tr>';
								echo '<td class="gname" colspan="3">'.$displayData['gname'].'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td rowspan="4">'.$displayData['glogo'].'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>'.lang('profiles_txt_3',true).'</td>';
								echo '<td><a href="'.__BASE_URL__.'profile/player/req/'.$displayData['gmaster'].'/" target="_blank">'.$displayData['gmaster'].'</a></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>'.lang('profiles_txt_4',true).'</td>';
								echo '<td>'.$displayData['gscore'].'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>'.lang('profiles_txt_5',true).'</td>';
								echo '<td>'.$displayData['gmembers'].'</td>';
							echo '</tr>';
						echo '</table>';
						
						echo '<table class="profiles_guild_table">';
							echo '<tr>';
								echo '<td class="gmembs">'.lang('profiles_txt_6',true).'</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td class="memblist">';
								foreach($guildMembers as $gMember) {
									if($gMember != $displayData['gmaster']) {
										echo '<div><a href="'.__BASE_URL__.'profile/player/req/'.$gMember.'/" target="_blank">'.$gMember.'</a></div>';
									}
								}
								echo '</td>';
							echo '</tr>';
						echo '</table>';

					} catch(Exception $e) {
						message('error', $e->getMessage());
					}
				} else {
					message('error', lang('error_25',true));
				}
			} else {
				message('error', lang('error_47',true));
			}
		?>
	</div>
</div>