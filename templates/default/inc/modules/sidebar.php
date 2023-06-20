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

$serverStatus = CheckGS(config('ip_game_server',true), config('port_game_server',true));

// Login block
if(!isLoggedIn()) {
	echo '<div class="card">';
		echo '<div class="card-header text-white bg-primary">';
		echo '<i class="fas fa-sign-in-alt"></i> '.lang('module_titles_txt_2').'';
		echo '</div>';
		echo '<div class="card-body">';
			echo '<form action="'.__BASE_URL__.'login" method="post">';
			echo '<div class="input-group mb-3">';
				echo '<span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>';
				echo '<input type="text" class="form-control" id="loginBox1" name="webengineLogin_user" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>';
			echo '</div>';
			echo '<div class="input-group mb-3">';
				echo '<span class="input-group-text" id="basic-addon2"><i class="fas fa-key"></i></span>';
				echo '<input type="password" class="form-control" id="loginBox2" name="webengineLogin_pwd" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2" required>';
			echo '</div>';
			echo '<div class="d-grid gap-2 d-md-block">';
				echo '<button type="submit" name="webengineLogin_submit" value="submit" class="btn btn-outline-primary btn-sm">'.lang('login_txt_3').'</button>';
				echo '<a href="'.__BASE_URL__.'forgotpassword" class="btn btn-outline-primary btn-sm me-md-2 float-end">'.lang('login_txt_4').'</a>
				</div>';
			echo '</form>';
		echo '</div>';
	echo '</div>';

	// join now banner
	echo '<center><div class="sidebar-banner"><a href="'.__BASE_URL__.'register"><img src="'.__PATH_TEMPLATE_IMG__.'sidebar_banner_join.jpg"/></a></div></center>';
}

// Usercp block
if(isLoggedIn()) {
	echo '<div class="card panel-usercp">';
		echo '<div class="card-header text-white bg-primary">';
		echo '<i class="fa-solid fa-screwdriver-wrench"></i> '.lang('usercp_menu_title').'';
		echo '</div>';
		echo '<div class="card-body">';
			templateBuildUsercp();
		echo '</div>';
		echo '<div class="card-footer text-center">';
			echo '<a href="'.__BASE_URL__.'logout" class="btn btn-primary btn-sm"><i class="fa-solid fa-arrow-right-from-bracket"></i> Salir</a>';
		echo '</div>';
	echo '</div>';
}

// download banner
echo '<center><div class="sidebar-banner"><a href="'.__BASE_URL__.'downloads"><img src="'.__PATH_TEMPLATE_IMG__.'sidebar_banner_download.jpg"/></a></div></center>';

