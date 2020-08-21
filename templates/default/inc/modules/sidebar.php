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

// Login block
if(!isLoggedIn()) {
	echo '<div class="panel panel-sidebar">';
		echo '<div class="panel-heading">';
			echo '<h3 class="panel-title">'.lang('module_titles_txt_2').' <a href="'.__BASE_URL__.'forgotpassword" class="btn btn-primary btn-xs pull-right">'.lang('login_txt_4').'</a></h3>';
		echo '</div>';
		echo '<div class="panel-body">';
			echo '<form action="'.__BASE_URL__.'login" method="post">';
				echo '<div class="form-group">';
					echo '<input type="text" class="form-control" id="loginBox1" name="webengineLogin_user" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<input type="password" class="form-control" id="loginBox2" name="webengineLogin_pwd" required>';
				echo '</div>';
				echo '<button type="submit" name="webengineLogin_submit" value="submit" class="btn btn-primary">'.lang('login_txt_3').'</button>';
			echo '</form>';
		echo '</div>';
	echo '</div>';

	// join now banner
	echo '<div class="sidebar-banner"><a href="'.__BASE_URL__.'register"><img src="'.__PATH_TEMPLATE_IMG__.'sidebar_banner_join.jpg"/></a></div>';
}

// Usercp block
if(isLoggedIn()) {
	echo '<div class="panel panel-sidebar panel-usercp">';
		echo '<div class="panel-heading">';
			echo '<h3 class="panel-title">'.lang('usercp_menu_title').' <a href="'.__BASE_URL__.'logout" class="btn btn-primary btn-xs pull-right">logout</a></h3>';
		echo '</div>';
		echo '<div class="panel-body">';
				templateBuildUsercp();
		echo '</div>';
	echo '</div>';
}

// download banner
echo '<div class="sidebar-banner"><a href="'.__BASE_URL__.'downloads"><img src="'.__PATH_TEMPLATE_IMG__.'sidebar_banner_download.jpg"/></a></div>';

// Server info block
if(is_array($srvInfo)) {
	echo '<div class="panel panel-sidebar">';
		echo '<div class="panel-heading">';
			echo '<h3 class="panel-title">'.lang('sidebar_srvinfo_txt_1').'</h3>';
		echo '</div>';
		echo '<div class="panel-body">';
			echo '<table class="table">';
				if(check_value(config('server_info_season', true))) echo '<tr><td>'.lang('sidebar_srvinfo_txt_6').'</td><td>'.config('server_info_season', true).'</td></tr>';
				if(check_value(config('server_info_exp', true))) echo '<tr><td>'.lang('sidebar_srvinfo_txt_7').'</td><td>'.config('server_info_exp', true).'</td></tr>';
				if(check_value(config('server_info_masterexp', true))) echo '<tr><td>'.lang('sidebar_srvinfo_txt_8').'</td><td>'.config('server_info_masterexp', true).'</td></tr>';
				if(check_value(config('server_info_drop', true))) echo '<tr><td>'.lang('sidebar_srvinfo_txt_9').'</td><td>'.config('server_info_drop', true).'</td></tr>';
				echo '<tr><td>'.lang('sidebar_srvinfo_txt_2').'</td><td style="font-weight:bold;">'.number_format($srvInfo[0]).'</td></tr>';
				echo '<tr><td>'.lang('sidebar_srvinfo_txt_3').'</td><td style="font-weight:bold;">'.number_format($srvInfo[1]).'</td></tr>';
				echo '<tr><td>'.lang('sidebar_srvinfo_txt_4').'</td><td style="font-weight:bold;">'.number_format($srvInfo[2]).'</td></tr>';
				if(check_value(config('maximum_online', true))) echo '<tr><td>'.lang('sidebar_srvinfo_txt_5').'</td><td style="color:#00aa00;font-weight:bold;">'.number_format($onlinePlayers).'</td></tr>';
			echo '</table>';
		echo '</div>';
	echo '</div>';
}

// Castle Siege Block
templateCastleSiegeWidget();

// Top Level
$levelRankingData = LoadCacheData('rankings_level.cache');
$topLevelLimit = 5;
if(is_array($levelRankingData)) {
    $topLevel = array_slice($levelRankingData, 0, $topLevelLimit+1);
    echo '<div class="panel panel-sidebar">';
        echo '<div class="panel-heading">';
            echo '<h3 class="panel-title">'.lang('rankings_txt_1').'<a href="'.__BASE_URL__.'rankings/level" class="btn btn-primary btn-xs pull-right" style="text-align:center;width:22px;">+</a></h3>';
        echo '</div>';
        echo '<div class="panel-body">';
            echo '<table class="table table-condensed">';
                echo '<thead>';
					echo '<tr>';
						echo '<th class="text-center">'.lang('rankings_txt_10').'</th>'; // Character
						echo '<th class="text-center">'.lang('rankings_txt_11').'</th>'; // Class
						echo '<th class="text-center">'.lang('rankings_txt_12').'</th>'; // Level
					echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach($topLevel as $key => $row) {
                    if($key == 0) continue;
                    echo '<tr>';
                        echo '<td class="text-center">'.playerProfile($row[0]).'</td>';
                        echo '<td class="text-center">'.getPlayerClass($row[1]).'</td>';
                        echo '<td class="text-center">'.number_format($row[2]).'</td>';
                    echo '</tr>';
                }
            echo '</tbody>';
            echo '</table>';
        echo '</div>';
    echo '</div>';
}