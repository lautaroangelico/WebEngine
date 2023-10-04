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

class Rankings {
	
	private $_results;
	private $_excludedCharacters = array('');
	private $_excludedGuilds = array('');
	private $_rankingsMenu;
	
	function __construct() {
		
		// webengine configs
		$this->config = webengineConfigs();
		$this->serverFiles = strtolower($this->config['server_files']);
		
		// rankings configs
		loadModuleConfigs('rankings');
		$this->_results = (check_value(mconfig('rankings_results')) ? mconfig('rankings_results') : 25);
		
		// excluded characters
		if(check_value(mconfig('rankings_excluded_characters'))) {
			$excludedCharacters = explode(",", mconfig('rankings_excluded_characters'));
			$this->_excludedCharacters = $excludedCharacters;
		}
		
		// excluded guilds
		if(check_value(mconfig('rankings_excluded_guilds'))) {
			$excludedGuilds = explode(",", mconfig('rankings_excluded_guilds'));
			$this->_excludedGuilds = $excludedGuilds;
		}
		
		// rankings menu
		$this->_rankingsMenu = array(
			// language phrase, module, status, file-exclusive (array)
			array(lang('rankings_txt_1',true), 'level', mconfig('rankings_enable_level')),
			array(lang('rankings_txt_2',true), 'resets', mconfig('rankings_enable_resets')),
			array(lang('rankings_txt_3',true), 'killers', mconfig('rankings_enable_pk')),
			array(lang('rankings_txt_4',true), 'guilds', mconfig('rankings_enable_guilds')),
			array(lang('rankings_txt_5',true), 'grandresets', mconfig('rankings_enable_gr')),
			array(lang('rankings_txt_6',true), 'online', mconfig('rankings_enable_online'), array('xteam')),
			array(lang('rankings_txt_7',true), 'votes', mconfig('rankings_enable_votes')),
			array(lang('rankings_txt_8',true), 'gens', mconfig('rankings_enable_gens')),
			array(lang('rankings_txt_22',true), 'master', mconfig('rankings_enable_master')),
		);
		
		// extra menu links
		$extraMenuLinks = getRankingMenuLinks();
		if(is_array($extraMenuLinks)) {
			foreach($extraMenuLinks as $menuLink) {
				$this->_rankingsMenu[] = array($menuLink[0], $menuLink[1], true);
			}
		}
	}
   
	public function UpdateRankingCache($type) {
		switch($type) {
			case 'level':
				$this->_levelsRanking();
				break;
			case 'resets':
				$this->_resetsRanking();
				break;
			case 'killers':
				$this->_killersRanking();
				break;
			case 'grandresets':
				$this->_grandresetsRanking();
				break;
			case 'online':
				$this->_onlineRanking();
				break;
			case 'votes':
				$this->_votesRanking();
				break;
			case 'guilds':
				$this->_guildsRanking();
				break;
			case 'master':
				$this->_masterlevelRanking();
				break;
			case 'gens':
				$this->_gensRanking();
				break;
			default:
				return;
		}
	}
	
	private function _levelsRanking() {
		if(mconfig('combine_level_masterlevel')) {
			// level + master level combined (same tables)
			$result = $this->_getLevelRankingData(true);
		} else {
			// level only
			$result = $this->_getLevelRankingData(false);
		}
		if(!is_array($result)) return;
		
		$cache = BuildCacheData($result);
		UpdateCache('rankings_level.cache', $cache);
	}
	
