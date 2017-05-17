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
<h2>Add Stats Settings</h2>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.addstats.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->addstats_enable_zen_requirement = $_POST['setting_2'];
	$xml->addstats_price_zen = $_POST['setting_3'];
	$xml->addstats_max_stats = $_POST['setting_4'];
	$xml->addstats_minimum_add_points = $_POST['setting_5'];
	
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

loadModuleConfigs('usercp.addstats');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the add stats module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Zen Requirement<br/></th>
			<td>
				<? enabledisableCheckboxes('setting_2',mconfig('addstats_enable_zen_requirement'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Zen<br/><span>If zen requirement is enabled, set the price of this feature.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_3" value="<?=mconfig('addstats_price_zen')?>"/>
			</td>
		</tr>
		<tr>
			<th>Max Stats<br/></th>
			<td>
				<input class="input-mini" type="text" name="setting_4" value="<?=mconfig('addstats_max_stats')?>"/>
			</td>
		</tr>
		<tr>
			<th>Minimum Level-Up Points to Add<br/><span>Minimum amount of level-up points to add in order to use the module.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_5" value="<?=mconfig('addstats_minimum_add_points')?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>