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
<h2>Downloads Settings</h2>
<?php
$downloadTypes = array (
	1 => 'Client',
	2 => 'Patch',
	3 => 'Tool'
);

function downloadTypesSelect($downloadTypes,$selected=null) {
	foreach($downloadTypes as $key => $typeOPTION) {
		if(check_value($selected)) {
			if($key == $selected) {
				echo '<option value="'.$key.'" selected="selected">'.$typeOPTION.'</option>';
			} else {
				echo '<option value="'.$key.'">'.$typeOPTION.'</option>';
			}
		} else {
			echo '<option value="'.$key.'">'.$typeOPTION.'</option>';
		}
	}
}

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'downloads.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->show_client_downloads = $_POST['setting_2'];
	$xml->show_patch_downloads = $_POST['setting_3'];
	$xml->show_tool_downloads = $_POST['setting_4'];
	
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

if(check_value($_POST['downloads_add_submit'])) {
	$action = addDownload($_POST['downloads_add_title'], $_POST['downloads_add_desc'], $_POST['downloads_add_link'], $_POST['downloads_add_size'], $_POST['downloads_add_type']);
	if($action) {
		message('success','Your download link has been successfully added!');
	} else {
		message('error','There was an error adding the download link.');
	}
}

if(check_value($_POST['downloads_edit_submit'])) {
	$action = editDownload($_POST['downloads_edit_id'], $_POST['downloads_edit_title'], $_POST['downloads_edit_desc'], $_POST['downloads_edit_link'], $_POST['downloads_edit_size'], $_POST['downloads_edit_type']);
	if($action) {
		message('success','Your download link has been successfully updated!');
	} else {
		message('error','There was an error updating the download link.');
	}
}

if(check_value($_REQUEST['deletelink'])) {
	$action = deleteDownload($_REQUEST['deletelink']);
	if($action) {
		message('success','Your download link has been successfully deleted!');
	} else {
		message('error','There was an error deleting the download link.');
	}
}

loadModuleConfigs('downloads');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the downloads module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Client Downloads<br/></th>
			<td>
				<? enabledisableCheckboxes('setting_2',mconfig('show_client_downloads'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Patches Downloads<br/></th>
			<td>
				<? enabledisableCheckboxes('setting_3',mconfig('show_patch_downloads'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<th>Show Tools Downloads<br/></th>
			<td>
				<? enabledisableCheckboxes('setting_4',mconfig('show_tool_downloads'),'Yes','No'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>

<hr>
<h3>Manage Downloads</h3>
<?php
$downloads = getDownloadsList();
if(is_array($downloads)) {
echo '
<table class="table table-striped table-bordered table-hover">
	<tr>
		<th>Title</th>
		<th>Description</th>
		<th>Link</th>
		<th>Size (MB)</th>
		<th>Type</th>
		<th></th>
	</tr>';
	
	foreach($downloads as $thisDownload) {
	echo '
	<form action="index.php?module=modules_manager&config=downloads" method="post">
	<input type="hidden" name="downloads_edit_id" value="'.$thisDownload['download_id'].'"/>
	<tr>
		<td><input type="text" name="downloads_edit_title" class="form-control" value="'.$thisDownload['download_title'].'"/></td>
		<td><input type="text" name="downloads_edit_desc" class="form-control" value="'.$thisDownload['download_description'].'"/></td>
		<td><input type="text" name="downloads_edit_link" class="form-control" value="'.$thisDownload['download_link'].'"/></td>
		<td><input type="text" name="downloads_edit_size" class="form-control" value="'.round($thisDownload['download_size'],2).'"/></td>
		<td>
			<select name="downloads_edit_type" class="form-control">';
				downloadTypesSelect($downloadTypes, $thisDownload['download_type']);
		echo '
			</select>
		</td>
		<td>
		<input type="submit" class="btn btn-success" name="downloads_edit_submit" value="Save"/>
		<a href="index.php?module=modules_manager&config=downloads&deletelink='.$thisDownload['download_id'].'" class="btn btn-danger">Remove</a>
		</td>
	</tr>
	</form>';
	}
	
echo '</table>';
} else {
	message('error','You have not added any download link.');
}
?>

<hr>
<h3>Add Download</h3>
<form action="index.php?module=modules_manager&config=downloads" method="post">
<table class="table table-striped table-bordered table-hover">
	<tr>
		<th>Title</th>
		<th>Description</th>
		<th>Link</th>
		<th>Size (MB)</th>
		<th>Type</th>
	</tr>
	<tr>
		<td><input type="text" name="downloads_add_title" class="form-control"/></td>
		<td><input type="text" name="downloads_add_desc" class="form-control"/></td>
		<td><input type="text" name="downloads_add_link" class="form-control"/></td>
		<td><input type="text" name="downloads_add_size" class="form-control"/></td>
		<td>
			<select name="downloads_add_type" class="form-control">
				<? downloadTypesSelect($downloadTypes); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="5"><input type="submit" name="downloads_add_submit" class="btn btn-success" value="Add Download"/></td>
	</tr>
</table>
</form>