<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

$castleSiege = new CastleSiege();
$siegeData = $castleSiege->siegeData();
if(!is_array($siegeData)) throw new Exception(lang('error_103'));
if(!$castleSiege->moduleEnabled()) throw new Exception(lang('error_47'));

// Module title
echo '<div class="page-title"><span>'.lang('module_titles_txt_29',true).'</span></div>';

echo '<div class="row">';
	echo '<div class="col-xs-12 castle-siege-block">';

	//
	// CASTLE SIEGE GUILD OWNER AND ALLIANCE
	//
	if($castleSiege->showCastleOwner() && $siegeData['castle_data'][_CLMN_MCD_OCCUPY_] == 1) {
		echo '<h2>'.lang('castlesiege_txt_2').'</h2>';
		echo '<div class="panel castle-owner-panel">';
			echo '<div class="panel-body">';
				echo '<div class="row">';
					echo '<div class="col-xs-4 text-center">';
						echo '<span class="castle-owner-name">'.guildProfile($siegeData['castle_owner_alliance'][0]['G_Name']).'</span>';
						echo returnGuildLogo($siegeData['castle_owner_alliance'][0]['G_Mark'], 100);
						echo '<h4>'.lang('castlesiege_txt_12').'</h4>';
						echo '<p>'.playerProfile($siegeData['castle_owner_alliance'][0]['G_Master']).'</p>';
					echo '</div>';
					echo '<div class="col-xs-8">';
						
						// Castle Owner Alliance
						if($castleSiege->showCastleOwnerAlliance()) {
							if(is_array($siegeData['castle_owner_alliance']) && count($siegeData['castle_owner_alliance']) > 1) {
								echo '<div class="row">';
									echo '<div class="col-xs-12 text-center">';
										echo '<h4>'.lang('castlesiege_txt_13').'</h4>';
										echo '<div class="row castle-owner-ally-title">';
											echo '<div class="col-xs-4">';
												echo '<strong>'.lang('castlesiege_txt_16').'</strong>';
											echo '</div>';
											echo '<div class="col-xs-4">';
												echo '<strong>'.lang('castlesiege_txt_14').'</strong>';
											echo '</div>';
											echo '<div class="col-xs-4">';
												echo '<strong>'.lang('castlesiege_txt_15').'</strong>';
											echo '</div>';
										echo '</div>';
										foreach($siegeData['castle_owner_alliance'] as $key => $alliedGuild) {
											if($key == 0) continue;
											echo '<div class="row castle-owner-ally">';
												echo '<div class="col-xs-4">';
													echo returnGuildLogo($alliedGuild['G_Mark'], 25);
												echo '</div>';
												echo '<div class="col-xs-4">';
													echo guildProfile($alliedGuild['G_Name']);
												echo '</div>';
												echo '<div class="col-xs-4">';
													echo playerProfile($alliedGuild['G_Master']);
												echo '</div>';
											echo '</div>';
										}
									echo '</div>';
								echo '</div>';
							}
						}
						
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		
		echo '<hr>';
	}

	//
	// CASTLE SIEGE BATTLE COUNTDOWN TIMER
	//
	if($castleSiege->showBattleCountdown()) {
		echo '<h2>'.lang('castlesiege_txt_1').'</h2>';
		echo '<div id="siegeTimer">.</div>';
		
		echo '<hr>';
	}

	//
	// CASTLE SIEGE INFORMATION
	//
	if($castleSiege->showCastleInformation()) {
		echo '<h2>'.lang('castlesiege_txt_7').'</h2>';
		echo '<table class="table table-condensed table-striped">';
			echo '<tbody>';
				if($castleSiege->showCurrentStage()) {
				echo '<tr>';
					echo '<td>'.lang('castlesiege_txt_9').'</td>';
					echo '<td>'.$siegeData['current_stage']['title'].'</td>';
				echo '</tr>';
				}
				if($castleSiege->showNextStage()) {
				echo '<tr>';
					echo '<td>'.lang('castlesiege_txt_10').'</td>';
					echo '<td>'.$siegeData['next_stage']['title'].' ('.$siegeData['next_stage_countdown'].')</td>';
				echo '</tr>';
				}
				if($castleSiege->showBattleDuration()) {
				echo '<tr>';
					echo '<td>'.lang('castlesiege_txt_11').'</td>';
					echo '<td>'.$castleSiege->getWarfareDuration().'</td>';
				echo '</tr>';
				}
				echo '<tr>';
					echo '<td>'.lang('castlesiege_txt_5').'</td>';
					echo '<td>'.$siegeData['castle_data'][_CLMN_MCD_TRS_].'%</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>'.lang('castlesiege_txt_4').'</td>';
					echo '<td>'.$siegeData['castle_data'][_CLMN_MCD_TRC_].'%</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>'.lang('castlesiege_txt_6').'</td>';
					echo '<td>'.$siegeData['castle_data'][_CLMN_MCD_THZ_].'%</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>'.lang('castlesiege_txt_3').'</td>';
					echo '<td>'.number_format($siegeData['castle_data'][_CLMN_MCD_MONEY_]).' '.lang('castlesiege_txt_8').'</td>';
				echo '</tr>';
			echo '</tbody>';
		echo '</table>';
		
		echo '<hr>';
	}

	//
	// CASTLE SIEGE REGISTERED GUILDS
	//
	if($castleSiege->showRegisteredGuilds() && is_array($siegeData['registered_guilds'])) {
		echo '<h2>'.lang('castlesiege_txt_19').'</h2>';
		echo '<table class="table table-condensed table-striped">';
			echo '<thead>';
				echo '<tr>';
					echo '<th class="text-center">'.lang('castlesiege_txt_16').'</th>';
					echo '<th class="text-center">'.lang('castlesiege_txt_14').'</th>';
					echo '<th class="text-center">'.lang('castlesiege_txt_15').'</th>';
					echo '<th class="text-center">'.lang('castlesiege_txt_17').'</th>';
					echo '<th class="text-center">'.lang('castlesiege_txt_18').'</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach($siegeData['registered_guilds'] as $registeredGuild) {
				echo '<tr>';
					echo '<td class="text-center">'.returnGuildLogo($registeredGuild['G_Mark'], 20).'</td>';
					echo '<td class="text-center">'.guildProfile($registeredGuild['G_Name']).'</td>';
					echo '<td class="text-center">'.playerProfile($registeredGuild['G_Master']).'</td>';
					echo '<td class="text-center">'.$registeredGuild['G_Score'].'</td>';
					echo '<td class="text-center">'.$registeredGuild['member_count'].'</td>';
				echo '</tr>';
			}
			echo '</tbody>';
		echo '</table>';
		
		echo '<hr>';
	}

	//
	// CASTLE SIEGE SCHEDULE
	//
	if($castleSiege->showSchedule()) {
		echo '<h2>'.lang('castlesiege_txt_20').'</h2>';
		echo '<table class="table table-condensed table-striped">';
			echo '<thead>';
				echo '<tr>';
					echo '<th>'.lang('castlesiege_txt_21').'</th>';
					echo '<th>'.lang('castlesiege_txt_22').'</th>';
					echo '<th>'.lang('castlesiege_txt_23').'</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach($siegeData['schedule'] as $stage) {
				echo '<tr>';
					echo '<td>'.$stage['title'].'</td>';
					echo '<td>'.$castleSiege->friendlyDateFormat($stage['start_timestamp']).'</td>';
					echo '<td>'.$castleSiege->friendlyDateFormat($stage['end_timestamp']).'</td>';
				echo '</tr>';
			}
			echo '</tbody>';
		echo '</table>';
	}

	echo '</div>';
echo '</div>';