// Server info block

	echo '<div class="card">';
		echo '<div class="card-header text-white bg-primary">';
			echo '<i class="fas fa-info-circle"></i> '.lang('sidebar_srvinfo_txt_1').'';
		echo '</div>';
		echo '<div class="card-body" style="padding:unset !important;">';
			echo '<table class="table table-striped">';
				if(check_value(config('server_info_season', true))) echo '<tr><td style="width:1%;text-align:center;"><i class="fa-solid fa-check-to-slot"></i></td><td> '.lang('sidebar_srvinfo_txt_6').'</td><td>'.config('server_info_season', true).'</td></tr>';
				if(check_value(config('server_info_exp', true))) echo '<tr><td style="width:1%;text-align:center;"><i class="fa-solid fa-square-poll-horizontal"></i></td><td> '.lang('sidebar_srvinfo_txt_7').'</td><td>'.config('server_info_exp', true).'</td></tr>';
				if(check_value(config('server_info_masterexp', true))) echo '<tr><td style="width:1%;text-align:center;"><i class="fa-solid fa-square-poll-vertical"></i></td><td> '.lang('sidebar_srvinfo_txt_8').'</td><td>'.config('server_info_masterexp', true).'</td></tr>';
				if(check_value(config('server_info_drop', true))) echo '<tr><td style="width:1%;text-align:center;"><i class="fas fa-tint"></i></td> <td>'.lang('sidebar_srvinfo_txt_9').'</td><td>'.config('server_info_drop', true).'</td></tr>';
				echo '<tr><td style="width:1%;text-align:center;"><i class="fa-solid fa-square-pen"></i></td><td>'.lang('sidebar_srvinfo_txt_2').'</td><td style="font-weight:bold;">'.number_format($srvInfo[0]).'</td></tr>';
				echo '<tr><td style="width:1%;text-align:center;"><i class="fas fa-user-friends"></i></td><td>'.lang('sidebar_srvinfo_txt_3').'</td><td style="font-weight:bold;">'.number_format($srvInfo[1]).'</td></tr>';
				echo '<tr><td style="width:1%;text-align:center;"><i class="fas fa-shield-alt"></i></td><td>'.lang('sidebar_srvinfo_txt_4').'</td><td style="font-weight:bold;">'.number_format($srvInfo[2]).'</td></tr>';
				if(check_value(config('maximum_online', true))) echo '<tr><td style="width:1%;text-align:center;"><i class="fas fa-signal"></i></td><td> '.lang('sidebar_srvinfo_txt_5').'</td><td style="font-weight:bold;" class="text-primary">'.number_format($onlinePlayers).'</td></tr>';
				if($serverStatus == true){
					echo '<tr><td style="width:1%;text-align:center;" class="align-middle"><i class="fa-solid fa-server"></i></td><td class="align-middle">Estado del Servidor</td><td style="font-weight:bold;" class="align-middle"><div class="spinner-grow spinner-grow-sm text-success" role="status"><span class="visually-hidden">Online...</span></div> <span class="text-success">Online</span></td></tr>';
				}else{
					echo '<tr><td style="width:1%;text-align:center;" class="align-middle"><i class="fa-solid fa-server"></i></td><td class="align-middle">Estado del Servidor</td><td style="font-weight:bold;" class="align-middle"><div class="spinner-grow spinner-grow-sm text-danger" role="status"><span class="visually-hidden">Offline...</span></div> <span class="text-danger">Offline</span></td></tr>';
				}
			echo '</table>';
		echo '</div>';
	echo '</div>';
	echo '<hr>';

// Castle Siege Block
templateCastleSiegeWidget();

// Top Level
$levelRankingData = LoadCacheData('rankings_level.cache');
$topLevelLimit = 5;

	echo '<div class="card">';
		echo '<div class="card-header bg-primary text-white">';
			echo '<i class="fa-solid fa-trophy"></i> '.lang('rankings_txt_1').'';
		echo '</div>';
		echo '<div class="card-body" style="padding:unset !important;">';
				echo '<table class="table table-striped">';
				echo '<thead class="table-dark">';
					echo '<tr>';
						echo '<th class="text-start ps-3"><i class="fa-solid fa-user-ninja"></i> '.lang('rankings_txt_10').'</th>'; // Character
						echo '<th class="text-center"><i class="fa-solid fa-wand-magic-sparkles"></i> '.lang('rankings_txt_11').'</th>'; // Class
						echo '<th class="text-center"><i class="fa-solid fa-circle-up"></i> '.lang('rankings_txt_12').'</th>'; // Level
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				if(is_array($levelRankingData)) {
					$topLevel = array_slice($levelRankingData, 0, $topLevelLimit+1);
						foreach($topLevel as $key => $row) {
							$characterIMG = getPlayerClassAvatar($row[1], true, true, 'rankings-class-image-sidebar border border-primary rounded-3');
							if($key == 0) continue;
							echo '<tr>';
								echo '<td class="text-start ps-3 align-middle">'.$characterIMG.'   '.playerProfile($row[0]).'</td>';
								echo '<td class="text-center align-middle">'.getPlayerClass($row[1]).'</td>';
								echo '<td class="text-center align-middle text-primary">'.number_format($row[2],0,",",".").'</td>';
							echo '</tr>';
						}
					}	
			echo '</tbody>';
			echo '</table>';
		echo '</div>';
		echo '<div class="card-footer text-center">';
			echo '<a href="'.__BASE_URL__.'rankings/level" class="btn btn-primary btn-sm">Ver Mas</a>';
		echo '</div>';
	echo '</div>';
	echo '<hr>';


