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

// check install directory
if(file_exists(__ROOT_DIR__ . 'install/')) {
	message('warning', 'Your WebEngine CMS <strong>install</strong> directory still exists, it is recommended that you rename or delete it.', 'WARNING');
}

echo '<div class="row">';
	echo '<div class="col-md-6">';
		echo '<div class="panel panel-primary">';
		echo '<div class="panel-heading">General Information</div>';
		echo '<div class="panel-body">';
			
			echo '<div class="list-group">';
				echo '<div class="list-group-item" target="_blank">';
					echo 'OS';
					echo '<span class="pull-right text-muted small">';
						echo '<em>'.PHP_OS.'</em>';
					echo '</span>';
				echo '</div>';
				echo '<div class="list-group-item" target="_blank">';
					echo 'PHP';
					echo '<span class="pull-right text-muted small">';
						echo '<em>'.phpversion().'</em>';
					echo '</span>';
				echo '</div>';
				echo '<a href="https://webenginecms.org/" class="list-group-item" target="_blank">';
					echo 'WebEngine';
					echo '<span class="pull-right text-muted small">';
						if(checkVersion()) echo '<span class="label label-danger">Update Available</span>  ';
						echo '<em>'.__WEBENGINE_VERSION__.'</em>';
					echo '</span>';
				echo '</a>';
			echo '</div>';
			
			echo '<div class="list-group">';
				
				$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
				@$handler->checkWebEngineBlacklist();
				
				// Total Accounts
				$totalAccounts = $database->query_fetch_single("SELECT COUNT(*) as result FROM MEMB_INFO");
				echo '<div class="list-group-item">';
					echo 'Registered Accounts';
					echo '<span class="pull-right text-muted small">'.number_format($totalAccounts['result']).'</span>';
				echo '</div>';
				
				// Banned Accounts
				$bannedAccounts = $database->query_fetch_single("SELECT COUNT(*) as result FROM MEMB_INFO WHERE bloc_code = 1");
				echo '<div class="list-group-item">';
					echo 'Banned Accounts';
					echo '<span class="pull-right text-muted small">'.number_format($bannedAccounts['result']).'</span>';
				echo '</div>';
				
				// Total Characters
				$totalCharacters = $dB->query_fetch_single("SELECT COUNT(*) as result FROM Character");
				echo '<div class="list-group-item">';
					echo 'Characters';
					echo '<span class="pull-right text-muted small">'.number_format($totalCharacters['result']).'</span>';
				echo '</div>';
				
				// Plugins Status
				$pluginStatus = (config('plugins_system_enable',true) ? 'Enabled' : 'Disabled');
				echo '<div class="list-group-item">';
					echo 'Plugin System';
					echo '<span class="pull-right text-muted small">'.$pluginStatus.'</span>';
				echo '</div>';
				
				// Scheduled Tasks
				$scheduledTasks = $database->query_fetch_single("SELECT COUNT(*) as result FROM ".WEBENGINE_CRON."");
				echo '<div class="list-group-item">';
					echo 'Scheduled Tasks (cron)';
					echo '<span class="pull-right text-muted small">'.number_format($scheduledTasks['result']).'</span>';
				echo '</div>';
				
				// Server Time
				echo '<div class="list-group-item">';
					echo 'Server Time (web)';
					echo '<span class="pull-right text-muted small">'.date("Y-m-d h:i A").'</span>';
				echo '</div>';
				
				// Admins
				$admincpUsers = implode(", ", array_keys(config('admins',true)));
				echo '<div class="list-group-item">';
					echo 'Admins';
					echo '<span class="pull-right text-muted small">'.$admincpUsers.'</span>';
				echo '</div>';
				
			echo '</div>';
		echo '</div>';
		echo '</div>';
	echo '</div>';
	
	echo '<div class="col-md-6">';
		echo '<div class="panel panel-default">';
		echo '<div class="panel-body">';
			echo '<div style="margin-bottom:7px;"><a href="https://webenginecms.org/discord/" class="btn btn-default" target="_blank">Discord</a></div>';
			echo '<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FMUE.WebEngine&amp;width=600&amp;height=447&amp;colorscheme=light&amp;show_faces=false&amp;header=false&amp;stream=true&amp;show_border=false&amp;appId=1439010682981422" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:447px;" allowTransparency="true"></iframe>';
		echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';