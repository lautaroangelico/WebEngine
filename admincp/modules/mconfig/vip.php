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

echo '<h2>V.I.P. Settings (MuEngine)</h2>';

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.vip.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->vip_day_cost = $_POST['setting_5'];
	
	$save = $xml->asXML($xmlPath);
	if($save) {
		message('success','Settings successfully saved.');
	} else {
		message('error','There has been an error while saving changes.');
	}
}

function addPackage($title,$days,$discount) {
	if(check_value($title) && check_value($days)) {
		
		if(!check_value($discount)) {
			$discount = 0;
		}
		
		if($days < 1) {
			message('error','The package must add at least 1 day of VIP.');
			return;
		}
		
		$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.vip.xml';
		$xml = simplexml_load_file($xmlPath);
		
		$vip_plan = $xml->addChild('vip_plans');
		$vip_plan->addChild('title', $title);
		$vip_plan->addChild('days', $days);
		$vip_plan->addChild('discount_percent', $discount);

		$save = $xml->asXML($xmlPath);
		
		if($save) {
			message('success','VIP Package Successfully Added!');
		} else {
			message('error','There has been an error while adding the package.');
		}
		
	} else {
		message('error','Missing data (complete all fields).');
	}
}

function editPackage($id,$title,$days,$discount) {
	if(check_value($id) && check_value($title) && check_value($days)) {
	
		if(!check_value($discount)) {
			$discount = 0;
		}
		
		if($days < 1) {
			message('error','The package must add at least 1 day of VIP.');
			return;
		}
		
		$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.vip.xml';
		$xml = simplexml_load_file($xmlPath);
		
		$index = 0;
		foreach($xml->vip_plans as $plan) {
			if($index == $id) {
				$plan->title = $title;
				$plan->days = $days;
				$plan->discount_percent = $discount;
			}
			$index++;
		}

		$save = $xml->asXML($xmlPath);
		
		if($save) {
			message('success','VIP Package Successfully Edited!');
		} else {
			message('error','There has been an error while editing the package.');
		}
	
	} else {
		message('error','Missing data (complete all fields).');
	}
}