// Top Resets
$ResetRankingData = LoadCacheData('rankings_resets.cache');
$topResetLimit = 5;
	echo '<div class="card">';
		echo '<div class="card-header text-white bg-primary">';
			echo '<i class="fa-solid fa-trophy"></i> '.lang('rankings_txt_2').'';
		echo '</div>';
		echo '<div class="card-body" style="padding:unset !important;">';
				echo '<table class="table table-striped">';
				echo '<thead class="table-dark">';
					echo '<tr>';
						echo '<th class="text-start ps-3"><i class="fa-solid fa-user-ninja"></i> '.lang('rankings_txt_10').'</th>'; // Character
						echo '<th class="text-center"><i class="fa-solid fa-wand-magic-sparkles"></i> '.lang('rankings_txt_11').'</th>'; // Class
						echo '<th class="text-center"><i class="fa-solid fa-repeat"></i> '.lang('rankings_txt_13').'</th>'; // Reset
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				if(is_array($ResetRankingData)) {
					$topReset = array_slice($ResetRankingData, 0, $topResetLimit+1);
						foreach($topReset as $key => $row) {
							$characterIMG = getPlayerClassAvatar($row[1], true, true, 'rankings-class-image-sidebar border border-primary rounded-3');
							if($key == 0) continue;
							echo '<tr>';
								echo '<td class="text-start ps-3 align-middle">'.$characterIMG.'   '.playerProfile($row[0]).'</td>';
								echo '<td class="text-center align-middle">'.getPlayerClass($row[1]).'</td>';
								echo '<td class="text-center align-middle text-primary">'.number_format($row[2],0,",",".").'</td>';
							echo '</tr>';
						}
					}
			echo '</tbody>';
			echo '</table>';
		echo '</div>';
		echo '<div class="card-footer text-center">';
			echo '<a href="'.__BASE_URL__.'rankings/resets" class="btn btn-primary btn-sm">Ver Mas</a>';
		echo '</div>';
	echo '</div>';
	echo '<hr>';


// Top Guilds
$GuildRankingData = LoadCacheData('rankings_guilds.cache');
$topGuildLimit = 5;
	echo '<div class="card">';
		echo '<div class="card-header text-white bg-primary">';
			echo '<i class="fa-solid fa-trophy"></i> '.lang('rankings_txt_4').'';
		echo '</div>';
		echo '<div class="card-body" style="padding:unset !important;">';
				echo '<table class="table table-striped">';
				echo '<thead class="table-dark">';
					echo '<tr>';
						echo '<th class="text-center"><i class="fa-solid fa-shield"></i> Guild</th>'; // Guild
						echo '<th class="text-center"><i class="fa-solid fa-user-shield"></i> Master</th>'; // Master
						echo '<th class="text-center"><i class="fa-solid fa-bolt"></i> '.lang('rankings_txt_19').'</th>'; // Score
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				if(is_array($GuildRankingData)) {
					$topGuild = array_slice($GuildRankingData, 0, $topGuildLimit+1);
						foreach($topGuild as $key => $row) {
							if($key == 0) continue;
							echo '<tr>';
								echo '<td class="text-start ps-3 align-middle">'.returnGuildLogo($row[3], 30).' '.guildProfile($row[0]).'</td>';
								echo '<td class="text-center align-middle">'.playerProfile($row[1]).'</td>';
								echo '<td class="text-center align-middle">'.number_format($row[2],0,",",".").'</td>';
							echo '</tr>';
						}
					}
			echo '</tbody>';
			echo '</table>';
		echo '</div>';
		echo '<div class="card-footer text-center">';
			echo '<a href="'.__BASE_URL__.'rankings/guilds" class="btn btn-primary btn-sm">Ver Mas</a>';
		echo '</div>';
	echo '</div>';
	echo '<hr>';
