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

echo '<h1 class="page-header">Website Settings</h1>';

$allowedSettings = array(
	'settings_submit', # the submit button
	'system_active',
	'error_reporting',
	'website_template',
	'maintenance_page',
	'server_name',
	'website_title',
	'website_meta_description',
	'website_meta_keywords',
	'website_forum_link',
	'server_files',
	'language_switch_active',
	'language_default',
	'language_debug',
	'plugins_system_enable',
	'ip_block_system_enable',
	'player_profiles',
	'guild_profiles',
	'username_min_len',
	'username_max_len',
	'password_min_len',
	'password_max_len',
	'cron_api',
	'cron_api_key',
	'social_link_facebook',
	'social_link_instagram',
	'social_link_discord',
	'server_info_season',
	'server_info_exp',
	'server_info_masterexp',
	'server_info_drop',
	'maximum_online',
);

if(check_value($_POST['settings_submit'])) {
	try {
		
		# website status
		if(!check_value($_POST['system_active'])) throw new Exception('Invalid Website Status setting.');
		if(!in_array($_POST['system_active'], array(0, 1))) throw new Exception('Invalid Website Status setting.');
		$setting['system_active'] = ($_POST['system_active'] == 1 ? true : false);
		
		# error reporting
		if(!check_value($_POST['error_reporting'])) throw new Exception('Invalid Error Reporting setting.');
		if(!in_array($_POST['error_reporting'], array(0, 1))) throw new Exception('Invalid Error Reporting setting.');
		$setting['error_reporting'] = ($_POST['error_reporting'] == 1 ? true : false);
		
		# default template
		if(!check_value($_POST['website_template'])) throw new Exception('Invalid Default Template setting.');
		if(!file_exists(__PATH_TEMPLATES__.$_POST['website_template'].'/index.php')) throw new Exception('The selected template doesn\'t exist.');
		$setting['website_template'] = $_POST['website_template'];
		
		# maintenance page
		if(!check_value($_POST['maintenance_page'])) throw new Exception('Invalid Maintenance Page setting.');
		if(!Validator::Url($_POST['maintenance_page'])) throw new Exception('The maintenance page setting is not a valid URL.');
		$setting['maintenance_page'] = $_POST['maintenance_page'];
		
		# server name
		if(!check_value($_POST['server_name'])) throw new Exception('Invalid Server Name setting.');
		$setting['server_name'] = $_POST['server_name'];
		
		# website title
		if(!check_value($_POST['website_title'])) throw new Exception('Invalid Website Title setting.');
		$setting['website_title'] = $_POST['website_title'];
		
		# meta description
		if(!check_value($_POST['website_meta_description'])) throw new Exception('Invalid Meta Description setting.');
		$setting['website_meta_description'] = $_POST['website_meta_description'];
		
		# meta keywords
		if(!check_value($_POST['website_meta_keywords'])) throw new Exception('Invalid Meta Keywords setting.');
		$setting['website_meta_keywords'] = $_POST['website_meta_keywords'];
		
		# forum link
		if(!check_value($_POST['website_forum_link'])) throw new Exception('Invalid Forum Link setting.');
		if(!Validator::Url($_POST['website_forum_link'])) throw new Exception('The forum link setting is not a valid URL.');
		$setting['website_forum_link'] = $_POST['website_forum_link'];
		
		# server files
		if(!check_value($_POST['server_files'])) throw new Exception('Invalid Server Files setting.');
		if(!array_key_exists($_POST['server_files'], $webengine['file_compatibility'])) throw new Exception('Invalid Server Files setting.');
		$setting['server_files'] = $_POST['server_files'];
		
		# language switch
		if(!check_value($_POST['language_switch_active'])) throw new Exception('Invalid Language Switch setting.');
		if(!in_array($_POST['language_switch_active'], array(0, 1))) throw new Exception('Invalid Language Switch setting.');
		$setting['language_switch_active'] = ($_POST['language_switch_active'] == 1 ? true : false);
		
		# language default
		if(!check_value($_POST['language_default'])) throw new Exception('Invalid Default Language setting.');
		if(!file_exists(__PATH_LANGUAGES__.$_POST['language_default'].'/language.php')) throw new Exception('The default language doesn\'t exist.');
		$setting['language_default'] = $_POST['language_default'];
		
		# language debug
		if(!check_value($_POST['language_debug'])) throw new Exception('Invalid Language Debug setting.');
		if(!in_array($_POST['language_debug'], array(0, 1))) throw new Exception('Invalid Language Debug setting.');
		$setting['language_debug'] = ($_POST['language_debug'] == 1 ? true : false);
		
		# plugin system
		if(!check_value($_POST['plugins_system_enable'])) throw new Exception('Invalid Plugin System setting.');
		if(!in_array($_POST['plugins_system_enable'], array(0, 1))) throw new Exception('Invalid Plugin System setting.');
		$setting['plugins_system_enable'] = ($_POST['plugins_system_enable'] == 1 ? true : false);
		
		# ip block system
		if(!check_value($_POST['ip_block_system_enable'])) throw new Exception('Invalid IP Block System setting.');
		if(!in_array($_POST['ip_block_system_enable'], array(0, 1))) throw new Exception('Invalid IP Block System setting.');
		$setting['ip_block_system_enable'] = ($_POST['ip_block_system_enable'] == 1 ? true : false);
		
		# player_profiles
		if(!check_value($_POST['player_profiles'])) throw new Exception('Invalid setting (player_profiles)');
		if(!in_array($_POST['player_profiles'], array(0, 1))) throw new Exception('Invalid setting (player_profiles)');
		$setting['player_profiles'] = ($_POST['player_profiles'] == 1 ? true : false);
		
		# guild_profiles
		if(!check_value($_POST['guild_profiles'])) throw new Exception('Invalid setting (guild_profiles)');
		if(!in_array($_POST['guild_profiles'], array(0, 1))) throw new Exception('Invalid setting (guild_profiles)');
		$setting['guild_profiles'] = ($_POST['guild_profiles'] == 1 ? true : false);
		
		# username_min_len
		if(!check_value($_POST['username_min_len'])) throw new Exception('Invalid setting (username_min_len)');
		if(!Validator::UnsignedNumber($_POST['username_min_len'])) throw new Exception('Invalid setting (username_min_len)');
		$setting['username_min_len'] = $_POST['username_min_len'];
		
		# username_max_len
		if(!check_value($_POST['username_max_len'])) throw new Exception('Invalid setting (username_max_len)');
		if(!Validator::UnsignedNumber($_POST['username_max_len'])) throw new Exception('Invalid setting (username_max_len)');
		$setting['username_max_len'] = $_POST['username_max_len'];
		
		# password_min_len
		if(!check_value($_POST['password_min_len'])) throw new Exception('Invalid setting (password_min_len)');
		if(!Validator::UnsignedNumber($_POST['password_min_len'])) throw new Exception('Invalid setting (password_min_len)');
		$setting['password_min_len'] = $_POST['password_min_len'];
		
		# password_max_len
		if(!check_value($_POST['password_max_len'])) throw new Exception('Invalid setting (password_max_len)');
		if(!Validator::UnsignedNumber($_POST['password_max_len'])) throw new Exception('Invalid setting (password_max_len)');
		$setting['password_max_len'] = $_POST['password_max_len'];
		
		# cron_api
		if(!check_value($_POST['cron_api'])) throw new Exception('Invalid setting (cron_api)');
		if(!in_array($_POST['cron_api'], array(0, 1))) throw new Exception('Invalid setting (cron_api)');
		$setting['cron_api'] = ($_POST['cron_api'] == 1 ? true : false);
		
		# cron_api_key
		if(!check_value($_POST['cron_api_key'])) throw new Exception('Invalid setting (cron_api_key)');
		$setting['cron_api_key'] = $_POST['cron_api_key'];
		
		# social link facebook
		if(check_value($_POST['social_link_facebook'])) if(!Validator::Url($_POST['social_link_facebook'])) throw new Exception('The facebook link setting is not a valid URL.');
		$setting['social_link_facebook'] = $_POST['social_link_facebook'];
		
		# social link instagram
		if(check_value($_POST['social_link_instagram'])) if(!Validator::Url($_POST['social_link_instagram'])) throw new Exception('The instagram link setting is not a valid URL.');
		$setting['social_link_instagram'] = $_POST['social_link_instagram'];
		
		# social link discord
		if(check_value($_POST['social_link_discord'])) if(!Validator::Url($_POST['social_link_discord'])) throw new Exception('The discord link setting is not a valid URL.');
		$setting['social_link_discord'] = $_POST['social_link_discord'];
		
		# server info season
		$setting['server_info_season'] = $_POST['server_info_season'];
		
		# server info exp
		$setting['server_info_exp'] = $_POST['server_info_exp'];
		
		# server info master exp
		$setting['server_info_masterexp'] = $_POST['server_info_masterexp'];
		
		# server info drop
		$setting['server_info_drop'] = $_POST['server_info_drop'];
		
		# maximum online
		if(check_value($_POST['maximum_online'])) if(!Validator::UnsignedNumber($_POST['maximum_online'])) throw new Exception('Invalid setting (maximum_online)');
		$setting['maximum_online'] = $_POST['maximum_online'];
		
		# webengine configs
		$webengineConfigurations = webengineConfigs();
		
		# make sure the settings are in the allow list
		foreach(array_keys($setting) as $settingName) {
			if(!in_array($settingName, $allowedSettings)) throw new Exception('One or more submitted setting is not editable.');
			
			$webengineConfigurations[$settingName] = $setting[$settingName];
		}
		
		$newWebEngineConfig = json_encode($webengineConfigurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem opening the configuration file.');
		
		fwrite($cfgFile, $newWebEngineConfig);
		fclose($cfgFile);
		
		message('success', 'Settings successfully saved!');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="col-md-12">';
	echo '<form action="" method="post">';
		echo '<table class="table table-striped table-bordered table-hover" style="table-layout: fixed;">';
			
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Website Status</strong>';
					echo '<p class="setting-description">Enables/disables your website. If disabled, visitors will be redirected to the maintenance page.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="system_active" value="1" '.(config('system_active',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="system_active" value="0" '.(!config('system_active',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Debug Mode</strong>';
					echo '<p class="setting-description">Debugging mode, enable this setting only if you want the website to display any errors.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="error_reporting" value="1" '.(config('error_reporting',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="error_reporting" value="0" '.(!config('error_reporting',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Default Template</strong>';
					echo '<p class="setting-description">Your website\'s default template.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_template" value="'.config('website_template',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Maintenance Page Url</strong>';
					echo '<p class="setting-description">Full URL address to your website\'s maintenance page. Visitors are redirected to your maintenance page when the website is disabled.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="maintenance_page" value="'.config('maintenance_page',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Server Name</strong>';
					echo '<p class="setting-description">Your Mu Online server name.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_name" value="'.config('server_name',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Website Title</strong>';
					echo '<p class="setting-description">Your website\'s title.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_title" value="'.config('website_title',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Meta Description</strong>';
					echo '<p class="setting-description">Define a description of your server.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_meta_description" value="'.config('website_meta_description',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Meta Keywords</strong>';
					echo '<p class="setting-description">Define keywords for search engines.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_meta_keywords" value="'.config('website_meta_keywords',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Forum Link</strong>';
					echo '<p class="setting-description">Full URL to your forum.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="website_forum_link" value="'.config('website_forum_link',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Server Files</strong>';
					echo '<p class="setting-description">Define your server files for maximum WebEngine compatibility.</p>';
				echo '</td>';
				echo '<td>';
					
					echo '<select class="form-control" name="server_files">';
						$fileCompatibilityList = $webengine['file_compatibility'];
						if(is_array($fileCompatibilityList)) {
							foreach($fileCompatibilityList as $value => $fileCompatibilityInfo) {
								echo '<option value="'.$value.'" '.(strtolower(config('server_files',true)) == $value ? 'selected' : '').'>'.$fileCompatibilityInfo['name'].'</option>';
							}
						}
					echo '</select>';
					
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Language Switching</strong>';
					echo '<p class="setting-description">Enables/disables the language switching system.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="language_switch_active" value="1" '.(config('language_switch_active',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="language_switch_active" value="0" '.(!config('language_switch_active',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Default Langage</strong>';
					echo '<p class="setting-description">Default language that WebEngine will use.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="language_default" value="'.config('language_default',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Language Debug</strong>';
					echo '<p class="setting-description">Enables/disabled language debugging. If enabled, language phrases will be shown in a hover-tip when poiting text with the mouse. Keep disabled in a live website.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="language_debug" value="1" '.(config('language_debug',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="language_debug" value="0" '.(!config('language_debug',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Plugin System Status</strong>';
					echo '<p class="setting-description">Enables/disables the plugin system.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="plugins_system_enable" value="1" '.(config('plugins_system_enable',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="plugins_system_enable" value="0" '.(!config('plugins_system_enable',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>IP Block System Status</strong>';
					echo '<p class="setting-description">Enables/disables the IP blocking system.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="ip_block_system_enable" value="1" '.(config('ip_block_system_enable',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="ip_block_system_enable" value="0" '.(!config('ip_block_system_enable',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Player Profile Links</strong>';
					echo '<p class="setting-description">If enabled, player names will have a link to their public profile.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="player_profiles" value="1" '.(config('player_profiles',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="player_profiles" value="0" '.(!config('player_profiles',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Guild Profile Links</strong>';
					echo '<p class="setting-description">If enabled, guild names will have a link to their public profile.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="guild_profiles" value="1" '.(config('guild_profiles',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="guild_profiles" value="0" '.(!config('guild_profiles',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Username Minimum Length</strong>';
					echo '<p class="setting-description"></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="username_min_len" value="'.config('username_min_len',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Username Maximum Length</strong>';
					echo '<p class="setting-description"></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="username_max_len" value="'.config('username_max_len',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Password Minimum Length</strong>';
					echo '<p class="setting-description"></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="password_min_len" value="'.config('password_min_len',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Password Maximum Length</strong>';
					echo '<p class="setting-description"></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="password_max_len" value="'.config('password_max_len',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Cron API</strong>';
					echo '<p class="setting-description">Enable/disable the cron api.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="cron_api" value="1" '.(config('cron_api',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="cron_api" value="0" '.(!config('cron_api',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Cron API Key</strong>';
					echo '<p class="setting-description"><br />Usage:<br />'.__BASE_URL__.'api/cron.php?key=<span style="color:red;">123456</span></p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="cron_api_key" value="'.config('cron_api_key',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Facebook Link</strong>';
					echo '<p class="setting-description">Link to your facebook page.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="social_link_facebook" value="'.config('social_link_facebook',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Instagram Link</strong>';
					echo '<p class="setting-description">Link to your instagram page.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="social_link_instagram" value="'.config('social_link_instagram',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Discord Link</strong>';
					echo '<p class="setting-description">Link to your discord invitation.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="social_link_discord" value="'.config('social_link_discord',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Server Info: Season</strong>';
					echo '<p class="setting-description">Leave empty to hide this information.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_info_season" value="'.config('server_info_season',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Server Info: Experience</strong>';
					echo '<p class="setting-description">Leave empty to hide this information.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_info_exp" value="'.config('server_info_exp',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Server Info: Master Experience</strong>';
					echo '<p class="setting-description">Leave empty to hide this information.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_info_masterexp" value="'.config('server_info_masterexp',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Server Info: Drop</strong>';
					echo '<p class="setting-description">Leave empty to hide this information.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="server_info_drop" value="'.config('server_info_drop',true).'">';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Maximum Online Players</strong>';
					echo '<p class="setting-description">Maximum amount of players that your server may allow. Leave empty to hide this information.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="maximum_online" value="'.config('maximum_online',true).'">';
				echo '</td>';
			echo '</tr>';
			
		echo '</table>';
		
		echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-success">Save Settings</button>';
	echo '</form>';
echo '</div>';