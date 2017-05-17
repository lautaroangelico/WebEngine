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

echo '<h2>V.I.P. Settings (IGCN)</h2>';

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
	$xml->igcn_bronze_cost = $_POST['setting_2'];
	$xml->igcn_silver_cost = $_POST['setting_3'];
	$xml->igcn_gold_cost = $_POST['setting_4'];
	$xml->igcn_platinum_cost = $_POST['setting_5'];
	$xml->credit_config = $_POST['setting_6'];
	
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

loadModuleConfigs('usercp.vip');

$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the VIP module.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Bronze Cost<br/><span>Define the price for 30 days of Bronze VIP.<br />Set to 0 to disable this package.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_2" value="<?php echo mconfig('igcn_bronze_cost'); ?>"/> credits
			</td>
		</tr>
		<tr>
			<th>Silver Cost<br/><span>Define the price for 30 days of Silver VIP.<br />Set to 0 to disable this package.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_3" value="<?php echo mconfig('igcn_silver_cost'); ?>"/> credits
			</td>
		</tr>
		<tr>
			<th>Gold Cost<br/><span>Define the price for 30 days of Gold VIP.<br />Set to 0 to disable this package.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_4" value="<?php echo mconfig('igcn_gold_cost'); ?>"/> credits
			</td>
		</tr>
		<tr>
			<th>Platinum Cost<br/><span>Define the price for 30 days of Platinum VIP.<br />Set to 0 to disable this package.</span></th>
			<td>
				<input class="input-small" type="text" name="setting_5" value="<?php echo mconfig('igcn_platinum_cost'); ?>"/> credits
			</td>
		</tr>
		<tr>
			<th>Credit Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_6", mconfig('credit_config'), "form-control"); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>