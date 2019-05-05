<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

$configError = array();

$writablePaths = loadJsonFile(WEBENGINE_WRITABLE_PATHS);
if(!is_array($writablePaths)) throw new Exception('Could not load WebEngine CMS writable paths list.');

// File permission check
foreach($writablePaths as $thisPath) {
	if(file_exists(__PATH_INCLUDES__ . $thisPath)) {
		if(!is_writable(__PATH_INCLUDES__ . $thisPath)) {
			$configError[] = "<span style=\"color:#aaaaaa;\">[Permission Error]</span> " . $thisPath . " <span style=\"color:red;\">(file must be writable)</span>";
		}
	} else {
		$configError[] = "<span style=\"color:#aaaaaa;\">[Not Found]</span> " . $thisPath. " <span style=\"color:orange;\">(re-upload file)</span>";
	}
}

// Check cURL
if(!function_exists('curl_version')) $configError[] = "<span style=\"color:#aaaaaa;\">[PHP]</span> <span style=\"color:green;\">curl not loaded (WebEngine required cURL)</span>";

if(count($configError) >= 1) {
	throw new Exception("<strong>The following errors ocurred:</strong><br /><br />" . implode("<br />", $configError));
}