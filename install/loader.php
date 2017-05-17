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

if(!defined('access') or !access or access != 'install') die();

session_name('WebEngineInstaller109'); 
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
	
	if(file_exists(__PATH_CONFIGS__.'webengine.json')) {
		$checkConfigs = file_get_contents(__PATH_CONFIGS__ . 'webengine.json');
		if($checkConfigs) {
			$jsonConfigs = json_decode($checkConfigs, true);
			if($jsonConfigs['system_active']) throw new Exception('WebEngine installation is completed, delete the install folder.');
		}
	}
	
	if(!@include_once(__PATH_INCLUDES__ . 'functions.php')) throw new Exception('Could not load WebEngine functions.');
	if(!@include_once(__PATH_CLASSES__ . 'class.validator.php')) throw new Exception('Could not load validator library.');
	if(!@include_once(__PATH_CLASSES__ . 'class.database.php')) throw new Exception('Could not load database library.');
	if(!@include_once(__INSTALL_ROOT__ . 'definitions.php')) throw new Exception('');
	
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