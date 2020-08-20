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

echo '<h2>Reset Settings</h2>';

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
	
	// active
	if(!check_value($_POST['setting_1'])) throw new Exception('Invalid setting (active)');
	if(!in_array($_POST['setting_1'], array(0, 1))) throw new Exception('Invalid setting (active)');
	$xml->active = $_POST['setting_1'];
	
	// maximum_resets
	if(!check_value($_POST['setting_6'])) throw new Exception('Invalid setting (maximum_resets)');
	if(!Validator::UnsignedNumber($_POST['setting_6'])) throw new Exception('Invalid setting (maximum_resets)');
	$xml->maximum_resets = $_POST['setting_6'];
	
	// keep_stats
	if(!check_value($_POST['setting_7'])) throw new Exception('Invalid setting (keep_stats)');
	if(!in_array($_POST['setting_7'], array(0, 1))) throw new Exception('Invalid setting (keep_stats)');
	$xml->keep_stats = $_POST['setting_7'];
	
	// clear_inventory
	if(!check_value($_POST['setting_10'])) throw new Exception('Invalid setting (clear_inventory)');
	if(!in_array($_POST['setting_10'], array(0, 1))) throw new Exception('Invalid setting (clear_inventory)');
	$xml->clear_inventory = $_POST['setting_10'];
	
	// revert_class_evolution
	if(!check_value($_POST['setting_11'])) throw new Exception('Invalid setting (revert_class_evolution)');
	if(!in_array($_POST['setting_11'], array(0, 1))) throw new Exception('Invalid setting (revert_class_evolution)');
	$xml->revert_class_evolution = $_POST['setting_11'];
	
	////////////
	////////////
	
	// required_level
	if(!check_value($_POST['setting_5'])) throw new Exception('Invalid setting (required_level)');
	if(!Validator::UnsignedNumber($_POST['setting_5'])) throw new Exception('Invalid setting (required_level)');
	$xml->required_level = $_POST['setting_5'];
	
	// zen_cost
	if(!check_value($_POST['setting_2'])) throw new Exception('Invalid setting (zen_cost)');
	if(!Validator::UnsignedNumber($_POST['setting_2'])) throw new Exception('Invalid setting (zen_cost)');
	$xml->zen_cost = $_POST['setting_2'];
	
	// credit_cost
	if(!check_value($_POST['setting_4'])) throw new Exception('Invalid setting (credit_cost)');
	if(!Validator::UnsignedNumber($_POST['setting_4'])) throw new Exception('Invalid setting (credit_cost)');
	$xml->credit_cost = $_POST['setting_4'];
	
	// credit_config
	if(!check_value($_POST['setting_3'])) throw new Exception('Invalid setting (credit_config)');
	if(!Validator::UnsignedNumber($_POST['setting_3'])) throw new Exception('Invalid setting (credit_config)');
	$xml->credit_config = $_POST['setting_3'];
	
	////////////
	////////////
	
	// points_reward
	if(!check_value($_POST['setting_8'])) throw new Exception('Invalid setting (points_reward)');
	if(!Validator::UnsignedNumber($_POST['setting_8'])) throw new Exception('Invalid setting (points_reward)');
	$xml->points_reward = $_POST['setting_8'];
	
	// multiply_points_by_resets
	if(!check_value($_POST['setting_9'])) throw new Exception('Invalid setting (multiply_points_by_resets)');
	if(!in_array($_POST['setting_9'], array(0, 1))) throw new Exception('Invalid setting (multiply_points_by_resets)');
	$xml->multiply_points_by_resets = $_POST['setting_9'];
	
	// credit_reward
	if(!check_value($_POST['setting_12'])) throw new Exception('Invalid setting (credit_reward)');
	if(!Validator::UnsignedNumber($_POST['setting_12'])) throw new Exception('Invalid setting (credit_reward)');
	$xml->credit_reward = $_POST['setting_12'];
	
	// credit_reward_config
	if(!check_value($_POST['setting_13'])) throw new Exception('Invalid setting (credit_reward_config)');
	if(!Validator::UnsignedNumber($_POST['setting_13'])) throw new Exception('Invalid setting (credit_reward_config)');
	$xml->credit_reward_config = $_POST['setting_13'];
	
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

$creditSystem = new CreditSystem();
?>
<form action="" method="post">
	
	<h3>General</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the reset module.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Maximum Resets<br/><span>Maximum allowed number of resets each character may have.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_6" value="<?php echo mconfig('maximum_resets'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Keep Stats<br/><span>If enabled, the character's stats will not be reverted back to its base stats.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_7',mconfig('keep_stats'),'Yes (keep stats)','No (reset stats)'); ?>
			</td>
		</tr>
		<tr>
			<th>Clear Inventory<br/><span>Clears the character's inventory.<br /><br /><span style="color:red;">* Enabling this setting will also clear the character's equipment</span></span></th>
			<td>
				<?php enabledisableCheckboxes('setting_10',mconfig('clear_inventory'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Revert Class Evolution<br/><span>Example: If the character is a Soul Master, after performing the reset the character's class will become Dark Wizard.<br /><br /><span style="color:red;">* Enabling this setting will also clear the character's quests</span></span></th>
			<td>
				<?php enabledisableCheckboxes('setting_11',mconfig('revert_class_evolution'),'Yes','No'); ?>
			</td>
		</tr>
	</table>
	
	<h3>Requirements</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Required Level<br/><span>Minimum level required to perform a reset.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_5" value="<?php echo mconfig('required_level'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Zen Cost<br/><span>Amount of zen required to reset the character. Set to 0 to disable zen requirement.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_2" value="<?php echo mconfig('zen_cost'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Cost<br/><span>Amount of credits required to reset the character. Set to 0 to disable credit requirement.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_4" value="<?php echo mconfig('credit_cost'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Cost Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_3", mconfig('credit_config'), "form-control"); ?>
			</td>
		</tr>
	</table>
	
	<h3>Reward</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Level Up Points Reward<br/><span>Amount of level up points to be given to the character after the reset. Set to 0 to disable.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_8" value="<?php echo mconfig('points_reward'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Multiply Level Up Points by Resets<br/><span>If enabled, the amount of level up points reward will be multiplied by the amount of resets.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_9',mconfig('multiply_points_by_resets'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Credit Reward<br/><span>Amount of credits to be rewarded on each reset to the character. Set to 0 to disable credit reward.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_12" value="<?php echo mconfig('credit_reward'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Reward Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_13", mconfig('credit_reward_config'), "form-control"); ?>
			</td>
		</tr>
	</table>
	
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>