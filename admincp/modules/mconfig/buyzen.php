<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

echo '<h2>Buy Zen Settings</h2>';
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.buyzen.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->max_zen = $_POST['setting_2'];
	$xml->exchange_ratio = $_POST['setting_3'];
	$xml->increment_rate = $_POST['setting_5'];
	$xml->credit_config = $_POST['setting_4'];
	
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

loadModuleConfigs('usercp.buyzen');

$creditSystem = new CreditSystem();
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the buy zen module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Max Zen<br/><span>Maximum zen a character can have</span></th>
			<td>
				<input class="input-small" type="text" name="setting_2" value="<?=mconfig('max_zen')?>"/>
			</td>
		</tr>
		<tr>
			<th>Exchange Rate<br/><span>How much zen does 1 CREDIT equals to.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_3" value="<?=mconfig('exchange_ratio')?>"/>
			</td>
		</tr>
		<tr>
			<th>Increment Rate<br/><span>The larger the value, the less options there will be in the dropdown menu.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_5" value="<?=mconfig('increment_rate')?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_4", mconfig('credit_config'), "form-control"); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>