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

if(!defined('access') or !access or access != 'install') die();

session_name('WebEngineInstaller121'); 
session_start();
ob_start();

@ini_set('default_charset', 'utf-8');

define('HTTP_HOST', $_SERVER['HTTP_HOST']);
define('SERVER_PROTOCOL', (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://');
define('__ROOT_DIR__', str_replace('\\','/',dirname(dirname(__FILE__))).'/');
define('__RELATIVE_ROOT__', str_ireplace(rtrim(str_replace('\\','/', realpath(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']))), '/'), '', __ROOT_DIR__));// /
define('__BASE_URL__', SERVER_PROTOCOL.HTTP_HOST.__RELATIVE_ROOT__);
define('__PATH_INCLUDES__', __ROOT_DIR__.'includes/');
define('__PATH_CLASSES__', __PATH_INCLUDES__.'classes/');
define('__PATH_CRON__', __PATH_INCLUDES__.'cron/');
define('__PATH_CONFIGS__', __PATH_INCLUDES__.'config/');
define('__INSTALL_ROOT__', __ROOT_DIR__ . 'install/');
define('__INSTALL_URL__', __BASE_URL__ . 'install/');

try {
	
	if(!@include_once(__PATH_CONFIGS__ . 'webengine.tables.php')) throw new Exception('Could not load WebEngine CMS tables.');
	if(!@include_once(__INSTALL_ROOT__ . 'definitions.php')) throw new Exception('Could not load WebEngine CMS Installer definitions.');
	
	$webengineConfigsPath = __PATH_CONFIGS__.WEBENGINE_CONFIGURATION_FILE;
	if(!file_exists($webengineConfigsPath)) throw new Exception('WebEngine CMS configuration file missing.');
	if(!is_readable($webengineConfigsPath)) throw new Exception('WebEngine CMS configuration file is not readable.');
	if(!is_writable($webengineConfigsPath)) throw new Exception('WebEngine CMS configuration file is not writable.');
	
	$webengineConfigsFile = file_get_contents($webengineConfigsPath);
	if($webengineConfigsFile) {
		$webengineConfig = json_decode($webengineConfigsFile, true);
		if(!is_array($webengineConfig)) throw new Exception('WebEngine CMS configuration file could not be decoded.');
		if($webengineConfig['webengine_cms_installed'] === true) throw new Exception('WebEngine CMS installation is complete, it is recommended to rename or delete this directory.');
	}
	
	$webengineDefaultConfigsPath = __PATH_CONFIGS__.WEBENGINE_DEFAULT_CONFIGURATION_FILE;
	if(!file_exists($webengineDefaultConfigsPath)) throw new Exception('WebEngine CMS default configuration file missing.');
	if(!is_readable($webengineDefaultConfigsPath)) throw new Exception('WebEngine CMS default configuration file is not readable.');
	$webengineDefaultConfigsFile = file_get_contents($webengineDefaultConfigsPath);
	if(!$webengineDefaultConfigsFile) throw new Exception('WebEngine CMS default configuration file could not be loaded.');
	$webengineDefaultConfig = json_decode($webengineDefaultConfigsFile, true);
	if(!is_array($webengineDefaultConfig)) throw new Exception('WebEngine CMS default configuration file could not be decoded.');
	
	if(!@include_once(__PATH_INCLUDES__ . 'functions.php')) throw new Exception('Could not load WebEngine CMS functions.');
	if(!@include_once(__PATH_CLASSES__ . 'class.validator.php')) throw new Exception('Could not load WebEngine CMS validator library.');
	if(!@include_once(__PATH_CLASSES__ . 'class.database.php')) throw new Exception('Could not load WebEngine CMS database library.');
	if(!@include_once(__PATH_CONFIGS__ . 'compatibility.php')) throw new Exception('Could not load WebEngine CMS files compatibility.');
	if(!@include_once(__PATH_CONFIGS__ . 'timezone.php')) throw new Exception('Could not load WebEngine CMS timezone.');
	
	$writablePaths = loadJsonFile(__PATH_CONFIGS__.WEBENGINE_WRITABLE_PATHS_FILE);
	if(!is_array($writablePaths)) throw new Exception('Could not load WebEngine CMS writable paths list.');
	
	if(!check_value($_SESSION['install_cstep'])) {
		$_SESSION['install_cstep'] = 0;
	}

	function stepListSidebar() {
		global $install;
		if(is_array($install['step_list'])) {
			echo '<ul class="list-group">';
			foreach($install['step_list'] as $key => $row) {
				if($key == $_SESSION['install_cstep']) {
					echo '<li class="list-group-item active">'.$row[1].'</li>';
					continue;
				}
				echo '<li class="list-group-item">'.$row[1].'</li>';
			}
			echo '</ul>';
		}
		if($_SESSION['install_cstep'] > 0) {
			echo '<a href="?action=restart" class="btn btn-danger">Start Over</a>';
		}
	}

	if(check_value($_GET['action'])) {
		if($_GET['action'] == 'restart') {
			# restart install process
			$_SESSION = array();
			session_destroy();
			header('Location: install.php');
			die();
		}
	}
	
} catch (Exception $ex) {
	die($ex->getMessage());
}