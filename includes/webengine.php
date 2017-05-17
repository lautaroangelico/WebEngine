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

//session_name('WebEngine109'); # session name (change to your server name and uncomment)
//session_set_cookie_params(0, '/', 'muonline.com'); # same session with and without www protocol (edit with your domain and uncomment)
session_start();
ob_start();

# ArcticEngine Version
define('__WEBENGINE_VERSION__', '1.0.9');

# Set Encoding
@ini_set('default_charset', 'utf-8');

# Server Time
//date_default_timezone_set('America/Los_Angeles');

# Global Paths
define('HTTP_HOST', $_SERVER['HTTP_HOST']);
define('SERVER_PROTOCOL', (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://');
define('__ROOT_DIR__', str_replace('\\','/',dirname(dirname(__FILE__))).'/'); // /home/user/public_html/
define('__RELATIVE_ROOT__', str_ireplace(rtrim(str_replace('\\','/', realpath(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']))), '/'), '', __ROOT_DIR__));// /
define('__BASE_URL__', SERVER_PROTOCOL.HTTP_HOST.__RELATIVE_ROOT__); // http(s)://www.mysite.com/

# Private Paths
define('__PATH_INCLUDES__', __ROOT_DIR__.'includes/');
define('__PATH_TEMPLATES__', __ROOT_DIR__.'templates/');
define('__PATH_LANGUAGES__', __PATH_INCLUDES__ . 'languages/');
define('__PATH_CLASSES__', __PATH_INCLUDES__.'classes/');
define('__PATH_FUNCTIONS__', __PATH_INCLUDES__.'functions/');
define('__PATH_MODULES__', __ROOT_DIR__.'modules/');
define('__PATH_MODULES_USERCP__', __PATH_MODULES__.'usercp/');
define('__PATH_EMAILS__', __PATH_INCLUDES__.'emails/');
define('__PATH_CACHE__', __PATH_INCLUDES__.'cache/');
define('__PATH_ADMINCP__', __ROOT_DIR__.'admincp/');
define('__PATH_ADMINCP_INC__', __ROOT_DIR__.'admincp/inc/');
define('__PATH_ADMINCP_MODULES__', __ROOT_DIR__.'admincp/modules/');
define('__PATH_NEWS_CACHE__', __PATH_CACHE__.'news/');
define('__PATH_PLUGINS__', __PATH_INCLUDES__.'plugins/');
define('__PATH_CONFIGS__', __PATH_INCLUDES__.'config/');
define('__PATH_MODULE_CONFIGS__', __PATH_CONFIGS__.'modules/');
define('__PATH_CRON__', __PATH_INCLUDES__.'cron/');

# Public Paths
define('__PATH_MODULES_RANKINGS__', __BASE_URL__.'rankings/');
define('__PATH_ADMINCP_HOME__', __BASE_URL__.'admincp/');

# Load Libraries
if(!@include_once(__PATH_CLASSES__ . 'class.database.php')) throw new Exception('Could not load class (database).');
if(!@include_once(__PATH_CLASSES__ . 'class.common.php')) throw new Exception('Could not load class (common).');
if(!@include_once(__PATH_CLASSES__ . 'class.handler.php')) throw new Exception('Could not load class (handler).');
if(!@include_once(__PATH_CLASSES__ . 'class.validator.php')) throw new Exception('Could not load class (validator).');
if(!@include_once(__PATH_CLASSES__ . 'class.login.php')) throw new Exception('Could not load class (login).');
if(!@include_once(__PATH_CLASSES__ . 'class.vote.php')) throw new Exception('Could not load class (vote).');
if(!@include_once(__PATH_CLASSES__ . 'class.character.php')) throw new Exception('Could not load class (character).');
if(!@include_once(__PATH_CLASSES__ . 'phpmailer/PHPMailerAutoload.php')) throw new Exception('Could not load class (phpmailer).');
if(!@include_once(__PATH_CLASSES__ . 'class.rankings.php')) throw new Exception('Could not load class (rankings).');
if(!@include_once(__PATH_CLASSES__ . 'class.news.php')) throw new Exception('Could not load class (news).');
if(!@include_once(__PATH_CLASSES__ . 'class.plugins.php')) throw new Exception('Could not load class (plugins).');
if(!@include_once(__PATH_CLASSES__ . 'class.profiles.php')) throw new Exception('Could not load class (profiles).');
if(!@include_once(__PATH_CLASSES__ . 'class.credits.php')) throw new Exception('Could not load class (credits).');
if(!@include_once(__PATH_CLASSES__ . 'class.email.php')) throw new Exception('Could not load class (email).');
if(!@include_once(__PATH_CLASSES__ . 'class.account.php')) throw new Exception('Could not load class (email).');

# Load Functions
if(!@include_once(__PATH_INCLUDES__ . 'functions.php')) throw new Exception('Could not load functions.');

# WebEngine Configurations
$config = webengineConfigs();

# File-Dependant Libraries
	
	# MUE
	if(strtolower($config['server_files']) == 'mue') {
		if(!@include_once(__PATH_CLASSES__ . 'class.vip.mue.php')) throw new Exception('Could not load class (vip.mue).');
	}
	# IGCN
	if(strtolower($config['server_files']) == 'igcn') {
		if(!@include_once(__PATH_CLASSES__ . 'class.vip.igcn.php')) throw new Exception('Could not load class (vip.igcn).');
	}

# Configurations Check
$checkConfigs = true;
if($checkConfigs) {
	
	# encryption hash
	if(!in_array(strlen($config['encryption_hash']), array(16,24,32))) throw new Exception('The encryption hash configuration must be an alphanumeric string of 16, 24 or 32 characters in length.');
	
	# default template
	if(!file_exists(__PATH_TEMPLATES__ . $config['website_template'])) throw new Exception('The default template doesn\'t exist.');
	
	# required configs
	if(!check_value($config['SQL_DB_HOST'])) throw new Exception('The database host configuration is required to connect to your database.');
	if(!check_value($config['SQL_DB_NAME'])) throw new Exception('The database name configuration is required to connect to your database.');
	if(!check_value($config['SQL_DB_USER'])) throw new Exception('The database user configuration is required to connect to your database.');
	if(!check_value($config['SQL_DB_PASS'])) throw new Exception('The database password configuration is required to connect to your database.');
	if(!check_value($config['SQL_DB_PORT'])) throw new Exception('The database port configuration is required to connect to your database.');
	if($config['SQL_USE_2_DB']) if(!check_value($config['SQL_DB_2_NAME'])) throw new Exception('The additional database name configuration is required to connect to your database.');
	if(!check_value($config['SQL_PDO_DRIVER'])) throw new Exception('The PDO driver configuration is required to connect to your database.');
	if(!check_value($config['server_files'])) throw new Exception('The server files name configuration is required by webengine.');
	if(!in_array($config['server_files'], array('MUE', 'IGCN'))) throw new Exception('The server files name configuration is not valid.');
}

# Load Table Definitions
if(!@include_once(__PATH_CONFIGS__ . strtolower($config['server_files']) . '.tables.php')) throw new Exception('Could not load the table definitions.');

# CMS Status
if(!$config['system_active']) {
	if(!array_key_exists($_SESSION['username'], $config['admins'])) {
		header('Location: ' . $config['maintenance_page']);
		die();
	}
	
	# show website status to the admin
	echo '<div style="text-align:center;border-bottom:1px solid #aa0000;padding:15px;background:#000;color:#ff0000;font-size:12pt;">';
		echo 'OFFLINE MODE';
	echo '</div>';
}

# Error Reporting
if($config['error_reporting']) {
	ini_set('display_errors', true);
	error_reporting(E_ALL & ~E_NOTICE);
} else {
	ini_set('display_errors', false);
	error_reporting(0);
}

# MuOnline Database Connection
$dB = new dB($config['SQL_DB_HOST'], $config['SQL_DB_PORT'], $config['SQL_DB_NAME'], $config['SQL_DB_USER'], $config['SQL_DB_PASS'], $config['SQL_PDO_DRIVER']);
if($dB->dead) {
	if(config('error_reporting',true)) {
		throw new Exception($dB->error);
	} else {
		throw new Exception('Website Offline');
	}
}

# Me_MuOnline Database Connection
if($config['SQL_USE_2_DB']) {
	$dB2 = new dB($config['SQL_DB_HOST'], $config['SQL_DB_PORT'], $config['SQL_DB_2_NAME'], $config['SQL_DB_USER'], $config['SQL_DB_PASS'], $config['SQL_PDO_DRIVER']);
	if($dB2->dead) {
		if(config('error_reporting',true)) {
			throw new Exception($dB2->error);
		} else {
			throw new Exception('Website Offline');
		}
	}
}

# Common Library Instance
$common = new common($dB, $dB2);

# IP Blocking System
if($config['ip_block_system_enable']) {
	if($common->isIpBlocked($_SERVER['REMOTE_ADDR'])) throw new Exception('Your IP address has been blocked.');
}

# Anti-flood System
if($config['flood_check_enable']) {
	if(!check_value($_SESSION['track_timestamp'])) {
		$_SESSION['track_timestamp'] = time();
		$_SESSION['track_actions'] = 0;
	}
	
	if(time() > $_SESSION['track_timestamp']+60) {
		$_SESSION['track_timestamp'] = time();
		$_SESSION['track_actions'] = 0;
	}
	
	if($_SESSION['track_actions'] >= $config['flood_actions_per_minute']) throw new Exception('Flood limit reached, please try again in a moment.');
	
	$_SESSION['track_actions'] += 1;
}

# Load Plugins
if($config['plugins_system_enable']) {
	$PluginsSys = new Plugins();
	if($PluginsSys->gotEnabledPlugins()) {
		$pluginsCACHE = LoadCacheData('plugins.cache');
		$pli = 0;
		foreach($pluginsCACHE as $thisPlugin) {
			if($pli >= 1) {
				$pPath = $PluginsSys->pluginPath($thisPlugin[0]);
				$pFiles = explode("|",$thisPlugin[1]);
				foreach($pFiles as $pFile) {
					if(!@include_once($pPath.$pFile)) throw new Exception('Could not load plugin file ('.$pPath.$pFile.').');
				}
			}
			$pli++;
		}
	}
}

# Template Paths
define('__PATH_TEMPLATE_ROOT__', __PATH_TEMPLATES__ . $config['website_template'] . '/');
define('__PATH_TEMPLATE__', __BASE_URL__ . 'templates/' . $config['website_template'] . '/');
define('__PATH_TEMPLATE_IMG__', __PATH_TEMPLATE__ . 'img/');
define('__PATH_TEMPLATE_CSS__', __PATH_TEMPLATE__ . 'css/');
define('__PATH_TEMPLATE_JS__', __PATH_TEMPLATE__ . 'js/');
define('__PATH_TEMPLATE_FONTS__', __PATH_TEMPLATE__ . 'fonts/');

# Handler Instance
$handler = new Handler($dB, $dB2);
$handler->loadPage();