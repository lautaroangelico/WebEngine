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
<h1 class="page-header">Email Settings</h1>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_CONFIGS__.'email.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->send_from = $_POST['setting_2'];
	$xml->send_name = $_POST['setting_3'];
	$xml->smtp_active = $_POST['setting_4'];
	$xml->smtp_host = $_POST['setting_5'];
	$xml->smtp_port = $_POST['setting_6'];
	$xml->smtp_user = $_POST['setting_7'];
	$xml->smtp_pass = $_POST['setting_8'];
	
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

// Load SMTP Configs
$emailConfigs = gconfig('email',true);
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Email System<br/><span>Enable/disable the email system.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',$emailConfigs['active'],'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Send Email From<br/><span></span></th>
			<td>
				<input type="text" name="setting_2" value="<?php echo $emailConfigs['send_from']; ?>"/>
			</td>
		</tr>
		<tr>
			<th>Send Email From Name<br/><span></span></th>
			<td>
				<input type="text" name="setting_3" value="<?php echo $emailConfigs['send_name']; ?>"/>
			</td>
		</tr>
		<tr>
			<th>SMTP Status<br/><span>Enable/disable the SMTP system.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_4',$emailConfigs['smtp_active'],'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>SMTP Host<br/><span></span></th>
			<td>
				<input type="text" name="setting_5" value="<?php echo $emailConfigs['smtp_host']; ?>"/>
			</td>
		</tr>
		<tr>
			<th>SMTP Port<br/><span></span></th>
			<td>
				<input type="text" class="input-mini" name="setting_6" value="<?php echo $emailConfigs['smtp_port']; ?>"/>
			</td>
		</tr>
		<tr>
			<th>SMTP User<br/><span></span></th>
			<td>
				<input type="text" name="setting_7" value="<?php echo $emailConfigs['smtp_user']; ?>"/>
			</td>
		</tr>
		<tr>
			<th>SMTP Password<br/><span></span></th>
			<td>
				<input type="text" name="setting_8" value="<?php echo $emailConfigs['smtp_pass']; ?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>