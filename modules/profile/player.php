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

echo '<div class="page-title"><span>'.lang('profiles_txt_2',true).'</span></div>';

loadModuleConfigs('profiles');
if(mconfig('active')) {
	if(check_value($_GET['req'])) {
		try {
			$weProfiles = new weProfiles();
			$weProfiles->setType("player");
			$weProfiles->setRequest($_GET['req']);
			$cData = $weProfiles->data();
			
			echo '<div class="profiles_player_card '.$custom['character_class'][$cData[2]][1].'">';
				echo '<div class="profiles_player_content">';
					echo '<table class="profiles_player_table">';
						echo '<tr>';
							echo '<td class="cname">'.$cData[1].'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td class="cclass">'.$custom['character_class'][$cData[2]][0].'</td>';
						echo '</tr>';
					echo '</table>';
					
					# info table
					echo '<table class="profiles_player_table profiles_player_table_info">';
						echo '<tr>';
							echo '<td>'.lang('profiles_txt_7',true).'</td>';
							echo '<td>'.number_format($cData[3]).'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>'.lang('profiles_txt_20',true).'</td>';
							echo '<td>'.number_format($cData[14]).'</td>';
						echo '</tr>';
						if(check_value($cData[4])) {
							echo '<tr>';
								echo '<td>'.lang('profiles_txt_8',true).'</td>';
								echo '<td>'.number_format($cData[4]).'</td>';
							echo '</tr>';
						}
						if(check_value($cData[11])) {
							echo '<tr>';
								echo '<td>'.lang('profiles_txt_9',true).'</td>';
								echo '<td>'.number_format($cData[11]).'</td>';
							echo '</tr>';
						}
						echo '<tr>';
							echo '<td>'.lang('profiles_txt_10',true).'</td>';
							echo '<td>'.number_format($cData[5]).'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>'.lang('profiles_txt_11',true).'</td>';
							echo '<td>'.number_format($cData[6]).'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>'.lang('profiles_txt_12',true).'</td>';
							echo '<td>'.number_format($cData[7]).'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>'.lang('profiles_txt_13',true).'</td>';
							echo '<td>'.number_format($cData[8]).'</td>';
						echo '</tr>';
						if($custom['character_class'][$cData[2]]['base_stats']['cmd'] > 0) {
							echo '<tr>';
								echo '<td>'.lang('profiles_txt_14',true).'</td>';
								echo '<td>'.number_format($cData[9]).'</td>';
							echo '</tr>';
						}
						echo '<tr>';
							echo '<td>'.lang('profiles_txt_15',true).'</td>';
							echo '<td>'.number_format($cData[10]).'</td>';
						echo '</tr>';
						if(check_value($cData[12])) {
							echo '<tr>';
								echo '<td>'.lang('profiles_txt_16',true).'</td>';
								echo '<td>'.guildProfile($cData[12]).'</td>';
							echo '</tr>';
						}
						echo '<tr>';
							echo '<td>'.lang('profiles_txt_17',true).'</td>';
							if($cData[13]) {
								echo '<td class="isonline">'.lang('profiles_txt_18',true).'</td>';
							} else {
								echo '<td class="isoffline">'.lang('profiles_txt_19',true).'</td>';
							}
						echo '</tr>';
					echo '</table>';
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