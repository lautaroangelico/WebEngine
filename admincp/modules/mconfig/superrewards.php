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
<h1 class="page-header">SuperRewards Settings</h1>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	// SUPER REWARDS
	$xmlPath = __PATH_MODULE_CONFIGS__.'donation.superrewards.xml';
	$xml = simplexml_load_file($xmlPath);
	$xml->active = $_POST['setting_10'];
	$xml->sr_h = $_POST['setting_11'];
	$xml->sr_secret = $_POST['setting_12'];
	$xml->sr_conversion_rate = $_POST['setting_13'];
	$xml->credit_config = $_POST['setting_14'];
	$save3 = $xml->asXML($xmlPath);
	
	if($save3) {
		message('success','[Super Rewards] Settings successfully saved.');
	} else {
		message('error','[Super Rewards] There has been an error while saving changes.');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('donation.superrewards');

$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the super rewards donation gateway.<br/><br/>More info:<br/><a href="http://www.superrewards.com/" target="_blank">http://www.superrewards.com/</a></span></th>
			<td>
				<? enabledisableCheckboxes('setting_10',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>App ID<br/></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_11" value="<?=mconfig('sr_h')?>"/>
			</td>
		</tr>
		<tr>
			<th>Secret Key<br/></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_12" value="<?=mconfig('sr_secret')?>"/>
			</td>
		</tr>
		<tr>
			<th>Credits Conversion Rate<br/><span>How many game credits is equivalent to 1 of real money currency.<br/><br/>Example:<br/>1 USD = 100 Credits, in this example you would type in the box 100.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_13" value="<?=mconfig('sr_conversion_rate')?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_14", mconfig('credit_config'), "form-control"); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>	
</form>