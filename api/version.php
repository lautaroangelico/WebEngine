<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.0.9.9
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2013-2018 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * https://opensource.org/licenses/MIT
 */

define('access', 'api');

include('../includes/webengine.php');

$apacheVersion = (function_exists(apache_get_version) ? apache_get_version() : 'Unknown');
$phpVersion = phpversion();
$webengineVersion = __WEBENGINE_VERSION__;

echo json_encode(array('apache' => $apacheVersion, 'php' => $phpVersion, 'webengine' => $webengineVersion), JSON_PRETTY_PRINT);