function deletePackage($id) {
	if(check_value($id)) {
		$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.vip.xml';
		$xml = simplexml_load_file($xmlPath);
		
		$index = 0;
		foreach($xml->vip_plans as $plan) {
			if($index == $id) {
				$dom = dom_import_simplexml($plan);
				$remove = $dom->parentNode->removeChild($dom);
				if(!$remove) {
					message('error','There has been an error while deleting the package.');
					return;
				} else {
					$removed = true;
				}
			}
			$index++;
		}
		
		if($removed) {
			$save = $xml->asXML($xmlPath);
			if($save) {
				message('success','VIP Package Successfully Deleted!');
			} else {
				message('error','There has been an error while deleting the package.');
			}
		} else {
			message('error','No VIP package found with the requested id.');
		}
		
		
	} else {
		message('error','Missing data (complete all fields).');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

if(check_value($_POST['package_add_submit'])) {
	addPackage($_POST['package_add_title'],$_POST['package_add_days'],$_POST['package_add_discount']);
}

if(check_value($_POST['package_edit_submit'])) {
	editPackage($_POST['package_edit_id'],$_POST['package_edit_title'],$_POST['package_edit_days'],$_POST['package_edit_discount']);
}

if(check_value($_REQUEST['deleteplan'])) {
	deletePackage($_REQUEST['deleteplan']);
}

loadModuleConfigs('usercp.vip');

message('','In order for the VIP system to function properly, make sure that in your MuEngine\'s <b>vip.ini</b> config file, you set the setting <b>VipDays</b> to <b>1</b>!','IMPORTANT:');
?>
<form action="index.php?module=modules_manager&config=vip" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the vip module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>VIP Day Cost<br/><span>Set the cost of each VIP day.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_5" value="<?=mconfig('vip_day_cost')?>"/> credit(s)
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>

<hr>
<h3>Manage VIP Packages</h3>
<?php
if(array_key_exists('vip_plans', $mconfig)) {
	echo '<table class="table table-striped table-bordered table-hover module_config_tables">';
	echo '<tr>';
	echo '<th>Package Title</th>';
	echo '<th>Days</th>';
	echo '<th>Discount Percent</th>';
	echo '<th></th>';
	echo '</tr>';
	
	if(array_key_exists(0,$mconfig['vip_plans'])) {
		foreach($mconfig['vip_plans'] as $planKEY => $thisPLAN) {
			echo '<form action="index.php?module=modules_manager&config=vip" method="post">';
			echo '<input type="hidden" name="package_edit_id" value="'.$planKEY.'"/>';
			echo '<tr>';
			echo '<td><input name="package_edit_title" class="input-xxlarge" type="text" value="'.$thisPLAN['title'].'"/></td>';
			echo '<td><input name="package_edit_days" class="input-mini" type="text" value="'.$thisPLAN['days'].'"/> day(s)</td>';
			echo '<td><input name="package_edit_discount" class="input-mini" type="text" value="'.$thisPLAN['discount_percent'].'"/> %</td>';
			echo '<td>
			<input type="submit" class="btn btn-block" name="package_edit_submit" value="Save"/>
			<a href="index.php?module=modules_manager&config=vip&deleteplan='.$planKEY.'" class="btn btn-danger btn-block">Remove</a>
			</td>';
			echo '</tr>';
			echo '</form>';
		}
	} else {
		echo '<form action="index.php?module=modules_manager&config=vip" method="post">';
		echo '<input type="hidden" name="package_edit_id" value="0"/>';
		echo '<tr>';
		echo '<td><input name="package_edit_title" class="input-xxlarge" type="text" value="'.$mconfig['vip_plans']['title'].'"/></td>';
		echo '<td><input name="package_edit_days" class="input-mini" type="text" value="'.$mconfig['vip_plans']['days'].'"/> day(s)</td>';
		echo '<td><input name="package_edit_discount" class="input-mini" type="text" value="'.$mconfig['vip_plans']['discount_percent'].'"/> %</td>';
		echo '<td>
		<input type="submit" class="btn btn-block" name="package_edit_submit" value="Save"/>
		<a href="index.php?module=modules_manager&config=vip&deleteplan=0" class="btn btn-danger btn-block">Remove</a>
		</td>';
		echo '</tr>';
		echo '</form>';
	}
	
	echo '<form action="index.php?module=modules_manager&config=vip" method="post">';
	echo '<tr>';
	echo '<td><input name="package_add_title" class="input-xxlarge" type="text"/></td>';
	echo '<td><input name="package_add_days" class="input-mini" type="text"/> day(s)</td>';
	echo '<td><input name="package_add_discount" class="input-mini" type="text"/> %</td>';
	echo '<td><input type="submit" name="package_add_submit" class="btn btn-success" value="Add!"/></td>';
	echo '</tr>';
	echo '</form>';
	
	echo '</table>';
} else {
	echo '<h4>Add VIP Plan</h4>';
	echo '<table class="table table-striped table-bordered table-hover module_config_tables">';
	echo '<tr>';
	echo '<th>Package Title</th>';
	echo '<th>Days</th>';
	echo '<th>Discount Percent</th>';
	echo '<th></th>';
	echo '</tr>';
	echo '<form action="index.php?module=modules_manager&config=vip" method="post">';
	echo '<tr>';
	echo '<td><input name="package_add_title" class="input-xxlarge" type="text"/></td>';
	echo '<td><input name="package_add_days" class="input-mini" type="text"/> day(s)</td>';
	echo '<td><input name="package_add_discount" class="input-mini" type="text"/> %</td>';
	echo '<td><input type="submit" name="package_add_submit" class="btn btn-success" value="Add!"/></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
}

?>