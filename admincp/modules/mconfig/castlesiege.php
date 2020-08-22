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

echo '<h2>Castle Siege Settings</h2>';

function saveChanges() {
    global $_POST;
	
	$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
	
    $cfgFile = __PATH_CONFIGS__.'castlesiege.json';
	if(!is_writable($cfgFile)) throw new Exception('The configuration file is not writable.');
	$csConfig = file_get_contents($cfgFile);
	if(!$csConfig) throw new Exception('Error loading config file.');
	$csConfig = json_decode($csConfig, true);
	if(!is_array($csConfig)) throw new Exception('Error loading config file.');
	
	// active
	if(!Validator::UnsignedNumber($_POST['setting_1'])) throw new Exception('Submitted setting is not valid (active)');
	if(!in_array($_POST['setting_1'], array(1, 0))) throw new Exception('Submitted setting is not valid (active)');
	$csConfig['active'] = $_POST['setting_1'];
	
	// hide_idle
	if(!Validator::UnsignedNumber($_POST['setting_2'])) throw new Exception('Submitted setting is not valid (hide_idle)');
	if(!in_array($_POST['setting_2'], array(1, 0))) throw new Exception('Submitted setting is not valid (hide_idle)');
	$csConfig['hide_idle'] = $_POST['setting_2'];
	
	// live_data
	if(!Validator::UnsignedNumber($_POST['setting_3'])) throw new Exception('Submitted setting is not valid (live_data)');
	if(!in_array($_POST['setting_3'], array(1, 0))) throw new Exception('Submitted setting is not valid (live_data)');
	$csConfig['live_data'] = $_POST['setting_3'];
	
	// show_castle_owner
	if(!Validator::UnsignedNumber($_POST['setting_4'])) throw new Exception('Submitted setting is not valid (show_castle_owner)');
	if(!in_array($_POST['setting_4'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_castle_owner)');
	$csConfig['show_castle_owner'] = $_POST['setting_4'];
	
	// show_castle_owner_alliance
	if(!Validator::UnsignedNumber($_POST['setting_5'])) throw new Exception('Submitted setting is not valid (show_castle_owner_alliance)');
	if(!in_array($_POST['setting_5'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_castle_owner_alliance)');
	$csConfig['show_castle_owner_alliance'] = $_POST['setting_5'];
	
	// show_battle_countdown
	if(!Validator::UnsignedNumber($_POST['setting_6'])) throw new Exception('Submitted setting is not valid (show_battle_countdown)');
	if(!in_array($_POST['setting_6'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_battle_countdown)');
	$csConfig['show_battle_countdown'] = $_POST['setting_6'];
	
	// show_castle_information
	if(!Validator::UnsignedNumber($_POST['setting_7'])) throw new Exception('Submitted setting is not valid (show_castle_information)');
	if(!in_array($_POST['setting_7'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_castle_information)');
	$csConfig['show_castle_information'] = $_POST['setting_7'];
	
	// show_current_stage
	if(!Validator::UnsignedNumber($_POST['setting_8'])) throw new Exception('Submitted setting is not valid (show_current_stage)');
	if(!in_array($_POST['setting_8'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_current_stage)');
	$csConfig['show_current_stage'] = $_POST['setting_8'];
	
	// show_next_stage
	if(!Validator::UnsignedNumber($_POST['setting_9'])) throw new Exception('Submitted setting is not valid (show_next_stage)');
	if(!in_array($_POST['setting_9'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_next_stage)');
	$csConfig['show_next_stage'] = $_POST['setting_9'];
	
	// show_battle_duration
	if(!Validator::UnsignedNumber($_POST['setting_10'])) throw new Exception('Submitted setting is not valid (show_battle_duration)');
	if(!in_array($_POST['setting_10'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_battle_duration)');
	$csConfig['show_battle_duration'] = $_POST['setting_10'];
	
	// show_registered_guilds
	if(!Validator::UnsignedNumber($_POST['setting_11'])) throw new Exception('Submitted setting is not valid (show_registered_guilds)');
	if(!in_array($_POST['setting_11'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_registered_guilds)');
	$csConfig['show_registered_guilds'] = $_POST['setting_11'];
	
	// show_schedule
	if(!Validator::UnsignedNumber($_POST['setting_12'])) throw new Exception('Submitted setting is not valid (show_schedule)');
	if(!in_array($_POST['setting_12'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_schedule)');
	$csConfig['show_schedule'] = $_POST['setting_12'];
	
	// schedule_date_format
	if(!check_value($_POST['setting_13'])) throw new Exception('Submitted setting is not valid (schedule_date_format)');
	$csConfig['schedule_date_format'] = $_POST['setting_13'];
	
	// show_widget
	if(!Validator::UnsignedNumber($_POST['setting_14'])) throw new Exception('Submitted setting is not valid (show_widget)');
	if(!in_array($_POST['setting_14'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_widget)');
	$csConfig['show_widget'] = $_POST['setting_14'];
	
	// SCHEDULE
	
	// start_day
	if(count($_POST['setting_stage_startday']) != count($csConfig['stages'])) throw new Exception('Schedule stages settings array size is not valid.');
	foreach($_POST['setting_stage_startday'] as $key => $row) {
		$csConfig['stages'][$key]['start_day'] = $row;
	}
	
	// start_time
	if(count($_POST['setting_stage_starttime']) != count($csConfig['stages'])) throw new Exception('Schedule stages settings array size is not valid.');
	foreach($_POST['setting_stage_starttime'] as $key => $row) {
		$csConfig['stages'][$key]['start_time'] = $row;
	}
	
	// end_day
	if(count($_POST['setting_stage_endday']) != count($csConfig['stages'])) throw new Exception('Schedule stages settings array size is not valid.');
	foreach($_POST['setting_stage_endday'] as $key => $row) {
		$csConfig['stages'][$key]['end_day'] = $row;
	}
	
	// end_time
	if(count($_POST['setting_stage_endtime']) != count($csConfig['stages'])) throw new Exception('Schedule stages settings array size is not valid.');
	foreach($_POST['setting_stage_endtime'] as $key => $row) {
		$csConfig['stages'][$key]['end_time'] = $row;
	}
	
	$fp = fopen($cfgFile, 'w');
	if(!fwrite($fp, json_encode($csConfig, JSON_PRETTY_PRINT))) throw new Exception('There has been an error while saving changes.');
	fclose($fp);
}

if(check_value($_POST['submit_changes'])) {
	try {
		saveChanges();
		message('success', 'Settings successfully saved.');
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// load configs
$cfg = file_get_contents(__PATH_CONFIGS__.'castlesiege.json');
if(!$cfg) throw new Exception('Error loading config file.');
$cfg = json_decode($cfg, true);
if(!is_array($cfg)) throw new Exception('Error loading config file.');
?>
<form action="" method="post">
	
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Active<br/><span>Enables or disabled the castle siege module.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_1', $cfg['active'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Hide Idle<br/><span>If enabled, the idle stages of castle siege will not be displayed.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_2', $cfg['hide_idle'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Live Data<br/><span>If enabled, castle siege data will be loaded directly from the database and will bypass the cache system.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_3', $cfg['live_data'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Castle Owner<br/><span>Displays the castle owner.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_4', $cfg['show_castle_owner'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Castle Owner Alliance<br/><span>Displays the castle owner alliance guilds.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_5', $cfg['show_castle_owner_alliance'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Battle Countdown<br/><span>Displays the castle siege battle countdown.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_6', $cfg['show_battle_countdown'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Castle Information<br/><span>Displays the castle information.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_7', $cfg['show_castle_information'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Current Stage<br/><span>Displays the current castle siege stage.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_8', $cfg['show_current_stage'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Next Stage<br/><span>Displays the next castle siege stage.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_9', $cfg['show_next_stage'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Battle Duration<br/><span>Displays the castle siege battle duration. Duration of battle is calculated according to your castle siege schedule configurations.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_10', $cfg['show_battle_duration'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Registered Guilds<br/><span>Displays the registered guilds and alliances.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_11', $cfg['show_registered_guilds'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Schedule<br/><span>Displays the full castle siege schedule.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_12', $cfg['show_schedule'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Schedule PHP Date Format<br/><span>Documentation:<br /><a href="https://www.php.net/manual/en/datetime.format.php#refsect1-datetime.format-parameters" target="_blank">https://www.php.net/manual/en/datetime.format.php#refsect1-datetime.format-parameters</a></span></th>
			<td>
				<input class="form-control" type="text" name="setting_13" value="<?php echo $cfg['schedule_date_format']; ?>"/>
			</td>
		</tr>
		<tr>
			<th>Show Widget<br/><span>Displays the castle siege information in your template's sidebar/header.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_14', $cfg['show_widget'], 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
	</table>
	
	<h3>Schedule</h3>
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Stage</th>
				<th>Start Day</th>
				<th>Start Time</th>
				<th>End Day</th>
				<th>End Time</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach($cfg['stages'] as $stageIndex => $stageData) {
			echo '<tr>';
				echo '<td>'.lang($stageData['title']).'</td>';
				echo '<td><select name="setting_stage_startday[]" class="form-control">'.weekDaySelectOptions($stageData['start_day']).'</select></td>';
				echo '<td><input class="form-control" type="text" name="setting_stage_starttime[]" value="'.$stageData['start_time'].'"/></td>';
				echo '<td><select name="setting_stage_endday[]" class="form-control">'.weekDaySelectOptions($stageData['end_day']).'</select></td>';
				echo '<td><input class="form-control" type="text" name="setting_stage_endtime[]" value="'.$stageData['end_time'].'"/></td>';
			echo '</tr>';
		}
		?>
		</tbody>
	</table>
	
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>