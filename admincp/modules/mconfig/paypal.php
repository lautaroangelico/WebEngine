<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */
?>
<h1 class="page-header">PayPal Settings</h1>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	
	// PAYPAL
	$xmlPath = __PATH_MODULE_CONFIGS__.'donation.paypal.xml';
	$xml = simplexml_load_file($xmlPath);
	$xml->active = $_POST['setting_2'];
	$xml->paypal_enable_sandbox = $_POST['setting_3'];
	$xml->paypal_email = $_POST['setting_4'];
	$xml->paypal_title = $_POST['setting_5'];
	$xml->paypal_currency = $_POST['setting_6'];
	$xml->paypal_return_url = $_POST['setting_7'];
	$xml->paypal_notify_url = $_POST['setting_8'];
	$xml->paypal_conversion_rate = $_POST['setting_9'];
	$xml->credit_config = $_POST['setting_10'];
	$save2 = $xml->asXML($xmlPath);
	

	if($save2) {
		message('success','[PayPal] Settings successfully saved.');
	} else {
		message('error','[PayPal] There has been an error while saving changes.');
	}

}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('donation.paypal');

$creditSystem = new CreditSystem();
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the paypal donation gateway.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_2',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>PayPal Sandbox Mode<br/><span>Enable/disable PayPal's IPN testing mode.<br/><br/>More info:<br/><a href="https://developer.paypal.com/" target="_blank">https://developer.paypal.com/</a></span></th>
			<td>
				<? enabledisableCheckboxes('setting_3',mconfig('paypal_enable_sandbox'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>PayPal Email<br/><span>PayPal email where you will receive the donations.</span></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_4" value="<?=mconfig('paypal_email')?>"/>
			</td>
		</tr>
		<tr>
			<th>PayPal Donations Title<br/><span>Title of the PayPal donation. Example: "Donation for MU Credits".</span></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_5" value="<?=mconfig('paypal_title')?>"/>
			</td>
		</tr>
		<tr>
			<th>Currency Code<br/><span>List of available PayPal currencies: <a href="https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes" target="_blank">click here</a>.</span></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_6" value="<?=mconfig('paypal_currency')?>"/>
			</td>
		</tr>
		<tr>
			<th>Return/Cancel URL<br/><span>URL where the client will be redirected to if the donation is cancelled or completed.</span></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_7" value="<?=mconfig('paypal_return_url')?>"/>
			</td>
		</tr>
		<tr>
			<th>IPN Notify URL<br/><span>URL of WebEngine's PayPal API.<br/><br/> By default it has to be in: <b>http://YOURWEBSITE.COM/api/paypal.php</b></span></th>
			<td>
				<input class="input-xxlarge" type="text" name="setting_8" value="<?=mconfig('paypal_notify_url')?>"/>
			</td>
		</tr>
		<tr>
			<th>Credits Conversion Rate<br/><span>How many game credits is equivalent to 1 of real money currency.<br/><br/>Example:<br/>1 USD = 100 Credits, in this example you would type in the box 100.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_9" value="<?=mconfig('paypal_conversion_rate')?>"/>
			</td>
		</tr>
		<tr>
			<th>Credit Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_10", mconfig('credit_config'), "form-control"); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>