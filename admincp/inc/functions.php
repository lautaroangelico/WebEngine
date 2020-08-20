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

function getDownloadsList() {
	$db = config('SQL_USE_2_DB',true) ? Connection::Database('Me_MuOnline') : Connection::Database('MuOnline');
	$result = $db->query_fetch("SELECT * FROM ".WEBENGINE_DOWNLOADS." ORDER BY download_type ASC, download_id ASC");
	if(!is_array($result)) return;
	return $result;
}

function addDownload($title, $description='', $link, $size=0, $type=1) {
	$db = config('SQL_USE_2_DB',true) ? Connection::Database('Me_MuOnline') : Connection::Database('MuOnline');
	if(!check_value($title)) return;
	if(!check_value($link)) return;
	if(!check_value($size)) return;
	if(!check_value($type)) return;
	if(strlen($title) > 100) return;
	if(strlen($description) > 100) return;
	
	$result = $db->query("INSERT INTO ".WEBENGINE_DOWNLOADS." (download_title, download_description, download_link, download_size, download_type) VALUES (?, ?, ?, ?, ?)", array($title, $description, $link, $size, $type));
	if(!$result) return;
	
	@updateDownloadsCache();
	return true;
}

function editDownload($id, $title, $description='', $link, $size=0, $type=1) {
	$db = config('SQL_USE_2_DB',true) ? Connection::Database('Me_MuOnline') : Connection::Database('MuOnline');
	if(!check_value($id)) return;
	if(!check_value($title)) return;
	if(!check_value($link)) return;
	if(!check_value($size)) return;
	if(!check_value($type)) return;
	if(strlen($title) > 100) return;
	if(strlen($description) > 100) return;
	
	$result = $db->query("UPDATE ".WEBENGINE_DOWNLOADS." SET download_title = ?, download_description = ?, download_link = ?, download_size = ?, download_type = ? WHERE download_id = ?", array($title, $description, $link, $size, $type, $id));
	if(!$result) return;
	
	@updateDownloadsCache();
	return true;
}

function deleteDownload($id) {
	$db = config('SQL_USE_2_DB',true) ? Connection::Database('Me_MuOnline') : Connection::Database('MuOnline');
	if(!check_value($id)) return;
	$result = $db->query("DELETE FROM ".WEBENGINE_DOWNLOADS." WHERE download_id = ?", array($id));
	if(!$result) return;
	
	@updateDownloadsCache();
	return true;
}

function updateDownloadsCache() {
	$db = config('SQL_USE_2_DB',true) ? Connection::Database('Me_MuOnline') : Connection::Database('MuOnline');
	$downloadsData = $db->query_fetch("SELECT * FROM ".WEBENGINE_DOWNLOADS." ORDER BY download_type ASC, download_id ASC");
	$cacheData = encodeCache($downloadsData);
	updateCacheFile('downloads.cache', $cacheData);
	return true;
}

function weekDaySelectOptions($selected='Monday') {
	$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	foreach($days as $row) {
		if($selected == $row) {
			$result .= '<option value="'.$row.'" selected>'.$row.'</option>';
		} else {
			$result .= '<option value="'.$row.'">'.$row.'</option>';
		}
	}
	return $result;
}