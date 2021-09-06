<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.3
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2021 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

echo '<h2>Registration Settings</h2>';

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'register.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->register_enable_recaptcha = $_POST['setting_2'];
	$xml->register_recaptcha_site_key = $_POST['setting_3'];
	$xml->register_recaptcha_secret_key = $_POST['setting_4'];
	$xml->send_welcome_email = $_POST['setting_6'];
	$xml->verify_email = $_POST['setting_5'];
	$xml->verification_timelimit = $_POST['setting_7'];
	$xml->automatic_login = $_POST['setting_8'];
	
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

loadModuleConfigs('register');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the registration module.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Recaptcha<br/><span>Enable/disable Recaptcha validation. <br/><br/> <a href="http://www.google.com/recaptcha" target="_blank">http://www.google.com/recaptcha</a></span></th>
			<td>
				<?php enabledisableCheckboxes('setting_2',mconfig('register_enable_recaptcha'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Recaptcha Site Key<br/></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_3" value="<?php echo mconfig('register_recaptcha_site_key'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Recaptcha Secret Key<br/></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_4" value="<?php echo mconfig('register_recaptcha_secret_key'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Email Verification<br/><span>If enabled, the user will receive an email with a verification link. The accout will not be created if the email is not verified.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_5',mconfig('verify_email'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Send Welcome Email<br/><span>Sends a welcome email after registering a new account.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_6',mconfig('send_welcome_email'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Verification Time Limit<br/><span>If <strong>Email Verification</strong> is Enabled. Set the amount of time the user has to verify the account. After the verification time limit passed, the user will have to repeat the registration process.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_7" value="<?php echo mconfig('verification_timelimit'); ?>"/> Hour(s)
			</td>
		</tr>
		<tr>
			<th>Automatic Log-In<br/><span>Automatic account log-in after registering. This feature only works when email verification is disabled.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_8', mconfig('automatic_login'), 'Enabled', 'Disabled'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>