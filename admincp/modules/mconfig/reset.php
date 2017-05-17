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

echo '<h2>Character Reset Settings</h2>';

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.reset.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->resets_enable_zen_requirement = $_POST['setting_2'];
	$xml->resets_price_zen = $_POST['setting_3'];
	$xml->resets_required_level = $_POST['setting_4'];
	$xml->resets_enable_credit_reward = $_POST['setting_5'];
	$xml->resets_credits_reward = $_POST['setting_6'];
	$xml->credit_config = $_POST['setting_7'];
	
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

loadModuleConfigs('usercp.reset');

$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the character reset module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Zen Requirement<br/></th>
			<td>
				<? enabledisableCheckboxes('setting_2',mconfig('resets_enable_zen_requirement'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Zen<br/><span>If zen requirement is enabled, set the price of this feature.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_3" value="<?=mconfig('resets_price_zen')?>"/>
			</td>
		</tr>
		<tr>
			<th>Required Level<br/><span>Required level to reset.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_4" value="<?=mconfig('resets_required_level')?>"/>
			</td>
		</tr>
		<tr>
			<th>Credits Reward<br/><span>Enable/disable giving credit(s) reward for every reset.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_5',mconfig('resets_enable_credit_reward'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Reward<br/><span>If credits reward is enabled, set the amount of credits that will be rewarded for every reset.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_6" value="<?=mconfig('resets_credits_reward')?>"/> credit(s)
			</td>
		</tr>
		<tr>
			<th>Credit Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_7", mconfig('credit_config'), "form-control"); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>