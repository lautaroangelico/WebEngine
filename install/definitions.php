<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.3.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2021 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!defined('access') or !access or access != 'install') die();

/**
 * INSTALLER_VERSION
 */
define('INSTALLER_VERSION', '1.3.0');

/**
 * WEBENGINE_CONFIGURATION_FILE
 */
define('WEBENGINE_CONFIGURATION_FILE', 'webengine.json');

/**
 * WEBENGINE_WRITABLE_PATHS_FILE
 */
define('WEBENGINE_WRITABLE_PATHS_FILE', 'writable.paths.json');

/**
 * WEBENGINE_DEFAULT_CONFIGURATION_FILE
 */
define('WEBENGINE_DEFAULT_CONFIGURATION_FILE', 'webengine.json.default');

$install['PDO_DSN'] = array(
	1 => 'dblib',
	2 => 'sqlsrv',
	3 => 'odbc',
);

$install['step_list'] = array(
	array('install_intro.php', 'Intro'),
	array('install_step_1.php', 'Web Server Requirements'),
	array('install_step_2.php', 'Database Connection'),
	array('install_step_3.php', 'Website Configuration'),
);