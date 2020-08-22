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

echo '<h2>Add Stats Settings</h2>';

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
	
	if(!check_value($_POST['setting_5'])) throw new Exception('Invalid setting (required_level)');
	if(!Validator::UnsignedNumber($_POST['setting_5'])) throw new Exception('Invalid setting (required_level)');
	if($_POST['setting_5'] > 400) throw new Exception('The required level setting can have a maximum value of 400.');
	$xml->required_level = $_POST['setting_5'];
	
	if(!check_value($_POST['setting_6'])) throw new Exception('Invalid setting (required_master_level)');
	if(!Validator::UnsignedNumber($_POST['setting_6'])) throw new Exception('Invalid setting (required_master_level)');
	$xml->required_master_level = $_POST['setting_6'];
	
	if(!check_value($_POST['setting_7'])) throw new Exception('Invalid setting (max_stats)');
	if(!Validator::UnsignedNumber($_POST['setting_7'])) throw new Exception('Invalid setting (max_stats)');
	$xml->max_stats = $_POST['setting_7'];
	
	if(!check_value($_POST['setting_8'])) throw new Exception('Invalid setting (minimum_limit)');
	if(!Validator::UnsignedNumber($_POST['setting_8'])) throw new Exception('Invalid setting (minimum_limit)');
	$xml->minimum_limit = $_POST['setting_8'];
	
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

$creditSystem = new CreditSystem();
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the add stats module.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Zen Cost<br/><span>Amount of zen required to add stats. Set to 0 to disable zen requirement.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_2" value="<?php echo mconfig('zen_cost'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Cost<br/><span>Amount of credits required to add stats. Set to 0 to disable credit requirement.</span></th>
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
			<th>Required Level<br/><span>Minimum level required to add stats. Set to 0 to disable level requirement.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_5" value="<?php echo mconfig('required_level'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Required Master Level<br/><span>Minimum master level required to add stats. Set to 0 to disable master level requirement.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_6" value="<?php echo mconfig('required_master_level'); ?>"/>
			</td>
		</tr>
		
		<tr>
			<th>Maximum Stats<br/><span>Amount of points that each stat may have.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_7" value="<?php echo mconfig('max_stats'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Minimum Points Limit<br/><span>Minimum amount of points the player must add in order to use the module.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_8" value="<?php echo mconfig('minimum_limit'); ?>"/>
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>