	private function _resetsRanking() {
		if(mconfig('combine_level_masterlevel')) {
			// level + master level combined (same tables)
			$result = $this->_getResetRankingData(true);
		} else {
			// level only
			$result = $this->_getResetRankingData(false);
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_resets.cache',$cache);
	}
	
	private function _killersRanking() {
		if(mconfig('combine_level_masterlevel')) {
			// level + master level combined (different tables)
			$result = $this->_getKillersRankingData(true);
		} else {
			// level only
			$result = $this->_getKillersRankingData(false);
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_pk.cache',$cache);
	}
	
	private function _grandresetsRanking() {
		$this->mu = Connection::Database('MuOnline');
		
		$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.", "._CLMN_CHR_GRSTS_.", "._CLMN_CHR_RSTS_.", "._CLMN_CHR_CLASS_.", "._CLMN_CHR_MAP_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_GRSTS_." >= 1 AND "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_GRSTS_." DESC, "._CLMN_CHR_RSTS_." DESC");
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_gr.cache',$cache);
	}
	
	private function _guildsRanking() {
		$this->mu = Connection::Database('MuOnline');
		
		switch(mconfig('guild_score_formula')) {
			case 2:
				$result = $this->mu->query_fetch("SELECT "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_.", (SELECT "._CLMN_GUILD_MASTER_." FROM "._TBL_GUILD_." WHERE "._CLMN_GUILD_NAME_." = "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_.") as "._CLMN_GUILD_MASTER_.", SUM("._TBL_CHR_."."._CLMN_CHR_STAT_STR_."+"._TBL_CHR_."."._CLMN_CHR_STAT_AGI_."+"._TBL_CHR_."."._CLMN_CHR_STAT_VIT_."+"._TBL_CHR_."."._CLMN_CHR_STAT_ENE_."+"._TBL_CHR_."."._CLMN_CHR_STAT_CMD_.") as "._CLMN_GUILD_SCORE_.", (SELECT CONVERT(varchar(max), "._CLMN_GUILD_LOGO_.", 2) FROM "._TBL_GUILD_." WHERE "._CLMN_GUILD_NAME_." = "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_.") as "._CLMN_GUILD_LOGO_." FROM "._TBL_GUILDMEMB_." INNER JOIN "._TBL_CHR_." ON "._TBL_CHR_."."._CLMN_CHR_NAME_." = "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_CHAR_." INNER JOIN "._TBL_GUILD_." ON "._TBL_GUILD_."."._CLMN_GUILD_NAME_." = "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_." WHERE "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_." NOT IN(".$this->_rankingsExcludeGuilds().") GROUP BY "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_." ORDER BY "._CLMN_GUILD_SCORE_." DESC");
				break;
			case 3:
				$result = $this->mu->query_fetch("SELECT "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_.", (SELECT "._CLMN_GUILD_MASTER_." FROM "._TBL_GUILD_." WHERE "._CLMN_GUILD_NAME_." = "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_.") as "._CLMN_GUILD_MASTER_.", SUM("._TBL_CHR_."."._CLMN_CHR_STAT_STR_."+"._TBL_CHR_."."._CLMN_CHR_STAT_AGI_."+"._TBL_CHR_."."._CLMN_CHR_STAT_VIT_."+"._TBL_CHR_."."._CLMN_CHR_STAT_ENE_.") as "._CLMN_GUILD_SCORE_.", (SELECT CONVERT(varchar(max), "._CLMN_GUILD_LOGO_.", 2) FROM "._TBL_GUILD_." WHERE "._CLMN_GUILD_NAME_." = "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_.") as "._CLMN_GUILD_LOGO_." FROM "._TBL_GUILDMEMB_." INNER JOIN "._TBL_CHR_." ON "._TBL_CHR_."."._CLMN_CHR_NAME_." = "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_CHAR_." INNER JOIN "._TBL_GUILD_." ON "._TBL_GUILD_."."._CLMN_GUILD_NAME_." = "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_." WHERE "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_." NOT IN(".$this->_rankingsExcludeGuilds().") GROUP BY "._TBL_GUILDMEMB_."."._CLMN_GUILDMEMB_NAME_." ORDER BY "._CLMN_GUILD_SCORE_." DESC");
				break;
			default:
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_GUILD_NAME_.","._CLMN_GUILD_MASTER_.","._CLMN_GUILD_SCORE_.",CONVERT(varchar(max), "._CLMN_GUILD_LOGO_.", 2) as "._CLMN_GUILD_LOGO_." FROM "._TBL_GUILD_." WHERE G_Name NOT IN(".$this->_rankingsExcludeGuilds().") ORDER BY "._CLMN_GUILD_SCORE_." DESC");
		}
		
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_guilds.cache',$cache);
	}
	
