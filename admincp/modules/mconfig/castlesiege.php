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
<h2>Castle Siege Settings</h2>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'castlesiege.xml';
	$xml = simplexml_load_file($xmlPath);

	$xml->active = $_POST['setting_5'];
	$xml->enable_banner = $_POST['setting_1'];
	$xml->cs_battle_day = $_POST['setting_2'];
	$xml->cs_battle_time = $_POST['setting_3'];
	$xml->cs_battle_duration = $_POST['setting_4'];
	
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

loadModuleConfigs('castlesiege');

message('','Only works for weekly (every 7 days) Castle-Siege schedule!','BETA:');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the castle siege module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_5',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Banner Status<br/><span>Enable/disable the castle siege countdown banner.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('enable_banner'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>CS Battle Day<br/><span>Options:<ol><li>Monday</li><li>Tuesday</li><li>Wednesday</li><li>Thursday</li><li>Friday</li><li>Saturday</li><li>Sunday</li></ol></span></th>
			<td>
				<input class="input-mini" type="text" name="setting_2" value="<?=mconfig('cs_battle_day')?>"/>
			</td>
		</tr>
		<tr>
			<th>CS Battle Time<br/><span>Time when the battle starts (24 hour format!)</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_3" value="<?=mconfig('cs_battle_time')?>"/> 24-hour format (hh:mm:ss)
			</td>
		</tr>
		<tr>
			<th>CS Battle Duration<br/><span>Battle duration in MINUTES.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_4" value="<?=mconfig('cs_battle_duration')?>"/> minutes
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>