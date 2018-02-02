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

class Rankings {
	
	private $_results;
	private $_excludedCharacters;
	
	function __construct() {
		global $dB,$dB2;
		
		$this->mu = $dB;
		$this->me = $dB2;
		$this->db = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
		$this->config = webengineConfigs();
		$this->serverFiles = $this->config['server_files'];
		
		loadModuleConfigs('rankings');
		$this->_results = (check_value(mconfig('rankings_results')) ? mconfig('rankings_results') : 25);
		
		$excludedCharacters = explode(",", mconfig('rankings_excluded_characters'));
		$this->_excludedCharacters = $excludedCharacters;
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
			case 'pvplaststand':
				$this->_pvplaststandRanking();
				break;
			case 'gens':
				$this->_gensRanking();
				break;
			default:
				return;
		}
	}
	
	private function _levelsRanking() {
		switch($this->serverFiles) {
			case "MUE":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_LVL_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_LVL_." DESC");
				break;
			case "IGCN":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_LVL_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_LVL_." DESC");
				break;
			case "CUSTOM":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_LVL_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_LVL_." DESC");
				break;
			default:
				return;
		}
		if(!is_array($result)) return;
		
		$cache = BuildCacheData($result);
		UpdateCache('rankings_level.cache', $cache);
	}
	
	private function _resetsRanking() {
		switch($this->serverFiles) {
			case "MUE":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_RSTS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_RSTS_." DESC");
				break;
			case "IGCN":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_RSTS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_RSTS_." DESC");
				break;
			case "CUSTOM":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_RSTS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_RSTS_." DESC");
				break;
			default:
				return;
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_resets.cache',$cache);
	}
	
	private function _killersRanking() {
		switch($this->serverFiles) {
			case "MUE":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_PK_KILLS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_PK_KILLS_." DESC");
				break;
			case "IGCN":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_PK_KILLS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_PK_KILLS_." DESC");
				break;
			case "CUSTOM":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.","._CLMN_CHR_CLASS_.","._CLMN_CHR_PK_KILLS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_PK_KILLS_." DESC");
				break;
			default:
				return;
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_pk.cache',$cache);
	}
	
	private function _grandresetsRanking() {
		switch($this->serverFiles) {
			case "MUE":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.", "._CLMN_CHR_GRSTS_.", "._CLMN_CHR_RSTS_.", "._CLMN_CHR_CLASS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_GRSTS_." >= 1 AND "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_GRSTS_." DESC, "._CLMN_CHR_RSTS_." DESC");
				break;
			case "IGCN":
				
				break;
			case "CUSTOM":
				
				break;
			default:
				return;
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_gr.cache',$cache);
	}
	
	private function _guildsRanking() {
		switch($this->serverFiles) {
			case "MUE":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_GUILD_NAME_.","._CLMN_GUILD_MASTER_.","._CLMN_GUILD_SCORE_.","._CLMN_GUILD_LOGO_." FROM "._TBL_GUILD_." ORDER BY "._CLMN_GUILD_SCORE_." DESC");
				break;
			case "IGCN":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_GUILD_NAME_.","._CLMN_GUILD_MASTER_.","._CLMN_GUILD_SCORE_.","._CLMN_GUILD_LOGO_." FROM "._TBL_GUILD_." ORDER BY "._CLMN_GUILD_SCORE_." DESC");
				break;
			case "CUSTOM":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_GUILD_NAME_.","._CLMN_GUILD_MASTER_.","._CLMN_GUILD_SCORE_.","._CLMN_GUILD_LOGO_." FROM "._TBL_GUILD_." ORDER BY "._CLMN_GUILD_SCORE_." DESC");
				break;
			default:
				return;
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_guilds.cache',$cache);
	}
	
	private function _masterlevelRanking() {
		switch($this->serverFiles) {
			case "MUE":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." t1."._CLMN_ML_NAME_.", t1."._CLMN_ML_LVL_.", t2."._CLMN_CHR_CLASS_.", t2."._CLMN_CHR_RSTS_." FROM "._TBL_MASTERLVL_." AS t1 INNER JOIN "._TBL_CHR_." AS t2 ON t1."._CLMN_ML_NAME_." = t2."._CLMN_CHR_NAME_." WHERE t1."._CLMN_ML_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY t1."._CLMN_ML_LVL_." DESC, t2."._CLMN_CHR_RSTS_." DESC");
				break;
			case "IGCN":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.", "._CLMN_CHR_MLVL_.", "._CLMN_CHR_CLASS_.", "._CLMN_CHR_RSTS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") ORDER BY "._CLMN_CHR_MLVL_." DESC");
				break;
			case "CUSTOM":
				
				break;
			default:
				return;
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_master.cache',$cache);
	}
	
	private function _gensRanking() {
		switch($this->serverFiles) {
			case "MUE":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.", "._CLMN_CHR_GENS_CONT_.", "._CLMN_CHR_GENS_TYPE_.", "._CLMN_CHR_GENS_RANK_.", "._CLMN_CHR_CLASS_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_GENS_KNIGHT_." = 1 ORDER BY "._CLMN_CHR_GENS_CONT_." DESC");
				break;
			case "IGCN":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." gens."._CLMN_GENS_NAME_.", gens."._CLMN_GENS_POINT_.", gens."._CLMN_GENS_TYPE_.", gens."._CLMN_GENS_RANK_.", char."._CLMN_CHR_CLASS_." as cClass FROM "._TBL_GENS_." as gens INNER JOIN "._TBL_CHR_." as char ON gens."._CLMN_GENS_NAME_." = char."._CLMN_CHR_NAME_." ORDER BY gens."._CLMN_GENS_POINT_." DESC");
				break;
			case "CUSTOM":
				
				break;
			default:
				return;
		}
		if(!is_array($result)) return;

		$cache = BuildCacheData($result);
		UpdateCache('rankings_gens.cache',$cache);
	}
	
	private function _pvplaststandRanking() {
		switch($this->serverFiles) {
			case "MUE":
				$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." "._CLMN_CHR_NAME_.", "._CLMN_CHR_CLASS_.", "._CLMN_CHR_PVPLS_WIN_." FROM "._TBL_CHR_." ORDER BY "._CLMN_CHR_PVPLS_WIN_." DESC");
				break;
			case "IGCN":
				
				break;
			case "CUSTOM":
				
				break;
			default:
				return;
		}
		if(!is_array($result)) return;
		
		$cache = BuildCacheData($result);
		UpdateCache('rankings_pvplaststand.cache',$cache);
	}
	
	private function _votesRanking() {
		$voteMonth = date("m/01/Y 00:00");
		$voteMonthTimestamp = strtotime($voteMonth);
		$result = $this->db->query_fetch("SELECT TOP ".$this->_results." user_id,COUNT(*) as count FROM WEBENGINE_VOTE_LOGS WHERE timestamp >= ? GROUP BY user_id ORDER BY count DESC", array($voteMonthTimestamp));
		if(!is_array($result)) return;
		
		$finalResult = array();
		foreach($result as $data) {
			$common = new common($this->mu, $this->me);
			
			$accountInfo = $common->accountInformation($data['user_id']);
			if(!is_array($accountInfo)) continue;
			
			$Character = new Character();
			$characterName = $Character->AccountCharacterIDC($accountInfo[_CLMN_USERNM_]);
			if(!check_value($characterName)) continue;
			
			if(in_array($characterName, $this->_excludedCharacters)) continue;
			
			$finalResult[] = array($characterName, $data['count']);
		}
		
		$cache = BuildCacheData($finalResult);
		UpdateCache('rankings_votes.cache',$cache);
	}
	
	private function _onlineRanking() {
		switch($this->serverFiles) {
			case "MUE":
				if($this->config['SQL_USE_2_DB']) {
					$muLogEx = $this->me->query_fetch("SELECT TOP ".$this->_results." "._CLMN_LOGEX_ACCID_.", sum("._CLMN_LOGEX_OD_.") as TotalOnlineTime FROM "._TBL_LOGEX_." GROUP BY "._CLMN_LOGEX_ACCID_." ORDER BY TotalOnlineTime DESC");
					if(is_array($muLogEx)) {
						$result = array();
						$character = new Character();
						foreach($muLogEx as $key => $thisUser) {
							$characterName = $character->AccountCharacterIDC($thisUser[_CLMN_LOGEX_ACCID_]);
							$characterData = $character->CharacterData($characterName);
							$result[$key] = array($characterName, $thisUser['TotalOnlineTime'], $characterData[_CLMN_CHR_CLASS_]);
						}
					}
				} else {
					$result = $this->mu->query_fetch("SELECT TOP ".$this->_results." tb2."._CLMN_GAMEIDC_.", sum(tb1."._CLMN_LOGEX_OD_.") AS TotalOnlineDuration, tb3."._CLMN_CHR_CLASS_." FROM "._TBL_LOGEX_." AS tb1 INNER JOIN "._TBL_AC_." AS tb2 ON tb1."._CLMN_LOGEX_ACCID_." = tb2."._CLMN_AC_ID_." INNER JOIN "._TBL_CHR_." AS tb3 ON tb2."._CLMN_GAMEIDC_." = tb3."._CLMN_CHR_NAME_." WHERE tb3."._CLMN_CHR_NAME_." NOT IN(".$this->_rankingsExcludeChars().") GROUP BY tb1."._CLMN_LOGEX_ACCID_.", tb2."._CLMN_GAMEIDC_.", tb3."._CLMN_CHR_CLASS_." ORDER BY TotalOnlineDuration DESC");
				}
				break;
			case "IGCN":
				
				break;
			case "CUSTOM":
				
				break;
			default:
				return;
		}
		if(!is_array($result)) return;
		
		$cache = BuildCacheData($result);
		UpdateCache('rankings_online.cache',$cache);
	}
	
	public function rankingsMenu() {
		$rankings_menu = array(
			array(lang('rankings_txt_1',true), 'level', mconfig('rankings_enable_level')),
			array(lang('rankings_txt_2',true), 'resets', mconfig('rankings_enable_resets')),
			array(lang('rankings_txt_3',true), 'killers', mconfig('rankings_enable_pk')),
			array(lang('rankings_txt_4',true), 'guilds', mconfig('rankings_enable_guilds')),
			array(lang('rankings_txt_5',true), 'grandresets', mconfig('rankings_enable_gr')),
			array(lang('rankings_txt_6',true), 'online', mconfig('rankings_enable_online')),
			array(lang('rankings_txt_7',true), 'votes', mconfig('rankings_enable_votes')),
			array(lang('rankings_txt_8',true), 'gens', mconfig('rankings_enable_gens')),
			array(lang('rankings_txt_22',true), 'master', mconfig('rankings_enable_master')),
			array(lang('rankings_txt_24',true), 'pvplaststand', mconfig('rankings_enable_pvplaststand')),
		);

		echo '<div class="rankings_menu">';
		
		foreach($rankings_menu as $rm_item) {
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

}