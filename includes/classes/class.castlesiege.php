<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.5
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2023 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class CastleSiege {
	
	protected $_database = 'MuOnline';
	protected $_configFileName = 'castlesiege';
	protected $_cacheFileName = 'castle_siege.cache';
	protected $_dateFormat = 'Y/m/d H:i';
	protected $_friendlyDateFormat = 'l, F jS g:i A';
	protected $_defaultGuildLogo = '1111111111111111111111111114411111144111111111111111111111111111';
	
	protected $_active = true;
	protected $_hideIdle = false;
	protected $_liveData = false;
	protected $_showCastleOwner = true;
	protected $_showCastleOwnerAlliance = true;
	protected $_showBattleCountdown = true;
	protected $_showCastleInformation = true;
	protected $_showCurrentStage = true;
	protected $_showNextStage = true;
	protected $_showBattleDuration = true;
	protected $_showRegisteredGuilds = true;
	protected $_showSchedule = true;
	protected $_showWidget = true;
	
	protected $_stages;
	protected $_schedule;
	protected $_currentStage;
	protected $_nextStage;
	protected $_warfareStage;
	
	protected $_cacheSiegeData;
	
	function __construct() {
		
		// load configuration file
		$cfg = loadConfig($this->_configFileName);
		if(!$cfg) throw new Exception('Could not load castle siege configuration file.');
		
		// set configs
		$this->_active = $cfg['active'];
		$this->_hideIdle = $cfg['hide_idle'];
		$this->_liveData = $cfg['live_data'];
		$this->_showCastleOwner = $cfg['show_castle_owner'];
		$this->_showCastleOwnerAlliance = $cfg['show_castle_owner_alliance'];
		$this->_showBattleCountdown = $cfg['show_battle_countdown'];
		$this->_showCastleInformation = $cfg['show_castle_information'];
		$this->_showCurrentStage = $cfg['show_current_stage'];
		$this->_showNextStage = $cfg['show_next_stage'];
		$this->_showBattleDuration = $cfg['show_battle_duration'];
		$this->_showRegisteredGuilds = $cfg['show_registered_guilds'];
		$this->_showSchedule = $cfg['show_schedule'];
		$this->_showWidget = $cfg['show_widget'];
		$this->_friendlyDateFormat = $cfg['schedule_date_format'];
		$this->_stages = $cfg['stages'];
		
		// schedule
		$this->_generateSchedule();
		
		// current stage
		$this->_determineCurrentStage();
		$this->_determineNextStage();
		$this->_determineWarfareStage();
		
		// siege cache
		$this->_loadCacheSiegeData();
		
	}
	
	public function siegeData() {
		if($this->_liveData == true) {
        	$this->_initDatabase();
        } else {
        	if(!is_array($this->_cacheSiegeData)) return;
        }
		
		$result = array(
			'current_stage' => $this->getCurrentStage(),
			'next_stage' => $this->getNextStage(),
			'warfare_stage' => $this->getWarfareStage(),
			'castle_data' => $this->getCastleData(),
			'castle_owner_alliance' => $this->getCastleOwnerAlliance(),
			'registered_guilds' => $this->getRegisteredGuildsAndAlliances(),
			'schedule' => $this->getSchedule(),
			'next_stage_timeleft' => $this->_getSecondsForNextStage(),
			'next_stage_countdown' => $this->getNextStageCountdown(),
			'warfare_stage_timeleft' => $this->_getSecondsForWarfareStage(),
			'warfare_stage_countdown' => $this->getWarfareStageCountdown(),
			'warfare_duration' => $this->getWarfareDuration(),
		);
		
		return $result;
	}
	
	public function getSchedule() {
		return $this->_schedule;
	}
	
	public function getCurrentStage() {
		return $this->_schedule[$this->_currentStage];
	}
	
	public function getNextStage() {
		if($this->_nextStage == 0) {
			return $this->_getNextScheduleStartingStage();
		}
		return $this->_schedule[$this->_nextStage];
	}
	
	public function getWarfareStage() {
		return $this->_schedule[$this->_warfareStage];
	}
	
	public function getNextStageCountdown() {
		return $this->_formatCountdownTime($this->_getSecondsForNextStage());
	}
	
	public function getWarfareStageCountdown() {
		return $this->_formatCountdownTime($this->_getSecondsForWarfareStage());
	}
	
	public function getCastleData() {
		if($this->_liveData == true) {
			return $this->_returnCastleData();
		} else {
			return $this->_cacheSiegeData['castle_data'];
		}
	}
	
	public function getCastleOwnerAlliance() {
		if($this->_liveData == true) {
			return $this->_returnCastleOwnerAlliance();
		} else {
			return $this->_cacheSiegeData['castle_owner_alliance'];
		}
	}
	
	public function getRegisteredGuildsAndAlliances() {
		if($this->_liveData == true) {
			return $this->_returnRegisteredGuildsAndAlliances();
		} else {
			return $this->_cacheSiegeData['registered_guilds'];
		}
	}
	
	public function getDefaultGuildLogo() {
		return $this->_defaultGuildLogo;
	}
	
	public function friendlyDateFormat($timestamp) {
		return date($this->_friendlyDateFormat, $timestamp);
	}
	
	public function showCastleOwner() {
		return $this->_showCastleOwner;
	}
	
	public function showCastleOwnerAlliance() {
		return $this->_showCastleOwnerAlliance;
	}
	
	public function showBattleCountdown() {
		return $this->_showBattleCountdown;
	}
	
	public function showCastleInformation() {
		return $this->_showCastleInformation;
	}
	
	public function showCurrentStage() {
		return $this->_showCurrentStage;
	}
	
	public function showNextStage() {
		return $this->_showNextStage;
	}
	
	public function showRegisteredGuilds() {
		return $this->_showRegisteredGuilds;
	}
	
	public function showBattleDuration() {
		return $this->_showBattleDuration;
	}
	
	public function showSchedule() {
		return $this->_showSchedule;
	}
	
	public function showWidget() {
		return $this->_showWidget;
	}
	
	public function moduleEnabled() {
		return $this->_active;
	}
	
	public function getWarfareDuration() {
		$warfareStage = $this->getWarfareStage();
		$warfareDurationSeconds = $warfareStage['end_timestamp']-$warfareStage['start_timestamp'];
		$warfareDuration = sec_to_hms($warfareDurationSeconds);
		return langf('castlesiege_battle_duration', array($warfareDuration[0], $warfareDuration[1]));
	}
	
	public function updateSiegeCache() {
		$this->_initDatabase();
		$this->_liveData = true;
		$this->_cacheSiegeData();
	}
	
	protected function _generateSchedule() {
		if(!is_array($this->_stages)) throw new Exception('The castle siege schedule could not be generated, missing stages data.');
		
		$schedule = $this->_stages;
		
		$currentDay = date("l");
		$currentTime = date("H:i");

		$csScheduleStartDay = $schedule[0]['start_day'];
		$csScheduleStartTime = $schedule[0]['start_time'];
		if(strtolower($currentDay) != strtolower($csScheduleStartDay)) {
			$scheduleStartingDay = strtotime('last ' . $csScheduleStartDay . ' ' . $csScheduleStartTime);
		} else {
			$scheduleStartingDay = strtotime('today ' . $csScheduleStartTime);
		}

		foreach($schedule as $key => $stage) {
			
			// hide idle stages
			if($this->_hideIdle == true) {
				if($stage['is_idle'] == true) {
					unset($schedule[$key]);
					continue;
				}
			}
			
			// check if we are on starting schedule day
			if($key == 0) {
				if(strtolower($currentDay) == strtolower($stage['start_day'])) {
					$start_timestamp = strtotime('today ' . $stage['start_time']);
				} else {
					$start_timestamp = strtotime('last '.$stage['start_day'].' ' . $stage['start_time']);
				}
				if(strtolower($currentDay) == strtolower($stage['end_day'])) {
					$end_timestamp = strtotime('today ' . $stage['end_time']);
				} else {
					$end_timestamp = strtotime('next '.$stage['end_day'].' ' . $stage['end_time'], $scheduleStartingDay);
				}
			} else {
				$start_timestamp = strtotime('next '.$stage['start_day'].' ' . $stage['start_time'], $scheduleStartingDay);
				$end_timestamp = strtotime('next '.$stage['end_day'].' ' . $stage['end_time'], $scheduleStartingDay);
			}
			
			$schedule[$key]['title'] = lang($schedule[$key]['title']);
			$schedule[$key]['start_timestamp'] = $start_timestamp;
			$schedule[$key]['end_timestamp'] = $end_timestamp;
			$schedule[$key]['start_date'] = date($this->_dateFormat, $start_timestamp);
			$schedule[$key]['end_date'] = date($this->_dateFormat, $end_timestamp);
		}
		
		$this->_schedule = array_values($schedule);
	}
	
	protected function _determineCurrentStage() {
		foreach($this->_schedule as $key => $row) {
			if(time() < $row['start_timestamp']) continue;
			if(time() > $row['end_timestamp']) continue;
			$this->_currentStage = $key;
			return;
		}
	}
	
	protected function _determineNextStage() {
		if(array_key_exists($this->_currentStage+1, $this->_schedule)) {
			$this->_nextStage = $this->_currentStage+1;
			return;
		}
		$this->_nextStage = 0;
	}
	
	protected function _determineWarfareStage() {
		foreach($this->_schedule as $key => $row) {
			if($row['is_battle'] == true) {
				$this->_warfareStage = $key;
				return;
			}
		}
	}
	
	protected function _getNextScheduleStartingStage() {
		$stage = $this->_schedule[0];
		$startTimestamp = strtotime('next '.$stage['start_day'].' '.$stage['start_time'], $stage['start_timestamp']);
		$endTimestamp = strtotime('next '.$stage['end_day'].' '.$stage['end_time'], $stage['end_timestamp']);
		$stage['start_timestamp'] = $startTimestamp;
		$stage['end_timestamp'] = $endTimestamp;
		$stage['start_date'] = date($this->_dateFormat, $startTimestamp);
		$stage['end_date'] = date($this->_dateFormat, $endTimestamp);
		return $stage;
	}
	
	protected function _getSecondsForNextStage() {
		$nextStage = $this->getNextStage();
		return $nextStage['start_timestamp']-time();
	}
	
	protected function _getSecondsForWarfareStage() {
		$warfareStage = $this->getWarfareStage();
		return $warfareStage['start_timestamp']-time();
	}
	
	protected function _formatCountdownTime($seconds) {
		$timeleft = sec_to_dhms($seconds);
		// days + hours
		if($timeleft[0] > 0) {
			return langf('castlesiege_time_1', array($timeleft[0], $timeleft[1]));
		}
		// hours + minutes
		if($timeleft[1] > 0) {
			return langf('castlesiege_time_2', array($timeleft[1], $timeleft[2]));
		}
		// minutes
		if($timeleft[2] > 0) {
			return langf('castlesiege_time_3', array($timeleft[2]));
		}
		return lang('castlesiege_time_4');
	}
	
	protected function _returnCastleData() {
		$result = $this->db->query_fetch_single("SELECT * FROM "._TBL_MUCASTLE_DATA_."");
		if(!is_array($result)) return;
		return $result;
	}
	
	protected function _returnCastleOwnerAlliance() {
		$castleData = $this->getCastleData();
		$castleOwnerData = $this->_getGuildData($castleData[_CLMN_MCD_GUILD_OWNER_]);
		if(!is_array($castleOwnerData)) return;
		$castleOwnerData['member_count'] = $this->_getGuildMemberCount($castleData[_CLMN_MCD_GUILD_OWNER_]);
		$result[] = $castleOwnerData;
		$alliedGuilds = $this->_getAlliedGuilds($castleData[_CLMN_MCD_GUILD_OWNER_]);
		if(is_array($alliedGuilds)) {
			foreach($alliedGuilds as $alliedGuild) {
				$result[] = $alliedGuild;
			}
		}
		return $result;
	}
	
	protected function _getGuildMemberCount($guild) {
		$guildMembers = $this->db->query_fetch_single("SELECT COUNT(*) AS result FROM "._TBL_GUILDMEMB_." WHERE "._CLMN_GUILDMEMB_NAME_." = ?", array($guild));
		if(!is_array($guildMembers)) return 1;
		return $guildMembers['result'];
	}
	
	protected function _getAlliedGuilds($guild) {
		$alliedGuilds = $this->db->query_fetch("SELECT * FROM "._TBL_MUCASTLE_SGL_." WHERE "._CLMN_MCSGL_GID_." = (SELECT "._CLMN_MCSGL_GID_." FROM "._TBL_MUCASTLE_SGL_." WHERE "._CLMN_MCSGL_GNAME_." = :guild) AND "._CLMN_MCSGL_GNAME_." != :guild", array('guild' => $guild));
		if(!is_array($alliedGuilds)) return;
		foreach($alliedGuilds as $alliedGuild) {
			$alliedGuildData = $this->_getGuildData($alliedGuild[_CLMN_MCSGL_GNAME_]);
			if(!is_array($alliedGuildData)) continue;
			$alliedGuildData['member_count'] = $this->_getGuildMemberCount($alliedGuild[_CLMN_MCSGL_GNAME_]);
			$result[] = $alliedGuildData;
		}
		if(!is_array($result)) return;
		return $result;
	}
	
	protected function _getGuildData($guild) {
		$result = $this->db->query_fetch_single("SELECT *, CONVERT(varchar(max), "._CLMN_GUILD_LOGO_.", 2) as "._CLMN_GUILD_LOGO_." FROM "._TBL_GUILD_." WHERE "._CLMN_GUILD_NAME_." = ?", array($guild));
		if(!is_array($result)) return;
		return $result;
	}
	
	protected function _returnRegisteredGuildsAndAlliances() {
		$registeredGuilds = $this->db->query_fetch("SELECT * FROM "._TBL_MUCASTLE_RS_." ORDER BY "._CLMN_MCRS_SEQNUM_." ASC");
		if(!is_array($registeredGuilds)) return;
		foreach($registeredGuilds as $registeredGuild) {
			$guildData = $this->_getGuildData($registeredGuild[_CLMN_MCRS_GUILD_]);
			$guildData['member_count'] = $this->_getGuildMemberCount($registeredGuild[_CLMN_MCRS_GUILD_]);
			$result[] = $guildData;
			$alliedGuilds = $this->_getAlliedGuilds($registeredGuild[_CLMN_MCRS_GUILD_]);
			if(is_array($alliedGuilds)) {
				foreach($alliedGuilds as $alliedGuild) {
					$result[] = $alliedGuild;
				}
			}
		}
		if(!is_array($result)) return;
		return $result;
	}
	
	protected function _cacheSiegeData() {
		$data = array(
			'castle_data' => $this->getCastleData(),
			'castle_owner_alliance' => $this->getCastleOwnerAlliance(),
			'registered_guilds' => $this->getRegisteredGuildsAndAlliances()
		);
		$data = encodeCache($data, true);
		$update = updateCacheFile($this->_cacheFileName, $data);
		if(!$update) return;
		return true;
	}
	
	protected function _loadCacheSiegeData() {
		$data = loadCache($this->_cacheFileName);
		if(!is_array($data)) return;
		$this->_cacheSiegeData = $data;
	}
	
	protected function _initDatabase() {
		if(check_value($this->db)) return;
		$this->db = Connection::Database($this->_database);
	}
	
}