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
			echo '<h3 class="panel-title">'.lang('usercp_menu_title').' <a href="'.__BASE_URL__.'logout" class="btn btn-primary btn-xs pull-right">'.lang('login_txt_6').'</a></h3>';
		echo '</div>';
		echo '<div class="panel-body">';
				templateBuildUsercp();
		echo '</div>';
	echo '</div>';
}

// download banner
echo '<div class="sidebar-banner"><a href="'.__BASE_URL__.'downloads"><img src="'.__PATH_TEMPLATE_IMG__.'sidebar_banner_download.jpg"/></a></div>';

// Server info block
if(isset($srvInfo) && is_array($srvInfo)) {
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