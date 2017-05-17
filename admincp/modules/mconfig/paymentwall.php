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

echo '<h1 class="page-header">Paymentwall Settings</h1>';

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	// SUPER REWARDS
	$xmlPath = __PATH_MODULE_CONFIGS__.'donation.paymentwall.xml';
	$xml = simplexml_load_file($xmlPath);
	$xml->active = $_POST['setting_1'];
	$xml->app_key = $_POST['setting_2'];
	$xml->secret_key = $_POST['setting_3'];
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

loadModuleConfigs('donation.paymentwall');

$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the paymentwall donation gateway.<br/><br/>More info:<br/><a href="https://www.paymentwall.com/" target="_blank">https://www.paymentwall.com/</a></span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Project Key (App Key)<br/></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_2" value="<?=mconfig('app_key')?>"/>
			</td>
		</tr>
		<tr>
			<th>Secret Key<br/></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_3" value="<?=mconfig('secret_key')?>"/>
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