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

class CacheManager {
	
	protected $_protectedCacheFiles = array(
		'plugins.cache',
		'blocked_ip.cache',
		'downloads.cache',
		'news.cache',
		'.htaccess',
		'.gitignore',
	);
	protected $_jsonArrayFiles = array(
		'castle_siege.cache',
		'character_country.cache',
		'online_characters.cache',
	);
	protected $_file;

	
	public function setFile($file) {
		$this->_file = $file;
	}
	
	public function getCacheFileListAndData($type='') {
		switch($type) {
			case 'guild':
				$cacheFiles = $this->_getGuildProfileCacheFileList();
				$basePath = __PATH_GUILD_PROFILES_CACHE__;
				break;
			case 'player':
				$cacheFiles = $this->_getPlayerProfileCacheFileList();
				$basePath = __PATH_PLAYER_PROFILES_CACHE__;
				break;
			default:
				$cacheFiles = $this->_getCacheFileList();
				$basePath = __PATH_CACHE__;
		}
		if(!is_array($cacheFiles)) return;
		foreach($cacheFiles as $row) {
			$filePath = $basePath . $row;
			$fileSize = filesize($filePath);
			$lastModification = date('Y/m/d H:i A', filemtime($filePath));
			$writable = is_writable($filePath);
			$result[] = array(
				'file' => $row,
				'size' => $fileSize,
				'edit' => $lastModification,
				'write' => $writable
			);
		}
		return $result;
	}
	
	public function clearCacheData() {
		if(!check_value($this->_file)) return;
		if(!in_array($this->_file, $this->_getCacheFileList())) throw new Exception('The requested cache file is not valid.');
		$filePath = __PATH_CACHE__ . $this->_file;
		$fileData = '';
		if($this->_isJsonArrayFile($this->_file)) {
			$fileData = '[]';
		}
		$fp = fopen($filePath, 'w');
		if(!$fp) throw new Exception('The cache file could not be open.');
		fwrite($fp, $fileData);
		fclose($fp);
	}
	
	public function deleteGuildCache() {
		$fileList = $this->_getGuildProfileCacheFileList();
		if(!is_array($fileList)) return;
		foreach($fileList as $row) {
			unlink(__PATH_GUILD_PROFILES_CACHE__ . $row);
		}
	}
	
	public function deletePlayerCache() {
		$fileList = $this->_getPlayerProfileCacheFileList();
		if(!is_array($fileList)) return;
		foreach($fileList as $row) {
			unlink(__PATH_PLAYER_PROFILES_CACHE__ . $row);
		}
	}
	
	protected function _getCacheFileList() {
		$dir = opendir(__PATH_CACHE__);
		while(($file = readdir($dir)) !== false) {
			if(filetype(__PATH_CACHE__.$file) == "file" && !$this->_isProtected($file)) {
				$result[] = $file;
			}
		}
		closedir($dir);
		return $result;
	}
	
	protected function _getGuildProfileCacheFileList() {
		$dir = opendir(__PATH_GUILD_PROFILES_CACHE__);
		while(($file = readdir($dir)) !== false) {
			if(filetype(__PATH_GUILD_PROFILES_CACHE__.$file) == "file" && !$this->_isProtected($file)) {
				$result[] = $file;
			}
		}
		closedir($dir);
		return $result;
	}
	
	protected function _getPlayerProfileCacheFileList() {
		$dir = opendir(__PATH_PLAYER_PROFILES_CACHE__);
		while(($file = readdir($dir)) !== false) {
			if(filetype(__PATH_PLAYER_PROFILES_CACHE__.$file) == "file" && !$this->_isProtected($file)) {
				$result[] = $file;
			}
		}
		closedir($dir);
		return $result;
	}
	
	protected function _isProtected($file) {
		if(in_array($file, $this->_protectedCacheFiles)) return true;
		return false;
	}
	
	protected function _isJsonArrayFile($file) {
		if(in_array($file, $this->_jsonArrayFiles)) return true;
		return false;
	}
}