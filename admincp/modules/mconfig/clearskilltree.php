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
?>
<h2>Clear Skill-Tree Settings</h2>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.clearskilltree.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->clearst_enable_zen_requirement = $_POST['setting_2'];
	$xml->clearst_price_zen = $_POST['setting_3'];
	$xml->clearst_required_level = $_POST['setting_4'];
	//$xml->clearst_reset_method = $_POST['setting_5'];
	
	$save = $xml->asXML($xmlPath);
	if($save) {
		message('success','Settings successfully saved.');
	} else {
		message('error','There has been an error while saving changes.');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('usercp.clearskilltree');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the clear skill-tree module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Zen Requirement<br/></th>
			<td>
				<? enabledisableCheckboxes('setting_2',mconfig('clearst_enable_zen_requirement'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Zen<br/><span>If zen requirement is enabled, set the price of this feature.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_3" value="<?=mconfig('clearst_price_zen')?>"/>
			</td>
		</tr>
		<tr>
			<th>Required Level<br/><span>Required Master-Level to clear Skill-Tree.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_4" value="<?=mconfig('clearst_required_level')?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>