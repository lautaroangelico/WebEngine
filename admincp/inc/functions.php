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

function admincp_base($module="") {
	if(check_value($module)) return __PATH_ADMINCP_HOME__ . "?module=" . $module;
	return __PATH_ADMINCP_HOME__;;
}

function enabledisableCheckboxes($name,$checked,$e_txt,$d_txt) {
	echo '<div class="radio">';
	echo '<label class="radio">';
	if($checked == 1) {
		echo '<input type="radio" name="'.$name.'" value="1" checked>';
	} else {
		echo '<input type="radio" name="'.$name.'" value="1">';
	}
	echo $e_txt;
	echo '</label>';
	echo '<label class="radio">';
	if($checked == 0) {
		echo '<input type="radio" name="'.$name.'" value="0" checked>';
	} else {
		echo '<input type="radio" name="'.$name.'" value="0">';
	}
	echo $d_txt;
	echo '</label>';
	echo '</div>';
}

function tableExists($table_name, $db) {
	$tableExists = $db->query_fetch_single("SELECT * FROM sysobjects WHERE xtype = 'U' AND name = ?", array($table_name));
	if(!$tableExists) return false;
	return true;
}

function checkVersion() {
	$url = 'http://version.webenginecms.org/1.0/index.php';
	
	$fields = array(
		'version' => urlencode(__WEBENGINE_VERSION__),
		'baseurl' => urlencode(__BASE_URL__),
	);
	
	foreach($fields as $key => $value) {
		$fieldsArray[] = $key . '=' . $value;
	}
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $fieldsArray));
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'WebEngine');
	curl_setopt($ch, CURLOPT_HEADER, false);

	$result = curl_exec($ch);
	curl_close($ch);
	
	if(!$result) return;
	$resultArray = json_decode($result, true);
	if($resultArray['update']) return true;
	return;
}