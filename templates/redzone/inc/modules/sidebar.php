<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.1.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

# Login block
if(!isLoggedIn()) {
	echo '<div class="panel panel-sidebar">';
		echo '<div class="panel-heading">';
			echo '<h3 class="panel-title">'.lang('module_titles_txt_2',true).' <a href="'.__BASE_URL__.'forgotpassword" class="btn btn-primary btn-xs pull-right">'.lang('login_txt_4',true).'</a></h3>';
		echo '</div>';
		echo '<div class="panel-body">';
			echo '<form action="'.__BASE_URL__.'login" method="post">';
				echo '<div class="form-group">';
					echo '<input type="text" class="form-control" id="loginBox1" name="webengineLogin_user" required>';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<input type="password" class="form-control" id="loginBox2" name="webengineLogin_pwd" required>';
				echo '</div>';
				echo '<button type="submit" name="webengineLogin_submit" value="submit" class="btn btn-primary">'.lang('login_txt_3',true).'</button>';
			echo '</form>';
		echo '</div>';
	echo '</div>';

	# join now banner
	echo '<div style="margin:20px 0px;">';
		echo '<a href="'.__BASE_URL__.'register">';
			echo '<video width="300" height="100" preload="true" autoplay="" loop="" muted="">';
				echo '<source src="'.__PATH_TEMPLATE_IMG__.'video/register_button.webm" type="video/webm">';
				echo '<source src="'.__PATH_TEMPLATE_IMG__.'video/register_button.ogg" type="video/ogg">';
				echo '<source src="'.__PATH_TEMPLATE_IMG__.'video/register_button.mp4" type="video/mp4">';
				echo '<img src="'.__PATH_TEMPLATE_IMG__.'register_sidebar_banner.jpg"/>';
			echo '</video>';
		echo '</a>';
	echo '</div>';
}



# Usercp block
if(isLoggedIn()) {
echo '<div class="panel panel-sidebar panel-usercp">';
	echo '<div class="panel-heading">';
		echo '<h3 class="panel-title">'.lang('usercp_menu_title',true).' <a href="'.__BASE_URL__.'logout" class="btn btn-primary btn-xs pull-right">logout</a></h3>';
	echo '</div>';
	echo '<div class="panel-body">';
			templateBuildUsercp();
	echo '</div>';
echo '</div>';
}



# download banner
echo '<div class="sidebar-banner"><a href="'.__BASE_URL__.'downloads"><img src="'.__PATH_TEMPLATE_IMG__.'download_sidebar_banner.jpg"/></a></div>';



# Server info block
$srvInfoCache = LoadCacheData('server_info.cache');
if(is_array($srvInfoCache)) {
	$srvInfo = explode("|", $srvInfoCache[1][0]);
	
	echo '<div class="panel panel-sidebar">';
		echo '<div class="panel-heading">';
			echo '<h3 class="panel-title">'.lang('sidebar_srvinfo_txt_1',true).'</h3>';
		echo '</div>';
		echo '<div class="panel-body">';
			echo '<table class="table">';
				
				//echo '<tr><td>Version:</td><td>S12EP1</td></tr>';
				//echo '<tr><td>Experience:</td><td>10x</td></tr>';
				//echo '<tr><td>Drop:</td><td>20%</td></tr>';
				
				echo '<tr><td>'.lang('sidebar_srvinfo_txt_2',true).'</td><td style="font-weight:bold;">'.number_format($srvInfo[0]).'</td></tr>';
				echo '<tr><td>'.lang('sidebar_srvinfo_txt_3',true).'</td><td style="font-weight:bold;">'.number_format($srvInfo[1]).'</td></tr>';
				echo '<tr><td>'.lang('sidebar_srvinfo_txt_4',true).'</td><td style="font-weight:bold;">'.number_format($srvInfo[2]).'</td></tr>';
				echo '<tr><td>'.lang('sidebar_srvinfo_txt_5',true).'</td><td style="color:#00aa00;font-weight:bold;">'.number_format($srvInfo[3]).'</td></tr>';
			echo '</table>';
		echo '</div>';
	echo '</div>';
}



# Video block
echo '<div class="panel panel-sidebar">';
	echo '<div class="panel-body">';
		echo '<iframe width="271" height="152" src="https://www.youtube.com/embed/H5QQDvgU-hE" frameborder="0" allowfullscreen></iframe>';
	echo '</div>';
echo '</div>';



# FB block
/*
$facebookPage = 'MUE.WebEngine';
echo '<div class="panel panel-sidebar">';
	echo '<div class="panel-body">';
		echo '<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F'.$facebookPage.'&tabs=timeline&width=271&height=300&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false" width="271" height="300" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>';
	echo '</div>';
echo '</div>';
*/

/*
# Default block
echo '<div class="panel panel-sidebar">';
	echo '<div class="panel-heading">';
		echo '<h3 class="panel-title">Title</h3>';
	echo '</div>';
	echo '<div class="panel-body">';
		
	echo '</div>';
echo '</div>';
*/