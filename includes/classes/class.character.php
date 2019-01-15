<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.1.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class Character {

	function CharacterReset($username,$character_name,$userid) {
		global $dB,$common;
		if(check_value($username) && check_value($character_name)) {
			if(!Validator::Number($userid)) { $error = true; }
			if(!Validator::UsernameLength($username)) { $error = true; }
			if(!Validator::AlphaNumeric($username)) { $error = true; }
			if(!$error) {
				$character_name = Decode($character_name);
				if($this->CharacterExists($character_name) && $this->CharacterBelongsToAccount($character_name,$username)) {
					if(!$common->accountOnline($username)) {
					
						$characterData = $this->CharacterData($character_name);
						if($this->hasRequiredLevel($characterData[_CLMN_CHR_LVL_])) {
							if(mconfig('resets_enable_zen_requirement')) {
								$deductZen = $this->DeductZEN($character_name, mconfig('resets_price_zen'));
								if($deductZen) {
									$zen_ok = true;
								} else {
									$zen_ok = false;
								}
							} else {
								$zen_ok = true;
							}
							
							if($zen_ok) {
								
								$update = $dB->query("UPDATE "._TBL_CHR_." SET 
								"._CLMN_CHR_LVL_." = 1,
								"._CLMN_CHR_RSTS_." = "._CLMN_CHR_RSTS_." + 1 
								WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
								
								if($update) {
									
									// SUCCESS
									message('success', lang('success_8',true));
									
									if(mconfig('resets_enable_credit_reward')) {
										try {
											$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);
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
									
								} else {
									// unknown error (could not update database)
									message('error', lang('error_23',true));
								}
								
							} else {
								// not enough zen
								message('error', lang('error_34',true));
							}
						} else {
							// does not have the required level
							message('error', lang('error_33',true));
						}
					} else {
						// account is online
						message('error', lang('error_14',true));
					}
				} else {
					// character doesnt exist or does not belong to account
					message('error', lang('error_32',true));
				}
			} else {
				// unknown error (bad username)
				message('error', lang('error_23',true));
			}
		} else {
			// unknown error (incomplete data)
			message('error', lang('error_23',true));
		}
	}
	
	function CharacterResetStats($username,$character_name,$userid) {
		global $dB,$common;
		if(check_value($username) && check_value($character_name)) {
			if(!Validator::Number($userid)) { $error = true; }
			if(!Validator::UsernameLength($username)) { $error = true; }
			if(!Validator::AlphaNumeric($username)) { $error = true; }
			if(!$error) {
				$character_name = Decode($character_name);
				if($this->CharacterExists($character_name) && $this->CharacterBelongsToAccount($character_name,$username)) {
					if(!$common->accountOnline($username)) {
					
						$characterData = $this->CharacterData($character_name);
							if(mconfig('resetstats_enable_zen_requirement')) {
								$deductZen = $this->DeductZEN($character_name, mconfig('resetstats_price_zen'));
								if($deductZen) {
									$zen_ok = true;
								} else {
									$zen_ok = false;
								}
							} else {
								$zen_ok = true;
							}
							
							if($zen_ok) {
								
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
								
								$update = $dB->query($update_query, array('newstats' => $new_stats, 'lvlp' => $levelup_points, 'chr' => $character_name));
								
								if($update) {
									
									// SUCCESS
									message('success', lang('success_9',true));
									
								} else {
									// unknown error (could not update database)
									message('error', lang('error_23',true));
								}
								
							} else {
								// not enough zen
								message('error', lang('error_34',true));
							}
					} else {
						// account is online
						message('error', lang('error_14',true));
					}
				} else {
					// character doesnt exist or does not belong to account
					message('error', lang('error_35',true));
				}
			} else {
				// unknown error (bad username)
				message('error', lang('error_23',true));
			}
		} else {
			// unknown error (incomplete data)
			message('error', lang('error_23',true));
		}
	}
	
	function CharacterClearPK($username,$character_name) {
		global $dB,$common;
		if(check_value($username) && check_value($character_name)) {
			if(!Validator::UsernameLength($username)) { $error = true; }
			if(!Validator::AlphaNumeric($username)) { $error = true; }
			if(!$error) {
				$character_name = Decode($character_name);
				if($this->CharacterExists($character_name) && $this->CharacterBelongsToAccount($character_name,$username)) {
					if(!$common->accountOnline($username)) {
					
						$characterData = $this->CharacterData($character_name);
						if(mconfig('clearpk_enable_zen_requirement')) {
							$deductZen = $this->DeductZEN($character_name, mconfig('clearpk_price_zen'));
							if($deductZen) {
								$zen_ok = true;
							} else {
								$zen_ok = false;
							}
						} else {
							$zen_ok = true;
						}
						
						if($zen_ok) {
							
							$update = $dB->query("UPDATE "._TBL_CHR_." SET 
							"._CLMN_CHR_PK_LEVEL_." = 3,
							"._CLMN_CHR_PK_TIME_." = 0 
							WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
							
							if($update) {
								
								// SUCCESS
								message('success', lang('success_10',true));
								
							} else {
								// unknown error (could not update database)
								message('error', lang('error_23',true));
							}
							
						} else {
							// not enough zen
							message('error', lang('error_34',true));
						}
					} else {
						// account is online
						message('error', lang('error_14',true));
					}
				} else {
					// character doesnt exist or does not belong to account
					message('error', lang('error_36',true));
				}
			} else {
				// unknown error (bad username)
				message('error', lang('error_23',true));
			}
		} else {
			// unknown error (incomplete data)
			message('error', lang('error_23',true));
		}
	}
	
	function CharacterUnstick($username,$character_name) {
		global $common;
		if(check_value($username) && check_value($character_name)) {
			if(!Validator::UsernameLength($username)) { $error = true; }
			if(!Validator::AlphaNumeric($username)) { $error = true; }
			if(!$error) {
				$character_name = Decode($character_name);
				if($this->CharacterExists($character_name) && $this->CharacterBelongsToAccount($character_name,$username)) {
					if(!$common->accountOnline($username)) {
					
						$characterData = $this->CharacterData($character_name);
						if(mconfig('unstick_enable_zen_requirement')) {
							$deductZen = $this->DeductZEN($character_name, mconfig('unstick_price_zen'));
							if($deductZen) {
								$zen_ok = true;
							} else {
								$zen_ok = false;
							}
						} else {
							$zen_ok = true;
						}
						
						if($zen_ok) {
							
							// MOVE CHARACTER TO LORENCIA BAR (DEFAULT COORDS)
							$update = $this->moveCharacter($character_name,0,125,125);
							
							if($update) {
								
								// SUCCESS
								message('success', lang('success_11',true));
								
							} else {
								// unknown error (could not update database)
								message('error', lang('error_23',true));
							}
							
						} else {
							// not enough zen
							message('error', lang('error_34',true));
						}
					} else {
						// account is online
						message('error', lang('error_14',true));
					}
				} else {
					// character doesnt exist or does not belong to account
					message('error', lang('error_37',true));
				}
			} else {
				// unknown error (bad username)
				message('error', lang('error_23',true));
			}
		} else {
			// unknown error (incomplete data)
			message('error', lang('error_23',true));
		}
	}
	
	function CharacterClearSkillTree($username,$character_name) {
		global $common;
		if(check_value($username) && check_value($character_name)) {
			if(!Validator::UsernameLength($username)) { $error = true; }
			if(!Validator::AlphaNumeric($username)) { $error = true; }
			if(!$error) {
				$character_name = Decode($character_name);
				if($this->CharacterExists($character_name) && $this->CharacterBelongsToAccount($character_name,$username)) {
					if(!$common->accountOnline($username)) {
					
						$characterData = $this->CharacterData($character_name);
						$characterMLData = $this->getMasterLevelInfo($character_name);
						
						if(mconfig('clearst_enable_zen_requirement')) {
							$deductZen = $this->DeductZEN($character_name, mconfig('clearst_price_zen'));
							if($deductZen) {
								$zen_ok = true;
							} else {
								$zen_ok = false;
							}
						} else {
							$zen_ok = true;
						}
						
						if($zen_ok) {
							if($this->hasRequiredMasterLevel($characterMLData[_CLMN_ML_LVL_])) {
								// CLEAR CHARACTER MASTER SKILL TREE DATA
								$update = $this->resetMasterLevelData($character_name);
								
								// CLEAR MAGICLIST DATA
								$update_2 = $this->resetMagicList($character_name);
								
								if($update && $update_2) {
									message('success',lang('success_12',true));
								} else {
									message('error', lang('error_23',true));
								}
							} else {
								message('error', lang('error_39',true).mconfig('clearst_required_level'));
							}
						} else {
							// not enough zen
							message('error', lang('error_34',true));
						}
					} else {
						// account is online
						message('error', lang('error_14',true));
					}
				} else {
					// character doesnt exist or does not belong to account
					message('error', lang('error_38',true));
				}
			} else {
				// unknown error (bad username)
				message('error', lang('error_23',true));
			}
		} else {
			// unknown error (incomplete data)
			message('error', lang('error_23',true));
		}
	}
	
	function CharacterAddStats($username,$character_name,$str=0,$agi=0,$vit=0,$ene=0,$com=0) {
		global $dB,$common,$custom;
		if(check_value($username) && check_value($character_name)) {
			if(!Validator::UsernameLength($username)) { $error = true; }
			if(!Validator::AlphaNumeric($username)) { $error = true; }
			if(!$error) {
				$character_name = Decode($character_name);
				if($this->CharacterExists($character_name) && $this->CharacterBelongsToAccount($character_name,$username)) {
					if(!$common->accountOnline($username)) {
					
						$characterData = $this->CharacterData($character_name);
						
							// DO THE THING HERE :D
							
							if($str < 1) { $str = 0; }
							if($agi < 1) { $agi = 0; }
							if($vit < 1) { $vit = 0; }
							if($ene < 1) { $ene = 0; }
							if($com < 1) { $com = 0; }
							
							$total_add_points = $str+$agi+$vit+$ene+$com;
							if($total_add_points >= mconfig('addstats_minimum_add_points')) {
								if($total_add_points <= $characterData[_CLMN_CHR_LVLUP_POINT_]) {
																		
									if(in_array($characterData[_CLMN_CHR_CLASS_], $custom['character_cmd'])) {
										# is DL
										$error = false;
									} else {
										# rest of classes
										if($com >= 1) {
											$error = true;
										} else {
											$error = false;
										}
									}
									
									if(!$error) {
										
										$max_stats = mconfig('addstats_max_stats');
										$sum_str = $str+$characterData[_CLMN_CHR_STAT_STR_];
										$sum_agi = $agi+$characterData[_CLMN_CHR_STAT_AGI_];
										$sum_vit = $vit+$characterData[_CLMN_CHR_STAT_VIT_];
										$sum_ene = $ene+$characterData[_CLMN_CHR_STAT_ENE_];
										$sum_com = $com+$characterData[_CLMN_CHR_STAT_CMD_];
										
										$error = false;
										if($sum_str > $max_stats) { $error = true; }
										if($sum_agi > $max_stats) { $error = true; }
										if($sum_vit > $max_stats) { $error = true; }
										if($sum_ene > $max_stats) { $error = true; }
										if($sum_com > $max_stats) { $error = true; }
										
										if(!$error) {
										
											if(mconfig('addstats_enable_zen_requirement')) {
												$deductZen = $this->DeductZEN($character_name, mconfig('addstats_price_zen'));
												if($deductZen) {
													$zen_ok = true;
												} else {
													$zen_ok = false;
												}
											} else {
												$zen_ok = true;
											}
											
											if($zen_ok) {
												$query = $dB->query("UPDATE "._TBL_CHR_." SET 
												"._CLMN_CHR_STAT_STR_." = "._CLMN_CHR_STAT_STR_." + ?,
												"._CLMN_CHR_STAT_AGI_." = "._CLMN_CHR_STAT_AGI_." + ?,
												"._CLMN_CHR_STAT_VIT_." = "._CLMN_CHR_STAT_VIT_." + ?,
												"._CLMN_CHR_STAT_ENE_." = "._CLMN_CHR_STAT_ENE_." + ?,
												"._CLMN_CHR_STAT_CMD_." = "._CLMN_CHR_STAT_CMD_." + ?,
												"._CLMN_CHR_LVLUP_POINT_." = "._CLMN_CHR_LVLUP_POINT_." - ? 
												WHERE "._CLMN_CHR_NAME_." = ?", array($str, $agi, $vit, $ene, $com, $total_add_points, $character_name));
												
												if($query) {
													// SUCCESS
													message('success',lang('success_17',true));
												} else {
													// didnt work - unexpected error
													message('error', lang('error_23',true));
												}
											} else {
												// not enough zen
												message('error', lang('error_34',true));
											}
											
										} else {
											// exceeded max stats
											message('error', lang('error_53',true));
										}
										
									} else {
										// adding command to non DL character
										message('error', lang('error_52',true));
									}
									
								} else {
									// not enough points
									message('error', lang('error_51',true));
								}
							} else {
								// need to add at least 1 point
								message('error', lang('error_54',true).mconfig('addstats_minimum_add_points'));
							}
					} else {
						// account is online
						message('error', lang('error_14',true));
					}
				} else {
					// character doesnt exist or does not belong to account
					message('error', lang('error_38',true));
				}
			} else {
				// unknown error (bad username or not numeric values)
				message('error', lang('error_23',true));
			}
		} else {
			// unknown error (incomplete data)
			message('error', lang('error_23',true));
		}
	}
	
	function AccountCharacter($username) {
		global $dB;
		if(!check_value($username)) return;
		if(!Validator::UsernameLength($username)) return;
		if(!Validator::AlphaNumeric($username)) return;
		
		$result = $dB->query_fetch("SELECT "._CLMN_CHR_NAME_." FROM "._TBL_CHR_." WHERE "._CLMN_CHR_ACCID_." = ?", array($username));
		if(!is_array($result)) return;
		
		foreach($result as $row) {
			if(!check_value($row[_CLMN_CHR_NAME_])) continue;
			$return[] = $row[_CLMN_CHR_NAME_];
		}
		
		if(!is_array($return)) return;
		return $return;
	}
	
	function CharacterData($character_name) {
		global $dB;
		if(check_value($character_name)) {
			$result = $dB->query_fetch_single("SELECT * FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
			if(is_array($result)) {
				return $result;
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
	
	function CharacterBelongsToAccount($character_name,$username) {
		if(check_value($character_name) && check_value($username)) {
			if(!Validator::UsernameLength($username)) { $error = true; }
			if(!Validator::AlphaNumeric($username)) { $error = true; }
			if(!$error) {
				$characterData = $this->CharacterData($character_name);
				if(is_array($characterData)) {
					if($characterData[_CLMN_CHR_ACCID_] == $username) {
						return true;
					}
				}
			}
		}
	}
	
	function CharacterExists($character_name) {
		global $dB;
		if(check_value($character_name)) {
			$check = $dB->query_fetch_single("SELECT * FROM "._TBL_CHR_." WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
			if(is_array($check)) {
				return true;
			}
		}
	}
	
	function DeductZEN($character_name,$zen_amount) {
		global $dB;
		if(check_value($character_name) && check_value($zen_amount) && $zen_amount >= 1 && Validator::Number($zen_amount)) {
			if($this->CharacterExists($character_name)) {
				$characterData = $this->CharacterData($character_name);
				if(is_array($characterData)) {
					if($characterData[_CLMN_CHR_ZEN_] >= $zen_amount) {
						$deduct = $dB->query("UPDATE "._TBL_CHR_." SET "._CLMN_CHR_ZEN_." = "._CLMN_CHR_ZEN_." - ? WHERE "._CLMN_CHR_NAME_." = ?", array($zen_amount, $character_name));
						if($deduct) {
							return true;
						}
					}
				}
			}
		}
	}
	
	function hasRequiredLevel($level) {
		if(check_value($level) && $level >= 1) {
			if($level >= mconfig('resets_required_level')) {
				return true;
			}
		}
	}
	
	function moveCharacter($character_name,$map=0,$x=125,$y=125) {
		global $dB;
		if(check_value($character_name)) {
			$move = $dB->query("UPDATE "._TBL_CHR_." SET "._CLMN_CHR_MAP_." = ?, "._CLMN_CHR_MAP_X_." = ?, "._CLMN_CHR_MAP_Y_." = ? WHERE "._CLMN_CHR_NAME_." = ?", array($map, $x, $y, $character_name));
			if($move) {
				return true;
			}
		}
	}
	
	function AccountCharacterIDC($username) {
		global $dB;
		if(check_value($username)) {
			if(!Validator::UsernameLength($username)) { $error = true; }
			if(!Validator::AlphaNumeric($username)) { $error = true; }
			if(!$error) {
				$data = $dB->query_fetch_single("SELECT * FROM "._TBL_AC_." WHERE "._CLMN_AC_ID_." = ?", array($username));
				if(is_array($data)) {
					return $data[_CLMN_GAMEIDC_];
				}
			}
		}
	}
	
	function GenerateCharacterClassAvatar($code=0,$alt=true,$img_tags=true) {
		global $custom;
		
		$fileName = (array_key_exists($code, $custom['character_class']) ? $custom['character_class'][$code][2] : 'avatar.jpg');
		$image = __PATH_TEMPLATE_IMG__ . 'character-avatars/' . $fileName;
		$name = $custom['character_class'][$code][0];
		if($img_tags) {
			if($alt) {
				return '<img class="tables-character-class-img" src="'.$image.'" data-toggle="tooltip" data-placement="top" title="'.$name.'" alt="'.$name.'"/>';
			} else {
				return '<img class="tables-character-class-img" src="'.$image.'" />';
			}
		} else {
			return $image;
		}
	}
	
	function getMasterLevelInfo($character_name) {
		global $dB;
		if(check_value($character_name)) {
			if($this->CharacterExists($character_name)) {
				$CharInfo = $dB->query_fetch_single("SELECT * FROM "._TBL_MASTERLVL_." WHERE "._CLMN_ML_NAME_." = ?", array($character_name));
				if(is_array($CharInfo)) {
					return $CharInfo;
				}
			}
		}
	}
	
	function hasRequiredMasterLevel($level) {
		if(check_value($level) && $level >= 1 && Validator::Number($level)) {
			if($level >= mconfig('clearst_required_level')) {
				return true;
			}
		}
	}
	
	function resetMasterLevelData($character_name) {
		global $dB;
		if(check_value($character_name)) {
			if($this->CharacterExists($character_name)) {
				$reset = $dB->query("UPDATE "._TBL_MASTERLVL_." SET "._CLMN_ML_LVL_." = 0,"._CLMN_ML_EXP_." = 0,"._CLMN_ML_NEXP_." = '35507050',"._CLMN_ML_POINT_." = 0 WHERE "._CLMN_ML_NAME_." = ?", array($character_name));
				if($reset) {
					return true;
				}
			}
		}
	}
	
	function resetMagicList($character_name) {
		global $dB;
		if(check_value($character_name)) {
			if($this->CharacterExists($character_name)) {
				$reset = $dB->query("UPDATE "._TBL_CHR_." SET "._CLMN_CHR_MAGIC_L_." = null WHERE "._CLMN_CHR_NAME_." = ?", array($character_name));
				if($reset) {
					return true;
				}
			}
		}
	}
	
}