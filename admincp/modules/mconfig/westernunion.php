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
<h1 class="page-header">Western Union Settings</h1>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	// WESTERN UNION
	$xmlPath = __PATH_MODULE_CONFIGS__.'donation.westernunion.xml';
	$xml = simplexml_load_file($xmlPath);
	$xml->active = $_POST['setting_14'];
	$save4 = $xml->asXML($xmlPath);
	
	if($save4) {
		message('success','[Western Union] Settings successfully saved.');
	} else {
		message('error','[Western Union] There has been an error while saving changes.');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('donation.westernunion');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the western union donation gateway.<br/><? message('warning','You must edit your Western Union details manually in the module file located at <b>/modules/donation/westernunion.php</b>','NOTE:'); ?></span></th>
			<td>
				<? enabledisableCheckboxes('setting_14',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>	
</form>