<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

echo '<h2>Unstick Character Settings</h2>';

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.unstick.xml';
	$xml = simplexml_load_file($xmlPath);
	
	if(!check_value($_POST['setting_1'])) throw new Exception('Invalid setting (active)');
	if(!in_array($_POST['setting_1'], array(0, 1))) throw new Exception('Invalid setting (active)');
	$xml->active = $_POST['setting_1'];
	
	if(!check_value($_POST['setting_2'])) throw new Exception('Invalid setting (zen_cost)');
	if(!Validator::UnsignedNumber($_POST['setting_2'])) throw new Exception('Invalid setting (zen_cost)');
	$xml->zen_cost = $_POST['setting_2'];
	
	if(!check_value($_POST['setting_3'])) throw new Exception('Invalid setting (credit_config)');
	if(!Validator::UnsignedNumber($_POST['setting_3'])) throw new Exception('Invalid setting (credit_config)');
	$xml->credit_config = $_POST['setting_3'];
	
	if(!check_value($_POST['setting_4'])) throw new Exception('Invalid setting (credit_cost)');
	if(!Validator::UnsignedNumber($_POST['setting_4'])) throw new Exception('Invalid setting (credit_cost)');
	$xml->credit_cost = $_POST['setting_4'];
	
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

loadModuleConfigs('usercp.unstick');

$creditSystem = new CreditSystem();
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the character unstick module.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Zen Cost<br/><span>Amount of zen required to unstick the character. Set to 0 to disable zen requirement.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_2" value="<?php echo mconfig('zen_cost'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Cost<br/><span>Amount of credits required to unstick the character. Set to 0 to disable credit requirement.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_4" value="<?php echo mconfig('credit_cost'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_3", mconfig('credit_config'), "form-control"); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>