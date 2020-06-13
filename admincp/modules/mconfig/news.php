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
?>
<h2>News Settings</h2>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'news.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->news_expanded = $_POST['setting_2'];
	$xml->news_list_limit = $_POST['setting_3'];
	$xml->news_short = $_POST['setting_6'];
	$xml->news_short_char_limit = $_POST['setting_7'];
	
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

loadModuleConfigs('news');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the news module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Expanded News<br/><span>Amount of news you want to display expanded. If less than the display news limit configuration, then the rest of the news will not display expanded.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_2" value="<?=mconfig('news_expanded')?>"/>
			</td>
		</tr>
		<tr>
			<th>Shown News Limit<br/><span>Amount of news to display in the news page.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_3" value="<?=mconfig('news_list_limit')?>"/>
			</td>
		</tr>
		<tr>
			<th>Short News<br/><span>Enable/disable the short news feature.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_6',mconfig('news_short'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Short News Character Limit<br/><span>Amount of characters to show in the short version of news.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_7" value="<?=mconfig('news_short_char_limit')?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>