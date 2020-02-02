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

class Character {
	
	function __construct() {
		
		// load databases
		$this->muonline = Connection::Database('MuOnline');
		
		// common
		$this->common = new common();
		
	}

	function CharacterReset($username,$character_name,$userid) {
		try {
			if(!check_value($username)) throw new Exception(lang('error_23',true));
			if(!check_value($character_name)) throw new Exception(lang('error_23',true));
			if(!Validator::Number($userid)) throw new Exception(lang('error_23',true));
			if(!Validator::UsernameLength($username)) throw new Exception(lang('error_23',true));
			if(!Validator::AlphaNumeric($username)) throw new Exception(lang('error_23',true));
			if(!$this->CharacterExists($character_name)) throw new Exception(lang('error_32',true));
			if(!$this->CharacterBelongsToAccount($character_name,$username)) throw new Exception(lang('error_32',true));
			if($this->common->accountOnline($username)) throw new Exception(lang('error_14',true));
			
			$characterData = $this->CharacterData($character_name);
			if($characterData[_CLMN_CHR_LVL_] < mconfig('resets_required_level')) throw new Exception(lang('error_33',true));
			
			if(mconfig('resets_enable_zen_requirement')) {
				if($characterData[_CLMN_CHR_ZEN_] < mconfig('resets_price_zen')) throw new Exception(lang('error_34',true));
				$deductZen = $this->DeductZEN($character_name, mconfig('resets_price_zen'));
				if(!$deductZen) throw new Exception(lang('error_34',true));
			}
			
			$update = $this->muonline->query("UPDATE "._TBL_CHR_." SET "._CLMN_CHR_LVL_." = 1,"._CLMN_CHR_RSTS_." = "._CLMN_CHR_RSTS_." + 1 WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
			if(!$update) throw new Exception(lang('error_23',true));
			
			// SUCCESS
			message('success', lang('success_8',true));
			
			if(mconfig('resets_enable_credit_reward')) {
				try {
					$creditSystem = new CreditSystem();
					$creditSystem->setConfigId(mconfig('credit_config'));
					$configSettings = $creditSystem->showConfigs(true);
					switch($configSettings['config_user_col_id']) {
						case 'userid':
							$creditSystem->setIdentifier($_SESSION['userid']);
							break;
						case 'username':
							$creditSystem->setIdentifier($_SESSION['username']);
							break;
						case 'character':
							$creditSystem->setIdentifier($character_name);
							break;
						default:
							throw new Exception("Invalid identifier (credit system).");
					}
					$creditSystem->addCredits(mconfig('resets_credits_reward'));
					
					message('success', langf('resetcharacter_txt_8', array(mconfig('resets_credits_reward'))));
				} catch (Exception $ex) {}
			}
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	function CharacterResetStats($username,$character_name,$userid) {
		try {
			if(!check_value($username)) throw new Exception(lang('error_23',true));
			if(!check_value($character_name)) throw new Exception(lang('error_23',true));
			if(!Validator::Number($userid)) throw new Exception(lang('error_23',true));
			if(!Validator::UsernameLength($username)) throw new Exception(lang('error_23',true));
			if(!Validator::AlphaNumeric($username)) throw new Exception(lang('error_23',true));
			if(!$this->CharacterExists($character_name)) throw new Exception(lang('error_35',true));
			if(!$this->CharacterBelongsToAccount($character_name,$username)) throw new Exception(lang('error_35',true));
			if($this->common->accountOnline($username)) throw new Exception(lang('error_14',true));
			
			$characterData = $this->CharacterData($character_name);
			
			if(mconfig('resetstats_enable_zen_requirement')) {
				if($characterData[_CLMN_CHR_ZEN_] < mconfig('resetstats_price_zen')) throw new Exception(lang('error_34',true));
				$deductZen = $this->DeductZEN($character_name, mconfig('resetstats_price_zen'));
				if(!$deductZen) throw new Exception(lang('error_34',true));
			}
			
			// some data
			$new_stats = mconfig('resetstats_new_stats');
			$chr_str = $characterData[_CLMN_CHR_STAT_STR_];
			$chr_agi = $characterData[_CLMN_CHR_STAT_AGI_];
			$chr_vit = $characterData[_CLMN_CHR_STAT_VIT_];
			$chr_ene = $characterData[_CLMN_CHR_STAT_ENE_];
			$chr_cmd = $characterData[_CLMN_CHR_STAT_CMD_];
			
			if($chr_cmd >= 1) {
				$levelup_points = ($chr_str+$chr_agi+$chr_vit+$chr_ene+$chr_cmd) - ($new_stats*5);
				if($levelup_points < 1) { $levelup_points = 0; }
				$update_query = "UPDATE "._TBL_CHR_." SET 
				"._CLMN_CHR_STAT_STR_." = :newstats, 
				"._CLMN_CHR_STAT_AGI_." = :newstats, 
				"._CLMN_CHR_STAT_VIT_." = :newstats, 
				"._CLMN_CHR_STAT_ENE_." = :newstats, 
				"._CLMN_CHR_STAT_CMD_." = :newstats, 
				"._CLMN_CHR_LVLUP_POINT_." = "._CLMN_CHR_LVLUP_POINT_." + :lvlp 
				WHERE "._CLMN_CHR_NAME_." = :chr";
			} else {
				$levelup_points = (($chr_str+$chr_agi+$chr_vit+$chr_ene)-($new_stats*4));
				if($levelup_points < 1) { $levelup_points = 0; }
				$update_query = "UPDATE "._TBL_CHR_." SET 
				"._CLMN_CHR_STAT_STR_." = :newstats, 
				"._CLMN_CHR_STAT_AGI_." = :newstats, 
				"._CLMN_CHR_STAT_VIT_." = :newstats, 
				"._CLMN_CHR_STAT_ENE_." = :newstats, 
				"._CLMN_CHR_LVLUP_POINT_." = "._CLMN_CHR_LVLUP_POINT_." + :lvlp
				WHERE "._CLMN_CHR_NAME_." = :chr";
			}
			
			$update = $this->muonline->query($update_query, array('newstats' => $new_stats, 'lvlp' => $levelup_points, 'chr' => $character_name));
			if(!$update) throw new Exception(lang('error_23',true));
			
			// SUCCESS
			message('success', lang('success_9',true));
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	function CharacterClearPK($username,$character_name) {
		try {
			if(!check_value($username)) throw new Exception(lang('error_23',true));
			if(!check_value($character_name)) throw new Exception(lang('error_23',true));
			if(!Validator::UsernameLength($username)) throw new Exception(lang('error_23',true));
			if(!Validator::AlphaNumeric($username)) throw new Exception(lang('error_23',true));
			if(!$this->CharacterExists($character_name)) throw new Exception(lang('error_36',true));
			if(!$this->CharacterBelongsToAccount($character_name,$username)) throw new Exception(lang('error_36',true));
			if($this->common->accountOnline($username)) throw new Exception(lang('error_14',true));
			
			$characterData = $this->CharacterData($character_name);
			if(mconfig('clearpk_enable_zen_requirement')) {
				if($characterData[_CLMN_CHR_ZEN_] < mconfig('clearpk_price_zen')) throw new Exception(lang('error_34',true));
				$deductZen = $this->DeductZEN($character_name, mconfig('clearpk_price_zen'));
				if(!$deductZen) throw new Exception(lang('error_34',true));
			}
			
			$update = $this->muonline->query("UPDATE "._TBL_CHR_." SET "._CLMN_CHR_PK_LEVEL_." = 3,"._CLMN_CHR_PK_TIME_." = 0 WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
			if(!$update) throw new Exception(lang('error_23',true));
			
			// SUCCESS
			message('success', lang('success_10',true));
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	function CharacterUnstick($username,$character_name) {
		try {
			if(!check_value($username)) throw new Exception(lang('error_23',true));
			if(!check_value($character_name)) throw new Exception(lang('error_23',true));
			if(!Validator::UsernameLength($username)) throw new Exception(lang('error_23',true));
			if(!Validator::AlphaNumeric($username)) throw new Exception(lang('error_23',true));
			if(!$this->CharacterExists($character_name)) throw new Exception(lang('error_37',true));
			if(!$this->CharacterBelongsToAccount($character_name,$username)) throw new Exception(lang('error_37',true));
			if($this->common->accountOnline($username)) throw new Exception(lang('error_14',true));
			
			$characterData = $this->CharacterData($character_name);
			if(mconfig('unstick_enable_zen_requirement')) {
				if($characterData[_CLMN_CHR_ZEN_] < mconfig('unstick_price_zen')) throw new Exception(lang('error_34',true));
				$deductZen = $this->DeductZEN($character_name, mconfig('unstick_price_zen'));
				if(!$deductZen) throw new Exception(lang('error_34',true));
			}
			
			// MOVE CHARACTER TO LORENCIA BAR (DEFAULT COORDS)
			$update = $this->moveCharacter($character_name,0,125,125);
			if(!$update) throw new Exception(lang('error_23',true));
			
			// SUCCESS
			message('success', lang('success_11',true));
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	function CharacterClearSkillTree($username,$character_name) {
		try {
			if(!check_value($username)) throw new Exception(lang('error_23',true));
			if(!check_value($character_name)) throw new Exception(lang('error_23',true));
			if(!Validator::UsernameLength($username)) throw new Exception(lang('error_23',true));
			if(!Validator::AlphaNumeric($username)) throw new Exception(lang('error_23',true));
			if(!$this->CharacterExists($character_name)) throw new Exception(lang('error_38',true));
			if(!$this->CharacterBelongsToAccount($character_name,$username)) throw new Exception(lang('error_38',true));
			if($this->common->accountOnline($username)) throw new Exception(lang('error_14',true));
			
			$characterData = $this->CharacterData($character_name);
			if(mconfig('clearst_enable_zen_requirement')) {
				if($characterData[_CLMN_CHR_ZEN_] < mconfig('clearst_price_zen')) throw new Exception(lang('error_34',true));
				$deductZen = $this->DeductZEN($character_name, mconfig('clearst_price_zen'));
				if(!$deductZen) throw new Exception(lang('error_34',true));
			}
			
			if($characterMLData[_CLMN_ML_LVL_] < mconfig('clearst_required_level')) throw new Exception(lang('error_39',true).mconfig('clearst_required_level'));
			
			// CLEAR CHARACTER MASTER SKILL TREE DATA
			$update = $this->resetMasterLevelData($character_name);
			if(!$update) throw new Exception(lang('error_23',true));
			
			// CLEAR MAGICLIST DATA
			$update_2 = $this->resetMagicList($character_name);
			if(!$update_2) throw new Exception(lang('error_23',true));
			
			// SUCCESS
			message('success', lang('success_12',true));
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	function CharacterAddStats($username,$character_name,$str=0,$agi=0,$vit=0,$ene=0,$com=0) {
		global $custom;
		try {
			if(!check_value($username)) throw new Exception(lang('error_23',true));
			if(!check_value($character_name)) throw new Exception(lang('error_23',true));
			if(!Validator::UsernameLength($username)) throw new Exception(lang('error_23',true));
			if(!Validator::AlphaNumeric($username)) throw new Exception(lang('error_23',true));
			if(!$this->CharacterExists($character_name)) throw new Exception(lang('error_64',true));
			if(!$this->CharacterBelongsToAccount($character_name,$username)) throw new Exception(lang('error_64',true));
			if($this->common->accountOnline($username)) throw new Exception(lang('error_14',true));
			
			$characterData = $this->CharacterData($character_name);
			
			if($str < 1) { $str = 0; }
			if($agi < 1) { $agi = 0; }
			if($vit < 1) { $vit = 0; }
			if($ene < 1) { $ene = 0; }
			if($com < 1) { $com = 0; }
			
			$total_add_points = $str+$agi+$vit+$ene+$com;
			if($total_add_points < mconfig('addstats_minimum_add_points')) throw new Exception(lang('error_54',true).mconfig('addstats_minimum_add_points'));
			if($total_add_points > $characterData[_CLMN_CHR_LVLUP_POINT_]) throw new Exception(lang('error_51',true));
			
			if($com >= 1) {
				if(!in_array($characterData[_CLMN_CHR_CLASS_], $custom['character_cmd'])) throw new Exception(lang('error_52',true));
			}
			
			$max_stats = mconfig('addstats_max_stats');
			$sum_str = $str+$characterData[_CLMN_CHR_STAT_STR_];
			$sum_agi = $agi+$characterData[_CLMN_CHR_STAT_AGI_];
			$sum_vit = $vit+$characterData[_CLMN_CHR_STAT_VIT_];
			$sum_ene = $ene+$characterData[_CLMN_CHR_STAT_ENE_];
			$sum_com = $com+$characterData[_CLMN_CHR_STAT_CMD_];
			
			if($sum_str > $max_stats) throw new Exception(lang('error_53',true));
			if($sum_agi > $max_stats) throw new Exception(lang('error_53',true));
			if($sum_vit > $max_stats) throw new Exception(lang('error_53',true));
			if($sum_ene > $max_stats) throw new Exception(lang('error_53',true));
			if($sum_com > $max_stats) throw new Exception(lang('error_53',true));
			
			if(mconfig('addstats_enable_zen_requirement')) {
				if($characterData[_CLMN_CHR_ZEN_] < mconfig('addstats_price_zen')) throw new Exception(lang('error_34',true));
				$deductZen = $this->DeductZEN($character_name, mconfig('addstats_price_zen'));
				if(!$deductZen) throw new Exception(lang('error_34',true));
			}
			
			$query = $this->muonline->query("UPDATE "._TBL_CHR_." SET 
			"._CLMN_CHR_STAT_STR_." = "._CLMN_CHR_STAT_STR_." + ?,
			"._CLMN_CHR_STAT_AGI_." = "._CLMN_CHR_STAT_AGI_." + ?,
			"._CLMN_CHR_STAT_VIT_." = "._CLMN_CHR_STAT_VIT_." + ?,
			"._CLMN_CHR_STAT_ENE_." = "._CLMN_CHR_STAT_ENE_." + ?,
			"._CLMN_CHR_STAT_CMD_." = "._CLMN_CHR_STAT_CMD_." + ?,
			"._CLMN_CHR_LVLUP_POINT_." = "._CLMN_CHR_LVLUP_POINT_." - ? 
			WHERE "._CLMN_CHR_NAME_." = ?", array($str, $agi, $vit, $ene, $com, $total_add_points, $character_name));
			if(!$query) throw new Exception(lang('error_23',true));
			
			// SUCCESS
			message('success',lang('success_17',true));
			
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	function AccountCharacter($username) {
		if(!check_value($username)) return;
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		
		$result = $this->muonline->query_fetch("SELECT "._CLMN_CHR_NAME_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_ACCID_." = ?", array($username));
		if(!is_array($result)) return;
		
		foreach($result as $row) {
			if(!check_value($row[_CLMN_CHR_NAME_])) continue;
			$return[] = $row[_CLMN_CHR_NAME_];
		}
		
		if(!is_array($return)) return;
		return $return;
	}
	
	function CharacterData($character_name) {
		if(!check_value($character_name)) return;
		$result = $this->muonline->query_fetch_single("SELECT * FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
		if(!is_array($result)) return;
		return $result;
		
	}
	
	function CharacterBelongsToAccount($character_name,$username) {
		if(!check_value($character_name)) return;
		if(!check_value($username)) return;
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		$characterData = $this->CharacterData($character_name);
		if(!is_array($characterData)) return;
		if(strtolower($characterData[_CLMN_CHR_ACCID_]) != strtolower($username)) return;
		return true;
		
	}
	
	function CharacterExists($character_name) {
		if(!check_value($character_name)) return;
		$check = $this->muonline->query_fetch_single("SELECT * FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
		if(!is_array($check)) return;
		return true;
	}
	
	function DeductZEN($character_name,$zen_amount) {
		if(!check_value($character_name)) return;
		if(!check_value($zen_amount)) return;
		if(!Validator::UnsignedNumber($zen_amount)) return;
		if($zen_amount < 1) return;
		if(!$this->CharacterExists($character_name)) return;
		$characterData = $this->CharacterData($character_name);
		if(!is_array($characterData)) return;
		if($characterData[_CLMN_CHR_ZEN_] < $zen_amount) return;
		$deduct = $this->muonline->query("UPDATE "._TBL_CHR_." SET "._CLMN_CHR_ZEN_." = "._CLMN_CHR_ZEN_." - ? WHERE "._CLMN_CHR_NAME_." = ?", array($zen_amount, $character_name));
		if(!$deduct) return;
		return true;
	}
	
	function moveCharacter($character_name,$map=0,$x=125,$y=125) {
		if(!check_value($character_name)) return;
		$move = $this->muonline->query("UPDATE "._TBL_CHR_." SET "._CLMN_CHR_MAP_." = ?, "._CLMN_CHR_MAP_X_." = ?, "._CLMN_CHR_MAP_Y_." = ? WHERE "._CLMN_CHR_NAME_." = ?", array($map, $x, $y, $character_name));
		if(!$move) return;
		return true;
	}
	
	function AccountCharacterIDC($username) {
		if(!check_value($username)) return;
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		$data = $this->muonline->query_fetch_single("SELECT * FROM "._TBL_AC_." WHERE "._CLMN_AC_ID_." = ?", array($username));
		if(!is_array($data)) return;
		return $data[_CLMN_GAMEIDC_];
	}
	
	// To be removed (backwards compatibility)
	function GenerateCharacterClassAvatar($code=0,$alt=true,$img_tags=true) {
		return getPlayerClassAvatar($code, $img_tags, $alt, 'tables-character-class-img');
	}
	
	function getMasterLevelInfo($character_name) {
		if(!check_value($character_name)) return;
		if(!$this->CharacterExists($character_name)) return;
		$CharInfo = $this->muonline->query_fetch_single("SELECT * FROM "._TBL_MASTERLVL_." WHERE "._CLMN_ML_NAME_." = ?", array($character_name));
		if(!is_array($CharInfo)) return;
		return $CharInfo;
	}
	
	function resetMasterLevelData($character_name) {
		if(!check_value($character_name)) return;
		if(!$this->CharacterExists($character_name)) return;
		if(defined(_CLMN_ML_NEXP_)) {
			$reset = $this->muonline->query("UPDATE "._TBL_MASTERLVL_." SET "._CLMN_ML_LVL_." = 0,"._CLMN_ML_EXP_." = 0,"._CLMN_ML_NEXP_." = '35507050',"._CLMN_ML_POINT_." = 0 WHERE "._CLMN_ML_NAME_." = ?", array($character_name));
		} else {
			$reset = $this->muonline->query("UPDATE "._TBL_MASTERLVL_." SET "._CLMN_ML_LVL_." = 0,"._CLMN_ML_EXP_." = 0,"._CLMN_ML_POINT_." = 0 WHERE "._CLMN_ML_NAME_." = ?", array($character_name));
		}
		if(!$reset) return;
		return true;
	}
	
	function resetMagicList($character_name) {
		if(!check_value($character_name)) return;
		if(!$this->CharacterExists($character_name)) return;
		$reset = $this->muonline->query("UPDATE "._TBL_CHR_." SET "._CLMN_CHR_MAGIC_L_." = null WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
		if(!$reset) return;
		return true;
	}
	
}