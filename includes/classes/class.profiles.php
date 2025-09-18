<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class weProfiles {
	
	private $_request;
	private $_type;
	
	private $_reqMaxLen;
	private $_guildsCachePath;
	private $_playersCachePath;
	private $_cacheUpdateTime;
	
	private $_fileData;
	
	protected $common;
	protected $dB;
	protected $cfg;
	
	function __construct() {
		
		# database
		$this->common = new common();
		$this->dB = Connection::Database('MuOnline');
		
		# settings
		$this->_guildsCachePath = __PATH_CACHE__ . 'profiles/guilds/';
		$this->_playersCachePath = __PATH_CACHE__ . 'profiles/players/';
		$this->_cacheUpdateTime = 300;
		
		# check cache directories
		$this->checkCacheDir($this->_guildsCachePath);
		$this->checkCacheDir($this->_playersCachePath);
		
		# configs
		$profileConfig = loadConfigurations('profiles');
		if(!is_array($profileConfig)) throw new Exception(lang('error_25',true));
		$this->cfg = $profileConfig;
		
	}
	
	public function setType($input) {
		switch($input) {
			case "guild":
				$this->_type = "guild";
				$this->_reqMaxLen = 8;
				break;
			default:
				$this->_type = "player";
				$this->_reqMaxLen = 10;
		}
	}
	
	public function setRequest($input) {
		if(array_key_exists('encode', $this->cfg) && $this->cfg['encode'] == 1) {
			if(!Validator::Chars($input, array('a-z', 'A-Z', '0-9', '_', '-'))) throw new Exception(lang('error_25',true));
			$decodedReq = base64url_decode($input);
			if($decodedReq == false) throw new Exception(lang('error_25',true));
			$this->_request = $decodedReq;
			return true;
		}
		
		if(!Validator::AlphaNumeric($input)) throw new Exception(lang('error_25',true));
		if(strlen($input) > $this->_reqMaxLen) throw new Exception(lang('error_25',true));
		if(strlen($input) < 4) throw new Exception(lang('error_25',true));
		
		$this->_request = $input;
	}
	
	private function checkCacheDir($path) {
		if(check_value($path)) {
			if(!file_exists($path) || !is_dir($path)) {
				if(config('error_reporting',true)) {
					throw new Exception("Invalid cache directory ($path)");
				} else {
					throw new Exception(lang('error_21',true));
				}
			} else {
				if(!is_writable($path)) {
					if(config('error_reporting',true)) {
						throw new Exception("The cache directory is not writable ($path)");
					} else {
						throw new Exception(lang('error_21',true));
					}
				}
			}
		}
	}
	
	private function checkCache() {
		switch($this->_type) {
			case "guild":
				$reqFile = $this->_guildsCachePath . strtolower($this->_request) . '.cache';
				if(!file_exists($reqFile)) {
					$this->cacheGuildData();
				}
				$fileData = file_get_contents($reqFile);
				$fileData = explode("|", $fileData);
				if(is_array($fileData)) {
					if(time() > ($fileData[0]+$this->_cacheUpdateTime)) {
						$this->cacheGuildData();
					}
				} else {
					throw new Exception(lang('error_21',true));
				}
				$this->_fileData = file_get_contents($reqFile);
				break;
			default:
				$reqFile = $this->_playersCachePath . strtolower($this->_request) . '.cache';
				if(!file_exists($reqFile)) {
					$this->cachePlayerData();
				}
				$fileData = file_get_contents($reqFile);
				$fileData = explode("|", $fileData);
				if(is_array($fileData)) {
					if(time() > ($fileData[0]+$this->_cacheUpdateTime)) {
						$this->cachePlayerData();
					}
				} else {
					throw new Exception(lang('error_21',true));
				}
				$this->_fileData = file_get_contents($reqFile);
		}
	}
	
	private function cacheGuildData() {
		// General Data
		$guildData = $this->dB->query_fetch_single("SELECT *, CONVERT(varchar(max), G_Mark, 2) as G_Mark FROM Guild WHERE G_Name = ?", array($this->_request));
		if(!$guildData) throw new Exception(lang('error_25',true));
			
		// Members
		$guildMembers = $this->dB->query_fetch("SELECT * FROM GuildMember WHERE G_Name = ?", array($this->_request));
		if(!$guildMembers) throw new Exception(lang('error_25',true));
		$members = array();
		foreach($guildMembers as $gmember) {
			$members[] = $gmember['Name'];
		}
		$gmembers_str = implode(",", $members);
		
		// Cache
		$data = array(
			time(),
			$guildData['G_Name'],
			$guildData['G_Mark'],
			$guildData['G_Score'],
			$guildData['G_Master'],
			$gmembers_str
		);
		
		// Cache Ready Data
		$cacheData = implode("|", $data);
		
		// Update Cache File
		$reqFile = $this->_guildsCachePath . strtolower($this->_request) . '.cache';
		$fp = fopen($reqFile, 'w+');
		fwrite($fp, $cacheData);
		fclose($fp);
	}
	
	private function cachePlayerData() {
		$Character = new Character();
		
		// general player data
		$playerData = $Character->CharacterData($this->_request);
		if(!$playerData) throw new Exception(lang('error_25',true));
		
		// master level data
		if('MasterSkillTree' == 'Character') {
			$playerMasterLevel = $playerData["MasterLevel"];
		} else {
			$masterLevelInfo = $Character->getMasterLevelInfo($this->_request);
			if(is_array($masterLevelInfo)) {
				$playerMasterLevel = $masterLevelInfo["MasterLevel"];
			}
		}
		
		// guild data
		$guild = "";
		$guildData = $this->dB->query_fetch_single("SELECT * FROM GuildMember WHERE Name = ?", array($this->_request));
		if($guildData) $guild = $guildData['G_Name'];
		
		// Cache
		$data = array(
			time(),
			$playerData['Name'],
			$playerData['Class'],
			$playerData['LVL'],
			$playerData['RSTS'],
			$playerData['STR'],
			$playerData['AGI'],
			$playerData['VIT'],
			$playerData['ENE'],
			$playerData['CMD'],
			$playerData['PK_KILLS'],
			(check_value($playerData['GRSTS']) ? $playerData['GRSTS'] : 0),
			$guild,
			0,
			check_value($playerMasterLevel) ? $playerMasterLevel : 0,
		);
		
		// Cache Ready Data
		$cacheData = implode("|", $data);
		
		// Update Cache File
		$reqFile = $this->_playersCachePath . strtolower($this->_request) . '.cache';
		$fp = fopen($reqFile, 'w+');
		fwrite($fp, $cacheData);
		fclose($fp);
	}
	
	public function data() {
		if(!check_value($this->_type)) throw new Exception(lang('error_21',true));
		if(!check_value($this->_request)) throw new Exception(lang('error_21',true));
		$this->checkCache();
		return(explode("|", $this->_fileData));
	}
	
}