	private function _masterlevelRanking() {
		$this->mu = Connection::Database('MuOnline');
		
		if(_TBL_CHR_ == _TBL_MASTERLVL_) {
			// Master Level and Character in same table
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.", "._CLMN_ML_LVL_.", "._CLMN_CHR_CLASS_.", "._CLMN_CHR_LVL_.", "._CLMN_CHR_MAP_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") AND "._CLMN_ML_LVL_." > 0 ORDER BY "._CLMN_ML_LVL_." DESC");
		} else {
			// Master Level in separate table
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." t1."._CLMN_ML_NAME_.", t1."._CLMN_ML_LVL_.", t2."._CLMN_CHR_CLASS_.", t2."._CLMN_CHR_LVL_.", t2."._CLMN_CHR_MAP_." FROM "._TBL_MASTERLVL_." AS t1 INNER JOIN "._TBL_CHR_." AS t2 ON t1."._CLMN_ML_NAME_." = t2."._CLMN_CHR_NAME_." WHERE t1."._CLMN_ML_NAME_." NOT IN(".$this->_rankingsExcludeChars().") AND t1."._CLMN_ML_LVL_." > 0 ORDER BY t1."._CLMN_ML_LVL_." DESC, t2."._CLMN_CHR_LVL_." DESC");
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_master.cache',$cache);
	}
	
	private function _gensRanking() {
		$duprianData = $this->_generateGensRankingData(1);
		if(!is_array($duprianData)) $duprianData = array();
		
		$vanertData = $this->_generateGensRankingData(2);
		if(!is_array($vanertData)) $vanertData = array();
		
		$rankingData = array_merge($duprianData,$vanertData);
		usort($rankingData, function($a, $b) {
			return $b['contribution'] - $a['contribution'];
		});
		$result = array_slice($rankingData, 0, $this->_results);
		if(empty($result)) return;
		if(!is_array($result)) return;
		
		$cache = BuildCacheData($result);
		UpdateCache('rankings_gens.cache',$cache);
	}
	
	private function _votesRanking() {
		$this->me = Connection::Database('Me_MuOnline');
		
		$voteMonth = date("m/01/Y 00:00");
		$voteMonthTimestamp = strtotime($voteMonth);
		$accounts = $this->me->query_fetch("SELECT TOP ".$this->_results." user_id,COUNT(*) as count FROM ".WEBENGINE_VOTE_LOGS." WHERE timestamp >= ? GROUP BY user_id ORDER BY count DESC", array($voteMonthTimestamp));
		if(!is_array($accounts)) return;
		
		foreach($accounts as $data) {
			$common = new common();
			
			$accountInfo = $common->accountInformation($data['user_id']);
			if(!is_array($accountInfo)) continue;
			
			$Character = new Character();
			$characterName = $Character->AccountCharacterIDC($accountInfo[_CLMN_USERNM_]);
			if(!check_value($characterName)) continue;
			
			$characterData = $Character->CharacterData($characterName);
			if(!is_array($characterData)) continue;
			
			if(in_array($characterName, $this->_excludedCharacters)) continue;
			
			$result[] = array($characterName, $data['count'], $characterData[_CLMN_CHR_CLASS_], $characterData[_CLMN_CHR_MAP_]);
		}
		if(!is_array($result)) return;
		$cache = BuildCacheData($result);
		UpdateCache('rankings_votes.cache',$cache);
	}
	
	private function _onlineRanking() {
		$this->me = Connection::Database('Me_MuOnline');
		$this->mu = Connection::Database('MuOnline');
		
		switch($this->serverFiles) {
			case "xteam":
				$result = $this->_getOnlineRankingDataMembStatHours();
				break;
			default:
				return;
		}
		if(!is_array($result)) return;
		
		$cache = BuildCacheData($result);
		UpdateCache('rankings_online.cache',$cache);
	}
	
	public function rankingsMenu() {
		echo '<div class="rankings_menu">';
		foreach($this->_rankingsMenu as $rm_item) {
			if(array_key_exists(3, $rm_item)) {
				if(is_array($rm_item[3])) {
					if(!in_array($this->serverFiles, $rm_item[3])) continue;
				}
			}
			if($rm_item[2]) {
				if($_REQUEST['subpage'] == $rm_item[1]) {
					echo '<a href="'.__PATH_MODULES_RANKINGS__.$rm_item[1].'/" class="active">'.$rm_item[0].'</a>';
				} else {
					echo '<a href="'.__PATH_MODULES_RANKINGS__.$rm_item[1].'/">'.$rm_item[0].'</a>';
				}
			}
		}
		echo '</div>';
	}
	
	private function _rankingsExcludeChars() {
		if(!is_array($this->_excludedCharacters)) return;
		$return = array();
		foreach($this->_excludedCharacters as $characterName) {
			$return[] = "'".$characterName."'";
		}
		return implode(",", $return);
	}
	
	private function _rankingsExcludeGuilds() {
		if(!is_array($this->_excludedGuilds)) return;
		$return = array();
		foreach($this->_excludedGuilds as $guildName) {
			$return[] = "'".$guildName."'";
		}
		return implode(",", $return);
	}
	
	private function _generateGensRankingData($influence=1) {
		$this->mu = Connection::Database('MuOnline');
		
		$result = $this->mu->query_fetch("SELECT t1."._CLMN_GENS_NAME_.", t1."._CLMN_GENS_TYPE_.", t1."._CLMN_GENS_POINT_.", t2."._CLMN_CHR_LVL_.", t2."._CLMN_CHR_CLASS_.", t2."._CLMN_CHR_MAP_." FROM "._TBL_GENS_." as t1 INNER JOIN "._TBL_CHR_." as t2 ON t1."._CLMN_GENS_NAME_." = t2."._CLMN_CHR_NAME_." WHERE t1."._CLMN_GENS_TYPE_." = ? AND t1."._CLMN_GENS_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY t1."._CLMN_GENS_POINT_." DESC", array($influence));
		if(!is_array($result)) return;
		
		foreach($result as $rankPos => $row) {
			$gensRank = getGensRank($row[_CLMN_GENS_POINT_]);
			if($row[_CLMN_GENS_POINT_] >= 10000) {
				$gensRank = getGensLeadershipRank($rankPos);
			}
			
			$rankingData[] = array(
				'name' => $row[_CLMN_GENS_NAME_],
				'influence' => $row[_CLMN_GENS_TYPE_],
				'contribution' => $row[_CLMN_GENS_POINT_],
				'rank' => $gensRank,
				'level' => $row[_CLMN_CHR_LVL_],
				'class' => $row[_CLMN_CHR_CLASS_],
				'map' => $row[_CLMN_CHR_MAP_]
			);
		}
		
		if(!is_array($rankingData)) return;
		return $rankingData;
	}
	
	private function _getLevelRankingData($combineMasterLevel=false) {
		$this->mu = Connection::Database('MuOnline');
		
		// level only (no master level)
		if(!$combineMasterLevel) {
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_LVL_.","._CLMN_CHR_MAP_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_LVL_." DESC");
			if(!is_array($result)) return;
			return $result;
		}
		
		if(_TBL_CHR_ == _TBL_MASTERLVL_) {
			
			// level + master level (in same table)
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.",("._CLMN_CHR_LVL_."+"._CLMN_ML_LVL_.") as "._CLMN_CHR_LVL_.","._CLMN_CHR_MAP_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_LVL_." DESC");
			if(!is_array($result)) return;
			return $result;
		} else {
		
			// level + master level (different tables)
			$Character = new Character();
			$characters = $this->mu->query_fetch("SELECT "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_LVL_.","._CLMN_CHR_MAP_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_LVL_." DESC");
			if(!is_array($characters)) return;
			foreach($characters as $row) {
				$masterLevelInfo = $Character->getMasterLevelInfo($row[_CLMN_CHR_NAME_]);
				$rankingData[] = array(
					_CLMN_CHR_NAME_ => $row[_CLMN_CHR_NAME_],
					_CLMN_CHR_CLASS_ => $row[_CLMN_CHR_CLASS_],
					_CLMN_CHR_LVL_ => $row[_CLMN_CHR_LVL_]+$masterLevelInfo[_CLMN_ML_LVL_],
					_CLMN_CHR_MAP_ => $row[_CLMN_CHR_MAP_],
				);
			}
			
			usort($rankingData, function($a, $b) {
				return $b[_CLMN_CHR_LVL_] - $a[_CLMN_CHR_LVL_];
			});
			
			$result = array_slice($rankingData, 0, $this->_results);
			if(!is_array($result)) return;
			return $result;
		}
	}
	
	private function _getResetRankingData($combineMasterLevel=false) {
		$this->mu = Connection::Database('MuOnline');
		
		// level only (no master level)
		if(!$combineMasterLevel) {
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_RSTS_.","._CLMN_CHR_LVL_.","._CLMN_CHR_MAP_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") AND "._CLMN_CHR_RSTS_." > 0 ORDER BY "._CLMN_CHR_RSTS_." DESC, "._CLMN_CHR_LVL_." DESC");
			if(!is_array($result)) return;
			return $result;
		}
		
		if(_TBL_CHR_ == _TBL_MASTERLVL_) {
			// level + master level (in same table)
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_RSTS_.",("._CLMN_CHR_LVL_."+"._CLMN_ML_LVL_.") as "._CLMN_CHR_LVL_.","._CLMN_CHR_MAP_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") AND "._CLMN_CHR_RSTS_." > 0 ORDER BY "._CLMN_CHR_RSTS_." DESC, "._CLMN_CHR_LVL_." DESC");
			if(!is_array($result)) return;
			return $result;
		} else {
			// level + master level (different tables)
			$Character = new Character();
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._TBL_CHR_."."._CLMN_CHR_NAME_.", "._TBL_CHR_."."._CLMN_CHR_CLASS_.", "._TBL_CHR_."."._CLMN_CHR_RSTS_.", ("._TBL_CHR_."."._CLMN_CHR_LVL_." + "._TBL_MASTERLVL_."."._CLMN_ML_LVL_.") as "._CLMN_CHR_LVL_.", "._TBL_CHR_."."._CLMN_CHR_MAP_." FROM "._TBL_CHR_." INNER JOIN "._TBL_MASTERLVL_." ON "._TBL_CHR_."."._CLMN_CHR_NAME_." = "._TBL_MASTERLVL_."."._CLMN_ML_NAME_." WHERE "._TBL_CHR_."."._CLMN_CHR_NAME_." NOT IN (".$this->_rankingsExcludeChars().") AND "._TBL_CHR_."."._CLMN_CHR_RSTS_." > 0 ORDER BY "._TBL_CHR_."."._CLMN_CHR_RSTS_." DESC, "._CLMN_CHR_LVL_." DESC");
			if(!is_array($result)) return;
			return $result;
		}
	}
	
	private function _getKillersRankingData($combineMasterLevel=false) {
		$this->mu = Connection::Database('MuOnline');
		
		// level only (no master level)
		if(!$combineMasterLevel) {
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_PK_KILLS_.","._CLMN_CHR_LVL_.","._CLMN_CHR_MAP_.","._CLMN_CHR_PK_LEVEL_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") AND "._CLMN_CHR_PK_KILLS_." > 0 ORDER BY "._CLMN_CHR_PK_KILLS_." DESC");
			if(!is_array($result)) return;
			return $result;
		}
		
		if(_TBL_CHR_ == _TBL_MASTERLVL_) {
			// level + master level (in same table)
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_PK_KILLS_.",("._CLMN_CHR_LVL_."+"._CLMN_ML_LVL_.") as "._CLMN_CHR_LVL_.","._CLMN_CHR_MAP_.","._CLMN_CHR_PK_LEVEL_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") AND "._CLMN_CHR_PK_KILLS_." > 0 ORDER BY "._CLMN_CHR_PK_KILLS_." DESC");
			if(!is_array($result)) return;
			return $result;
		} else {
			// level + master level (different tables)
			$Character = new Character();
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_PK_KILLS_.","._CLMN_CHR_LVL_.","._CLMN_CHR_MAP_.","._CLMN_CHR_PK_LEVEL_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") AND "._CLMN_CHR_PK_KILLS_." > 0 ORDER BY "._CLMN_CHR_PK_KILLS_." DESC");
			if(!is_array($result)) return;
			foreach($result as $key => $row) {
				$masterLevelInfo = $Character->getMasterLevelInfo($row[_CLMN_CHR_NAME_]);
				if(!is_array($masterLevelInfo)) continue;
				$result[$key][_CLMN_CHR_LVL_] = $row[_CLMN_CHR_LVL_]+$masterLevelInfo[_CLMN_ML_LVL_];
			}
			return $result;
		}
	}
	
	private function _getOnlineRankingDataMembStatHours() {
		$this->mu = Connection::Database('MuOnline');
		
		$accounts = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_MS_MEMBID_.", "._CLMN_MS_ONLINEHRS_." FROM "._TBL_MS_." WHERE "._CLMN_MS_ONLINEHRS_." > 0 ORDER BY "._CLMN_MS_ONLINEHRS_." DESC");
		if(!is_array($accounts)) return;
		$Character = new Character();
		foreach($accounts as $row) {
			$playerIDC = $Character->AccountCharacterIDC($row[_CLMN_MS_MEMBID_]);
			if(!check_value($playerIDC)) continue;
			$platerData = $Character->CharacterData($playerIDC);
			if(!is_array($platerData)) continue;
			$result[] = array(
				$playerIDC,
				$row[_CLMN_MS_ONLINEHRS_]*3600,
				$platerData[_CLMN_CHR_CLASS_],
				$platerData[_CLMN_CHR_MAP_]
			);
		}
		if(!is_array($result)) return;
		return $result;
	}
	
	private function _getRankingsFilterData() {
		$classesData = custom('character_class');
		$rankingsFilter = custom('rankings_classgroup_filter');

		if(is_array($rankingsFilter)) {
			foreach($rankingsFilter as $class => $phrase) {
				if(!array_key_exists($class, $classesData)) continue;
				
				$filterName = lang($phrase) == 'ERROR' ? $phrase : lang($phrase);
				$classGroupList = array();
				foreach($classesData as $key => $row) {
					if($row['class_group'] == $class) {
						$classGroupList[] = $key;
					}
				}
				$filterList[] = array(
					$class,
					implode(',', $classGroupList),
					$filterName,
				);
			}
		}
		
		if(!is_array($filterList)) return;
		return $filterList;
	}
	
	public function rankingsFilterMenu() {
		$filterData = $this->_getRankingsFilterData();
		if(!is_array($filterData)) return;
		
		echo '<div class="text-center">';
			echo '<ul class="rankings-class-filter">';
				
				echo '<li><a onclick="rankingsFilterRemove()" class="rankings-class-filter-selection">'.getPlayerClassAvatar(-1, true, false, 'rankings-class-filter-image').'<br />'.lang('rankings_filter_1').'</a></li>';
				
				foreach($filterData as $row) {
					echo '<li><a onclick="rankingsFilterByClass('.$row[1].')" class="rankings-class-filter-selection rankings-class-filter-grayscale">'.getPlayerClassAvatar($row[0], true, false, 'rankings-class-filter-image').'<br />'.$row[2].'</a></li>';
				}
			echo '</ul>';
		echo '</div>';
	}

}