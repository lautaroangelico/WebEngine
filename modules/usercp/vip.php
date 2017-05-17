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

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_17',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$Character = new Character();
	$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);
	if(!is_array($AccountCharacters)) throw new Exception(lang('error_46',true));
	
	# IGCN VIP System
	if(strtolower(config('server_files',true)) == 'igcn') {
		
		$vipTypes = array(
			1 => array(
				'name' => 'Bronze',
				'cost' => mconfig('igcn_bronze_cost'),
			),
			2 => array(
				'name' => 'Silver',
				'cost' => mconfig('igcn_silver_cost'),
			),
			3 => array(
				'name' => 'Gold',
				'cost' => mconfig('igcn_gold_cost'),
			),
			4 => array(
				'name' => 'Platinum',
				'cost' => mconfig('igcn_platinum_cost'),
			)
		);
		
		$vipSystem = new Vip();
		$vipSystem->setUserid($_SESSION['userid']);
		
		# vip process
		if(check_value($_POST['submit'])) {
			try {
				if(!check_value($_POST['type'])) throw new Exception(lang('error_25',true));
				$vipSystem->setPackage($_POST['type']);
				if($vipSystem->isVip()) {
					# extend
					$vipSystem->extendVip();
					message('success', lang('success_13',true));
				} else {
					# buy
					$vipSystem->buyVip();
					message('success', lang('success_14',true));
				}
			} catch(Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
		
		$button_txt = ($vipSystem->isVip() ? lang('vip_txt_3',true) : lang('vip_txt_2',true));
		
		# current VIP status
		if($vipSystem->isVip()) {
			$vipInfo = $vipSystem->getVipData();
			$vipDays = sec_to_dhms(strtotime($vipInfo[_CLMN_VIP_DATE_])-time());
			$vipPackage = $vipTypes[$vipInfo[_CLMN_VIP_TYPE_]]['name'];
			message('info', langf('vip_txt_1', array($vipDays[0])) . ' ('.$vipPackage.')');
		}
		
		echo '<table class="table general-table-ui">';
			echo '<tr>';
				echo '<td>'.lang('vip_txt_4',true).'</td>';
				echo '<td>'.lang('vip_txt_5',true).'</td>';
				echo '<td>'.lang('vip_txt_6',true).'</td>';
				echo '<td></td>';
			echo '</tr>';
			
			foreach($vipTypes as $vipType => $vipPackage) {
				echo '<form action="" method="post">';
					echo '<input type="hidden" name="type" value="'.$vipType.'">';
					echo '<tr>';
						echo '<td>'.$vipPackage['name'].'</td>';
						echo '<td>'.langf('vip_txt_7', array(30)).'</td>';
						echo '<td>'.number_format($vipPackage['cost']).' '.lang('vip_txt_8',true).'</td>';
						echo '<td><button type="submit" name="submit" value="submit" class="btn btn-primary">'.$button_txt.'</button></td>';
					echo '</tr>';
				echo '</form>';
			}
		echo '</table>';
	}
	
	# MuEngine VIP System
	if(strtolower(config('server_files',true)) == 'mue') {
		$VipSystem = new Vip();
		
		if(check_value($_POST['submit'])) {
			$VipSystem->VipProcess($_SESSION['userid'],$_POST['vip_package']);
		}
		
		if($VipSystem->isVIP($_SESSION['userid'])) {
			if(check_value($AccountInfo[_CLMN_VIP_STAMP_])) {
				message('warning', langf('vip_txt_1', array($VipSystem->RemainingVIP($AccountInfo[_CLMN_VIP_STAMP_]))));
			}
			$button_txt = lang('vip_txt_3',true);
		} else {
			$button_txt = lang('vip_txt_2',true);
		}
		
		echo '<table class="table general-table-ui">';
			echo '<tr>';
				echo '<td>'.lang('vip_txt_4',true).'</td>';
				echo '<td>'.lang('vip_txt_5',true).'</td>';
				echo '<td>'.lang('vip_txt_6',true).'</td>';
				echo '<td></td>';
			echo '</tr>';
			
			$vplans = mconfig('vip_plans');
			
			if(array_key_exists(0, $vplans)) {
				# multiple plans
				foreach($vplans as $packageId => $vipPlan) {
					$discount = $vipPlan['discount_percent'];
					$vipCost = $VipSystem->CalculatePlanCost($vipPlan['days'], $discount);
					$discount_txt = ($discount >= 1 ? langf('vip_txt_9', array(number_format($discount))) : '');
					
					echo '<form action="" method="post">';
						echo '<input type="hidden" name="vip_package" value="'.$packageId.'"/>';
						echo '<tr>';
							echo '<td>'.$vipPlan['title'].'</td>';
							echo '<td>'.langf('vip_txt_7', array($vipPlan['days'])).'</td>';
							echo '<td>'.number_format($vipCost).' '.lang('vip_txt_8',true).''.$discount_txt.'</td>';
							echo '<td><button type="submit" name="submit" value="submit" class="btn btn-primary">'.$button_txt.'</button></td>';
						echo '</tr>';
					echo '</form>';
				}
			} else {
				# single plan
				$discount = $vplans['discount_percent'];
				$vipCost = $VipSystem->CalculatePlanCost($vplans['days'], $discount);
				$discount_txt = ($discount >= 1 ? langf('vip_txt_9', array(number_format($discount))) : '');
				
				echo '<form action="" method="post">';
					echo '<tr>';
						echo '<td>'.$vplans['title'].'</td>';
						echo '<td>'.langf('vip_txt_7', array($vplans['days'])).'</td>';
						echo '<td>'.number_format($vipCost).' '.lang('vip_txt_8',true).''.$discount_txt.'</td>';
						echo '<td><button type="submit" name="submit" value="submit" class="btn btn-primary">'.$button_txt.'</button></td>';
					echo '</tr>';
				echo '</form>';
			}
		echo '</table>';
	
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}