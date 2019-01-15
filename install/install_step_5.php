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

if(!defined('access') or !access or access != 'install') die();
?>
<h3>Website Configuration</h3>
<br />
<?php
if(check_value($_POST['install_step_5_submit'])) {
	try {
		# check for empty values
		if(!check_value($_POST['install_step_5_1'])) throw new Exception('You must complete all required fields.');
		if(!check_value($_POST['install_step_5_2'])) throw new Exception('You must complete all required fields.');
		if(!check_value($_POST['install_step_5_3'])) throw new Exception('You must complete all required fields.');
		if(!check_value($_POST['install_step_5_4'])) throw new Exception('You must complete all required fields.');
		if(!check_value($_POST['install_step_5_5'])) throw new Exception('You must complete all required fields.');
		if(!check_value($_POST['install_step_5_7'])) throw new Exception('You must complete all required fields.');
		if(!check_value($_POST['install_step_5_8'])) throw new Exception('You must complete all required fields.');
		
		# check admin user
		if(!Validator::AlphaNumeric($_POST['install_step_5_1'])) throw new Exception('The admin account username can only contain alpha-numeric characters.');
		if(!Validator::UsernameLength($_POST['install_step_5_1'])) throw new Exception('The admin account username length is not valid.');
		
		# check database connection data
		if(!check_value($_SESSION['install_sql_host'])) throw new Exception('Database connection info missing, restart installation process.');
		if(!check_value($_SESSION['install_sql_db1'])) throw new Exception('Database connection info missing, restart installation process.');
		if(!check_value($_SESSION['install_sql_user'])) throw new Exception('Database connection info missing, restart installation process.');
		if(!check_value($_SESSION['install_sql_pass'])) throw new Exception('Database connection info missing, restart installation process.');
		if(!check_value($_SESSION['install_sql_dsn'])) throw new Exception('Database connection info missing, restart installation process.');
		
		# check for valid server files
		if(!in_array($_POST['install_step_5_7'], $install['SERVER_FILES'])) throw new Exception('The server files selected is not a valid option.');
		
		# generate encryption hash
		$encryptionHash = substr(md5('WebEngine' . time()), 0, 16);
		$forumUrl = (check_value($_POST['install_step_5_2']) ? $_POST['install_step_5_2'] : 'https://forum.webenginecms.org/');
		$serverName = (check_value($_POST['install_step_5_8']) ? $_POST['install_step_5_8'] : 'MuOnline');
		$websiteTitle = (check_value($_POST['install_step_5_3']) ? $_POST['install_step_5_3'] : 'Mu Online');
		$websiteDescription = (check_value($_POST['install_step_5_4']) ? $_POST['install_step_5_4'] : '');
		$websiteKeywords = (check_value($_POST['install_step_5_5']) ? $_POST['install_step_5_5'] : '');
		$serverFiles = $_POST['install_step_5_7'];
		$websiteAdmin = array($_POST['install_step_5_1'] => 100);

		$websiteConfig = array(
			'system_active' => true,
			'error_reporting' => false,
			'website_template' => 'redzone',
			'encryption_hash' => $encryptionHash,
			'maintenance_page' => $forumUrl,
			'admins' => $websiteAdmin,
			'admincp_modules_access' => array(
				'addnews' => 100,
				'managenews' => 100,
				'searchaccount' => 100,
				'accountsfromip' => 100,
				'onlineaccounts' => 100,
				'newregistrations' => 100,
				'accountinfo' => 100,
				'editaccountpassword' => 100,
				'editaccountemail' => 100,
				'searchcharacter' => 100,
				'editcharacter' => 100,
				'searchban' => 100,
				'banaccount' => 100,
				'latestbans' => 100,
				'blockedips' => 100,
				'creditsconfigs' => 100,
				'creditsmanager' => 100,
				'latestpaypal' => 100,
				'latestsr' => 100,
				'latestps' => 100,
				'topvotes' => 100,
				'modules_manager' => 100,
				'plugins' => 100,
				'plugin_install' => 100,
				'addcron' => 100,
				'managecron' => 100,
				'connection_settings' => 100,
				'website_settings' => 100,
				'admincp_settings' => 100
			),
			'server_name' => $serverName,
			'website_title' => $websiteTitle,
			'website_meta_description' => $websiteDescription,
			'website_meta_keywords' => $websiteKeywords,
			'website_forum_link' => $forumUrl,
			'SQL_DB_HOST' => $_SESSION['install_sql_host'],
			'SQL_DB_NAME' => (check_value($_SESSION['install_sql_db1']) ? $_SESSION['install_sql_db1'] : 'MuOnline'),
			'SQL_DB_2_NAME' => (check_value($_SESSION['install_sql_db2']) ? $_SESSION['install_sql_db2'] : 'Me_MuOnline'),
			'SQL_DB_USER' => (check_value($_SESSION['install_sql_user']) ? $_SESSION['install_sql_user'] : 'sa'),
			'SQL_DB_PASS' => $_SESSION['install_sql_pass'],
			'SQL_DB_PORT' => (check_value($_SESSION['install_sql_port']) ? $_SESSION['install_sql_port'] : '1433'),
			'SQL_USE_2_DB' => (check_value($_SESSION['install_sql_db2']) ? true : false),
			'SQL_PDO_DRIVER' => (check_value($_SESSION['install_sql_dsn']) ? $_SESSION['install_sql_dsn'] : 1),
			'SQL_ENABLE_MD5' => ($_SESSION['install_sql_md5'] == 1 ? true : false),
			'server_files' => $serverFiles,
			'language_switch_active' => false,
			'language_default' => 'en',
			'language_debug' => false,
			'gmark_bin2hex_enable' => true,
			'plugins_system_enable' => false,
			'ip_block_system_enable' => false,
			'flood_check_enable' => false,
			'flood_actions_per_minute' => 60
		);
		
		# check if configuration file exists
		if(!file_exists(__PATH_CONFIGS__.'webengine.json')) throw new Exception('The configuration file is missing, re-upload WebEngine files.');
		
		# check if configuration file is writable
		if(!is_writable(__PATH_CONFIGS__.'webengine.json')) throw new Exception('The configuration file is not writable, chmod webengine.json to 0777 permissions.');
		
		# encode settings
		$webengineConfigs = json_encode($websiteConfig, JSON_PRETTY_PRINT);
		
		# save configurations
		$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
		if(!$cfgFile) throw new Exception('There was a problem opening the configuration file.');
		
		fwrite($cfgFile, $webengineConfigs);
		fclose($cfgFile);
		
		# clear session data
		$_SESSION = array();
		session_destroy();
		
		# redirect to website home
		header('Location: ' . __BASE_URL__);
		die();
		
	} catch (Exception $ex) {
		echo '<div class="alert alert-danger" role="alert">'.$ex->getMessage().'</div>';
	}
}
?>
<form class="form-horizontal" method="post">
	<div class="form-group">
		<label for="input_1" class="col-sm-3 control-label">Admin account</label>
		<div class="col-sm-9">
			<input type="text" name="install_step_5_1" class="form-control" id="input_1" required>
			<p class="help-block">Type the username of the account that will have full admincp access..</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_2" class="col-sm-3 control-label">Forum URL</label>
		<div class="col-sm-9">
			<input type="text" name="install_step_5_2" class="form-control" id="input_2" value="http://forum.muengine.net/" required>
			<p class="help-block">Full URL to your server's forum.</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_9" class="col-sm-3 control-label">Server Name</label>
		<div class="col-sm-9">
			<input type="text" name="install_step_5_8" class="form-control" id="input_9" value="MuOnline" required>
			<p class="help-block">Type your server name.</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_3" class="col-sm-3 control-label">Website Title</label>
		<div class="col-sm-9">
			<input type="text" name="install_step_5_3" class="form-control" id="input_3" value="Welcome to MuOnline Season X" required>
			<p class="help-block">Type the desired website title.</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_4" class="col-sm-3 control-label">Website Description</label>
		<div class="col-sm-9">
			<input type="text" name="install_step_5_4" class="form-control" id="input_4" value="Join MU Online! the free-to-play fantasy RPG based on the legendary Continent of MU! Feel the power of forbidden magic! Explore and fight!" required>
			<p class="help-block">A short description of your website. This is for search engines such as Google, Bing, etc...</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_5" class="col-sm-3 control-label">Website Keywords</label>
		<div class="col-sm-9">
			<input type="text" name="install_step_5_5" class="form-control" id="input_5" value="MU, MU online, Season10, private server, Battle Core, private MU online, free to play, mmorpg, auto play, free rpg, Free2Play, PVP, multi client and jewels, season X" required>
			<p class="help-block">Keywords that identify your website/server. This is for search engines such as Google, Bing, etc...</p>
		</div>
	</div>
	<div class="form-group">
		<label for="input_7" class="col-sm-3 control-label">Server Files</label>
		<div class="col-sm-9">
			<div class="radio">
				<label>
					<input type="radio" name="install_step_5_7" name="optionsRadios" id="input_7" value="MUE">
					MuEngine
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="install_step_5_7" name="optionsRadios" id="input_8" value="IGCN" checked="checked">
					IGCN
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="install_step_5_7" name="optionsRadios" id="input_10" value="CUSTOM">
					CUSTOM
				</label>
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" name="install_step_5_submit" value="continue" class="btn btn-success">Complete Installation</button>
		</div>
	</div>
</form>