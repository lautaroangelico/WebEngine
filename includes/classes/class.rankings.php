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

class Rankings {
	
	private $_results;
	private $_excludedCharacters = array('');
	private $_excludedGuilds = array('');
	private $_rankingsMenu;
	
	protected $config;
	protected $serverFiles;
	protected $mu;
	protected $me;
	
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
		
		$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, MasterResetCount, ResetCount, Class, MapNumber FROM Character WHERE MasterResetCount >= 1 AND Name NOT IN(".$this->_rankingsExcludeChars().") ORDER BY MasterResetCount DESC, ResetCount DESC");
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_gr.cache',$cache);
	}
	
	private function _guildsRanking() {
		$this->mu = Connection::Database('MuOnline');
		
		switch(mconfig('guild_score_formula')) {
			case 2:
				$result = $this->mu->query_fetch("SELECT GuildMember.G_Name, (SELECT G_Master FROM Guild WHERE G_Name = GuildMember.G_Name) as G_Master, SUM(Character.STR+Character.AGI+Character.VIT+Character.ENE+Character.CMD) as G_Score, (SELECT CONVERT(varchar(max), G_Mark, 2) FROM Guild WHERE G_Name = GuildMember.G_Name) as G_Mark FROM GuildMember INNER JOIN Character ON Character.Name = GuildMember.Name INNER JOIN Guild ON Guild.G_Name = GuildMember.G_Name WHERE GuildMember.G_Name NOT IN(".$this->_rankingsExcludeGuilds().") GROUP BY GuildMember.G_Name ORDER BY G_Score DESC");
				break;
			case 3:
				$result = $this->mu->query_fetch("SELECT GuildMember.G_Name, (SELECT G_Master FROM Guild WHERE G_Name = GuildMember.G_Name) as G_Master, SUM(Character.STR+Character.AGI+Character.VIT+Character.ENE+Character.CMD) as G_Score, (SELECT CONVERT(varchar(max), G_Mark, 2) FROM Guild WHERE G_Name = GuildMember.G_Name) as G_Mark FROM GuildMember INNER JOIN Character ON Character.Name = GuildMember.Name INNER JOIN Guild ON Guild.G_Name = GuildMember.G_Name WHERE GuildMember.G_Name NOT IN(".$this->_rankingsExcludeGuilds().") GROUP BY GuildMember.G_Name ORDER BY G_Score DESC");
				break;
			default:
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." 
				G_Name,
				G_Master,
				G_Master, 
				G_Score,
				CONVERT(varchar(max), G_Mark, 2) as G_Mark
				FROM ".'Guild'." 
				WHERE G_Name NOT IN(".$this->_rankingsExcludeGuilds().") ORDER BY G_Score DESC");
		}
		
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_guilds.cache',$cache);
	}
	
	private function _masterlevelRanking() {
		$this->mu = Connection::Database('MuOnline');
		
		if('Character' == 'MasterSkillTree') {
			// Master Level and Character in same table
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, MasterLevel, Class, cLevel, MapNumber FROM Character WHERE Name NOT IN(".$this->_rankingsExcludeChars().") AND "."MasterLevel"." > 0 ORDER BY "."MasterLevel"." DESC");
		} else {
			// Master Level in separate table
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." t1.Name,t1.MasterLevel, t2.Class, t2.cLevel, t2.MapNumber FROM MasterSkillTree AS t1 INNER JOIN Character AS t2 ON t1.Name = t2.Name WHERE t1.Name NOT IN(".$this->_rankingsExcludeChars().") AND t1.MasterLevel > 0 ORDER BY t1.MasterLevel DESC, t2.cLevel DESC");
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
		$accounts = $this->me->query_fetch("SELECT TOP ".$this->_results." user_id,COUNT(*) as count FROM WEBENGINE_VOTE_LOGS WHERE timestamp >= ? GROUP BY user_id ORDER BY count DESC", array($voteMonthTimestamp));
		if(!is_array($accounts)) return;
		
		foreach($accounts as $data) {
			$common = new common();
			
			$accountInfo = $common->accountInformation($data['user_id']);
			if(!is_array($accountInfo)) continue;
			
			$Character = new Character();
			$characterName = $Character->AccountCharacterIDC($accountInfo['memb___id']);
			if(!check_value($characterName)) continue;
			
			$characterData = $Character->CharacterData($characterName);
			if(!is_array($characterData)) continue;
			
			if(in_array($characterName, $this->_excludedCharacters)) continue;
			
			$result[] = array($characterName, $data['count'], $characterData['Class'], $characterData['MapNumber']);
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
		
		$result = $this->mu->query_fetch("SELECT t1.Name, t1.Family, t1.Contribution, t2.cLevel, t2.Class, t2.MapNumber FROM Gens_Rank as t1 INNER JOIN Character as t2 ON t1.Name = t2.Name WHERE t1.Family = ? AND t1.Name NOT IN(".$this->_rankingsExcludeChars().") ORDER BY t1.Contribution DESC", array($influence));
		if(!is_array($result)) return;
		
		foreach($result as $rankPos => $row) {
			$gensRank = getGensRank($row['Contribution']);
			if($row['Contribution'] >= 10000) {
				$gensRank = getGensLeadershipRank($rankPos);
			}
			
			$rankingData[] = array(
				'name' => $row['Name'],
				'influence' => $row['Family'],
				'contribution' => $row['Contribution'],
				'rank' => $gensRank,
				'level' => $row['cLevel'],
				'class' => $row['Class'],
				'map' => $row['MapNumber']
			);
		}
		
		if(!is_array($rankingData)) return;
		return $rankingData;
	}
	
	private function _getLevelRankingData($combineMasterLevel=false) {
		$this->mu = Connection::Database('MuOnline');
		
		// level only (no master level)
		if(!$combineMasterLevel) {
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, Class, cLevel, MapNumber FROM Character WHERE Name NOT IN(".$this->_rankingsExcludeChars().") ORDER BY cLevel DESC");
			if(!is_array($result)) return;
			return $result;
		}
		
		if('Character' == 'MasterSkillTree') {
			
			// level + master level (in same table)
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, Class, (cLevel+MasterLevel) as cLevel, MapNumber FROM Character WHERE Name NOT IN(".$this->_rankingsExcludeChars().") ORDER BY cLevel DESC");
			if(!is_array($result)) return;
			return $result;
		} else {
		
			// level + master level (different tables)
			$Character = new Character();
			$characters = $this->mu->query_fetch("SELECT Name, Class, cLevel, MapNumber FROM Character WHERE Name NOT IN(".$this->_rankingsExcludeChars().") ORDER BY cLevel DESC");
			if(!is_array($characters)) return;
			foreach($characters as $row) {
				$masterLevelInfo = $Character->getMasterLevelInfo($row['Name']);
				$rankingData[] = array(
					'Name' => $row['Name'],
					'Class' => $row['Class'],
					'cLevel' => $row['cLevel']+$masterLevelInfo["MasterLevel"],
					'MapNumber' => $row['MapNumber'],
				);
			}
			
			usort($rankingData, function($a, $b) {
				return $b['cLevel'] - $a['cLevel'];
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
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, Class, ResetCount, cLevel, MapNumber FROM Character WHERE Name NOT IN(".$this->_rankingsExcludeChars().") AND ResetCount > 0 ORDER BY ResetCount DESC, cLevel DESC");
			if(!is_array($result)) return;
			return $result;
		}
		
		if('Character' == 'MasterSkillTree') {
			// level + master level (in same table)
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, Class, ResetCount, (cLevel+MasterLevel) as cLevel, MapNumber FROM Character WHERE Name NOT IN(".$this->_rankingsExcludeChars().") AND ResetCount > 0 ORDER BY ResetCount DESC, cLevel DESC");
			if(!is_array($result)) return;
			return $result;
		} else {
			// level + master level (different tables)
			$Character = new Character();
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, Class, ResetCount, (cLevel+MasterLevel) as cLevel, MapNumber FROM Character INNER JOIN MasterSkillTree ON Character.Name = MasterSkillTree.Name WHERE Name NOT IN(".$this->_rankingsExcludeChars().") AND ResetCount > 0 ORDER BY ResetCount DESC, cLevel DESC");
			if(!is_array($result)) return;
			return $result;
		}
	}
	
	private function _getKillersRankingData($combineMasterLevel=false) {
		$this->mu = Connection::Database('MuOnline');
		
		// level only (no master level)
		if(!$combineMasterLevel) {
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, Class, PkCount, cLevel, MapNumber, PkLevel FROM Character WHERE Name NOT IN(".$this->_rankingsExcludeChars().") AND PkCount > 0 ORDER BY PkCount DESC");
			if(!is_array($result)) return;
			return $result;
		}
		
		if('Character' == 'MasterSkillTree') {
			// level + master level (in same table)
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, Class, PkCount, (cLevel+MasterLevel) as cLevel, MapNumber, PkLevel FROM Character WHERE Name NOT IN(".$this->_rankingsExcludeChars().") AND PkCount > 0 ORDER BY PkCount DESC");
			if(!is_array($result)) return;
			return $result;
		} else {
			// level + master level (different tables)
			$Character = new Character();
			$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." Name, Class, PkCount, (cLevel+MasterLevel) as cLevel, MapNumber, PkLevel FROM Character INNER JOIN MasterSkillTree ON Character.Name = MasterSkillTree.Name WHERE Name NOT IN(".$this->_rankingsExcludeChars().") AND PkCount > 0 ORDER BY PkCount DESC");
			if(!is_array($result)) return;
			foreach($result as $key => $row) {
				$masterLevelInfo = $Character->getMasterLevelInfo($row['Name']);
				if(!is_array($masterLevelInfo)) continue;
				$result[$key]['cLevel'] = $row['cLevel']+$masterLevelInfo["MasterLevel"];
			}
			return $result;
		}
	}
	
	private function _getOnlineRankingDataMembStatHours() {
		$this->mu = Connection::Database('MuOnline');
		
		$accounts = $this->mu->query_fetch("SELECT TOP ".$this->_results." memb___id, OnlineHours FROM MEMB_STAT WHERE OnlineHours > 0 ORDER BY OnlineHours DESC");
		if(!is_array($accounts)) return;
		$Character = new Character();
		foreach($accounts as $row) {
			$playerIDC = $Character->AccountCharacterIDC($row['memb___id']);
			if(!check_value($playerIDC)) continue;
			$platerData = $Character->CharacterData($playerIDC);
			if(!is_array($platerData)) continue;
			$result[] = array(
				$playerIDC,
				$row['OnlineHours']*3600,
				$platerData['Class'],
				$platerData['MapNumber']
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