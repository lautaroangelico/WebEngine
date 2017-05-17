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

echo '<h1 class="page-header">Website Settings</h1>';

$allowedSettings = array(
	'settings_submit', # the submit button
	'system_active',
	'error_reporting',
	'website_template',
	'encryption_hash',
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
	'gmark_bin2hex_enable',
	'plugins_system_enable',
	'ip_block_system_enable',
	'flood_check_enable',
	'flood_actions_per_minute',
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
		
		# encryption hash
		if(!check_value($_POST['encryption_hash'])) throw new Exception('Invalid Encryption Hash setting.');
		if(!in_array(strlen($_POST['encryption_hash']), array(16,24,32))) throw new Exception('Invalid Encryption Hash length (needs to be 16, 24 or 32 characters long).');
		$setting['encryption_hash'] = $_POST['encryption_hash'];
		
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
		if(!in_array($_POST['server_files'], array('MUE', 'IGCN'))) throw new Exception('Invalid Server Files setting.');
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
		
		# guild mark bin2hex
		if(!check_value($_POST['gmark_bin2hex_enable'])) throw new Exception('Invalid Guild Logo setting.');
		if(!in_array($_POST['gmark_bin2hex_enable'], array(0, 1))) throw new Exception('Invalid Guild Logo setting.');
		$setting['gmark_bin2hex_enable'] = ($_POST['gmark_bin2hex_enable'] == 1 ? true : false);
		
		# plugin system
		if(!check_value($_POST['plugins_system_enable'])) throw new Exception('Invalid Plugin System setting.');
		if(!in_array($_POST['plugins_system_enable'], array(0, 1))) throw new Exception('Invalid Plugin System setting.');
		$setting['plugins_system_enable'] = ($_POST['plugins_system_enable'] == 1 ? true : false);
		
		# ip block system
		if(!check_value($_POST['ip_block_system_enable'])) throw new Exception('Invalid IP Block System setting.');
		if(!in_array($_POST['ip_block_system_enable'], array(0, 1))) throw new Exception('Invalid IP Block System setting.');
		$setting['ip_block_system_enable'] = ($_POST['ip_block_system_enable'] == 1 ? true : false);
		
		# anti-flood system
		if(!check_value($_POST['flood_check_enable'])) throw new Exception('Invalid Anti-Flood System setting.');
		if(!in_array($_POST['flood_check_enable'], array(0, 1))) throw new Exception('Invalid Anti-Flood System setting.');
		$setting['flood_check_enable'] = ($_POST['flood_check_enable'] == 1 ? true : false);
		
		# anti-flood system
		if(!check_value($_POST['flood_actions_per_minute'])) throw new Exception('Invalid Anti-Flood Actions Per Minute setting.');
		if(!Validator::UnsignedNumber($_POST['flood_actions_per_minute'])) throw new Exception('Invalid Anti-Flood Actions Per Minute setting.');
		$setting['flood_actions_per_minute'] = $_POST['flood_actions_per_minute'];
		
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
					echo '<strong>Error Reporting</strong>';
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
					echo '<strong>Encryption Hash</strong>';
					echo '<p class="setting-description">This is a private key used for encrypting sensitive data. The key needs to be an alpha-numeric string of 16, 24 or 32 characters long.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="text" class="form-control" name="encryption_hash" value="'.config('encryption_hash',true).'" required>';
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
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="server_files" value="IGCN" '.(config('server_files',true) == 'IGCN' ? 'checked' : null).'>';
							echo 'IGCN';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="server_files" value="MUE" '.(config('server_files',true) == 'MUE' ? 'checked' : null).'>';
							echo 'MUE';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Language System Status</strong>';
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
					echo '<strong>Guild Logo bin2hex</strong>';
					echo '<p class="setting-description">Try enabling/disabling this setting if your guild logos are not being displayed correctly.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="gmark_bin2hex_enable" value="1" '.(config('gmark_bin2hex_enable',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="gmark_bin2hex_enable" value="0" '.(!config('gmark_bin2hex_enable',true) ? 'checked' : null).'>';
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
					echo '<strong>Anti-Flood Check Status</strong>';
					echo '<p class="setting-description">Enables/disables the anti-flood system.</p>';
				echo '</td>';
				echo '<td>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="flood_check_enable" value="1" '.(config('flood_check_enable',true) ? 'checked' : null).'>';
							echo 'Enabled';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="flood_check_enable" value="0" '.(!config('flood_check_enable',true) ? 'checked' : null).'>';
							echo 'Disabled';
						echo '</label>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td>';
					echo '<strong>Anti-Flood Actions Per Minute</strong>';
					echo '<p class="setting-description">Maximum actions a user can perform during a minute before being blocked.</p>';
				echo '</td>';
				echo '<td>';
					echo '<input type="number" class="form-control" name="flood_actions_per_minute" value="'.config('flood_actions_per_minute',true).'" required>';
				echo '</td>';
			echo '</tr>';
			
			
		echo '</table>';
		
		echo '<button type="submit" name="settings_submit" value="ok" class="btn btn-success">Save Settings</button>';
	echo '</form>';
echo '</div>';