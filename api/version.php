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

include('../includes/webengine.php');

$apacheVersion = (function_exists(apache_get_version) ? apache_get_version() : 'Unknown');
$phpVersion = phpversion();
$webengineVersion = __WEBENGINE_VERSION__;

echo json_encode(array('apache' => $apacheVersion, 'php' => $phpVersion, 'webengine' => $webengineVersion), JSON_PRETTY_PRINT);