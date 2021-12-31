<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.4
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2022 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */
?>
<h2>Rankings Settings</h2>
<?php
function saveChanges() {
	global $_POST;
	
	$xmlPath = __PATH_MODULE_CONFIGS__.'rankings.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->rankings_results = $_POST['setting_2'];
	$xml->rankings_show_date = $_POST['setting_3'];
	$xml->rankings_show_default = $_POST['setting_4'];
	$xml->rankings_show_place_number = $_POST['setting_5'];
	$xml->rankings_enable_level = $_POST['setting_6'];
	$xml->rankings_enable_resets = $_POST['setting_7'];
	$xml->rankings_enable_pk = $_POST['setting_8'];
	$xml->rankings_enable_gr = $_POST['setting_9'];
	$xml->rankings_enable_online = $_POST['setting_10'];
	$xml->rankings_enable_guilds = $_POST['setting_11'];
	$xml->rankings_enable_master = $_POST['setting_12'];
	$xml->rankings_enable_gens = $_POST['setting_14'];
	$xml->rankings_enable_votes = $_POST['setting_15'];
	$xml->rankings_excluded_characters = $_POST['setting_16'];
	$xml->combine_level_masterlevel = $_POST['setting_17'];
	$xml->show_country_flags = $_POST['setting_18'];
	$xml->show_location = $_POST['setting_19'];
	$xml->show_online_status = $_POST['setting_20'];
	$xml->guild_score_formula = $_POST['setting_21'];
	$xml->guild_score_multiplier = $_POST['setting_22'];
	$xml->rankings_excluded_guilds = $_POST['setting_23'];
	$xml->rankings_class_filter = $_POST['setting_24'];
	
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

$xmlPath = __PATH_MODULE_CONFIGS__.'rankings.xml';
$moduleConfig = simplexml_load_file($xmlPath);
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the ranking system.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_1',$moduleConfig->active,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Rankings Results<br/><span>Amount of ranking results WebEngine should cache.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_2" value="<?php echo $moduleConfig->rankings_results; ?>"/>
			</td>
		</tr>
		<tr>
			<th>Display Last Update Date<br/><span>Show at the bottom of the rankings the date each ranking was last updated.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_3',$moduleConfig->rankings_show_date,'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Default Rankings<br/><span>Which rankings will be shown by default when accessing to the rankings page.<br/><br/>Options:<ul><li>level</li><li>resets</li><li>killers</li><li>grandresets</li><li>online</li><li>guilds</li><li>master</li><li>gens</li></ul></span></th>
			<td>
				<input class="form-control" type="text" name="setting_4" value="<?php echo $moduleConfig->rankings_show_default; ?>"/>
			</td>
		</tr>
		<tr>
			<th>Display Position Number<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_5',$moduleConfig->rankings_show_place_number,'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Level Rankings<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_6',$moduleConfig->rankings_enable_level,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Reset Rankings<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_7',$moduleConfig->rankings_enable_resets,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Killer Rankings<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_8',$moduleConfig->rankings_enable_pk,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Grand Reset Rankings<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_9',$moduleConfig->rankings_enable_gr,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Online Rankings<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_10',$moduleConfig->rankings_enable_online,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Master Level Rankings<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_12',$moduleConfig->rankings_enable_master,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Gens Rankings<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_14',$moduleConfig->rankings_enable_gens,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Vote Rankings<br/></th>
			<td>
				<?php enabledisableCheckboxes('setting_15',$moduleConfig->rankings_enable_votes,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Exclude Characters<br/><span>Add the names of characters (separated by commas) that you want to exclude from showing up in the rankings.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_16" value="<?php echo $moduleConfig->rankings_excluded_characters; ?>"/>
			</td>
		</tr>
		<tr>
			<th>Combine Level + Master Level<br /><span>If enabled, the player's level and master level will be combined as one in the rankings.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_17',$moduleConfig->combine_level_masterlevel,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Country Flags<br /><span>If enabled, the character's country flag will be displayed in the rankings.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_18',$moduleConfig->show_country_flags,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Character Last Location<br /><span>If enabled, the character's last known location will be displayed in the rankings.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_19',$moduleConfig->show_location,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Character Online Status<br /><span>If enabled, the character's online status will be displayed in the rankings.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_20',$moduleConfig->show_online_status,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Character Class Filter<br /><span>If enabled, the character's class filter menu will be displayed in the rankings.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_24',$moduleConfig->rankings_class_filter,'Enabled','Disabled'); ?>
			</td>
		</tr>
	</table>
	
	<h3>Guild Ranking Settings</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Guild Rankings<br /></th>
			<td>
				<?php enabledisableCheckboxes('setting_11',$moduleConfig->rankings_enable_guilds,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Guild Score Formula<br /><span><br /><strong>Original Guild Score:</strong><br />Uses the game's original guild score.<br /><br /><strong>WebEngine CMS Custom 1:</strong><br />Calculates the sum of all guild member's stats and multiplies by the multiplier value.<br /><br /><strong>WebEngine CMS Custom 2:</strong><br />Calculates the sum of all guild member's stats (except Command) and multiplies by the multiplier value.<br /><br /></span></th>
			<td>
				<select class="form-control" name="setting_21">
					<option value="1" <?php if($moduleConfig->guild_score_formula == 1) echo 'selected';?>>Original Guild Score</option>
					<option value="2" <?php if($moduleConfig->guild_score_formula == 2) echo 'selected';?>>WebEngine CMS Custom 1: (STR+AGI+VIT+ENE+CMD) * MULTIPLIER</option>
					<option value="3" <?php if($moduleConfig->guild_score_formula == 3) echo 'selected';?>>WebEngine CMS Custom 2: (STR+AGI+VIT+ENE) * MULTIPLIER</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>Multiplier<br /><span>If the score setting is set to one of WebEngine's custom score formulas, use the multiplier to customize how big or small you want the scores to be.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_22" value="<?php echo $moduleConfig->guild_score_multiplier; ?>"/>
			</td>
		</tr>
		<tr>
			<th>Exclude Guilds<br /><span>Add the names of the guilds (separated by commas) that you want to exclude from showing up in the guilds rankings.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_23" value="<?php echo $moduleConfig->rankings_excluded_guilds; ?>"/>
			</td>
		</tr>
	</table>
	
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>