<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

class Vip {
	
	function VipProcess($userid,$plan) {
		global $common;
		if(check_value($userid) && Validator::Number($userid)) {
			$planData = $this->PlanInfo($plan);
			$userData = $common->accountInformation($userid);
			if(is_array($userData) && is_array($planData)) {
			
				// data
				$username = $userData[_CLMN_USERNM_];
				$vipstamp = $userData[_CLMN_VIP_STAMP_];
				$plandays = $planData['days'];
				$plandiscount = $planData['discount_percent'];
				$planprice = $this->CalculatePlanCost($plandays,$plandiscount);
				
				if(!$common->accountOnline($username)) {
					// deduct credits
					$deduct = $common->substractCredits($userid,$planprice);
					if($deduct) {
						if($this->isVIP($userid)) {
							// Extend Timestamp
							$new_timestamp = $this->CalculateExtension($vipstamp,$plandays);
							$update = $common->updateVipTimeStamp($userid,$new_timestamp);
							if($update) {
								// success
								message('success', lang('success_13',true));
							} else {
								// unkown error
								message('error', lang('error_23',true));
							}
						} else {
							
							// New timestamp
							$new_timestamp = $this->CalculateTimestamp($plandays);
							
							$update = $common->updateVipTimeStamp($userid,$new_timestamp);
							if($update) {
								// success
								message('success', lang('success_14',true));
							} else {
								// unkown error
								message('error', lang('error_23',true));
							}
						}
					} else {
						// not enough credits
						message('error', lang('error_40',true));
					}
				} else {
					// account is online
					message('error', lang('error_14',true));
				}
			} else {
				// invalid user (unknown error)
				message('error', lang('error_23',true));
			}
		} else {
			// missing data (unknown error)
			message('error', lang('error_23',true));
		}
	}
	
	function PackageExists($id) {
		$VipPlans = mconfig('vip_plans');
		if(is_array($VipPlans)) {
			if(array_key_exists(0, $VipPlans)) {
				if(is_array($VipPlans[$id])) {
					return true;
				}
			} else {
				return true;
			}
		}
	}
	
	function PlanInfo($id) {
		if($this->PackageExists($id)) {
			$VipPlans = mconfig('vip_plans');
			if(array_key_exists(0, $VipPlans)) {
				return $VipPlans[$id];
			} else {
				return $VipPlans;
			}
		}
	}
	
	function CalculateTimestamp($days) {
		if(check_value($days) && Validator::Number($days) && $days >= 1) {
			$vip_days = 60*60*24*$days;
			$free_time = 3600;
			$result = time() + $free_time + $vip_days;
			return $result;
		}
	}
	
	function CalculateExtension($timestamp,$days) {
		if(check_value($timestamp) && check_value($days) && Validator::Number($days) && $days >= 1) {
			$vip_days = 60*60*24*$days;
			$result = $timestamp + $vip_days;
			return $result;
		}
	}
	
	function isVIP($userid) {
		global $common;
		if(check_value($userid) && Validator::Number($userid)) {
			$accountInfo = $common->accountInformation($userid);
			if($accountInfo) {
				$vipTimestamp = $accountInfo[_CLMN_VIP_STAMP_];
				if($vipTimestamp > time()) {
					return true;
				}
			}
		}
	}
	
	function RemainingVIP($timestamp) {
		if(check_value($timestamp) && Validator::Number($timestamp)) {
			$calculate = ($timestamp - time()) / (60*60*24);
			return round($calculate);
		}
	}
	
	function CalculatePlanCost($days,$discount=0) {
		if(check_value($days) && Validator::Number($days)) {
			$vip_day_cost = mconfig('vip_day_cost');
			$calculate_days_cost = round($days*$vip_day_cost);
			if($discount >= 1) {
				// Formula: original price - ( (discount % / 100) * original price )
				$discounted_price = $calculate_days_cost - (($discount/100)*$calculate_days_cost);
				return round($discounted_price);
			} else {
				return round($calculate_days_cost);
			}
		}
	}

}