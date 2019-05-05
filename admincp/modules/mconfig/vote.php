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
<h2>Vote and Reward Settings</h2>
<?php

// Load Vote Class
$vote = new Vote();

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'usercp.vote.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->vote_save_logs = $_POST['setting_2'];
	$xml->credit_config = $_POST['setting_3'];
	
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

if(check_value($_POST['votesite_add_submit'])) {
	$add = $vote->addVotesite($_POST['votesite_add_title'],$_POST['votesite_add_link'],$_POST['votesite_add_reward'],$_POST['votesite_add_time']);
	if($add) {
		message('success','Votesite successfully added.');
	} else {
		message('error','There has been an error while adding the topsite.');
	}
}

if(check_value($_REQUEST['deletesite'])) {
	$delete = $vote->deleteVotesite($_REQUEST['deletesite']);
	if($delete) {
		message('success','Votesite successfully deleted.');
	} else {
		message('error','There has been an error while deleting the topsite.');
	}
}

loadModuleConfigs('usercp.vote');

$creditSystem = new CreditSystem();
?>
<form action="index.php?module=modules_manager&config=vote" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the vote module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Save Vote Logs<br/><span>If enabled, every vote will be permanently logged in a database table.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_2',mconfig('vote_save_logs'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Credit Configuration<br/><span></span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_3", mconfig('credit_config'), "form-control"); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>

<hr>
<h3>Manage Vote Sites</h3>
<?php
$votesiteList = $vote->retrieveVotesites();
if(is_array($votesiteList)) {
	echo '<table class="table table-striped table-bordered table-hover">';
	echo '<tr>';
	echo '<th>Title</th>';
	echo '<th>Link (full url including http)</th>';
	echo '<th>Reward</th>';
	echo '<th>Vote Every</th>';
	echo '<th></th>';
	echo '</tr>';
	
	foreach($votesiteList as $thisVoteSite) {
		echo '<tr>';
		echo '<td>'.$thisVoteSite['votesite_title'].'</td>';
		echo '<td>'.$thisVoteSite['votesite_link'].'</td>';
		echo '<td>'.$thisVoteSite['votesite_reward'].' credit(s)</td>';
		echo '<td>'.$thisVoteSite['votesite_time'].' hour(s)</td>';
		echo '<td><a href="index.php?module=modules_manager&config=vote&deletesite='.$thisVoteSite['votesite_id'].'" class="btn btn-block"><i class="fa fa-remove"></i></a></td>';
		echo '</tr>';
	}
	
	echo '<form action="index.php?module=modules_manager&config=vote" method="post">';
	echo '<tr>';
	echo '<td><input name="votesite_add_title" class="form-control" type="text"/></td>';
	echo '<td><input name="votesite_add_link" class="form-control" type="text"/></td>';
	echo '<td><input name="votesite_add_reward" class="form-control" type="text"/> credit(s)</td>';
	echo '<td><input name="votesite_add_time" class="form-control" type="text"/> hour(s)</td>';
	echo '<td><input type="submit" name="votesite_add_submit" class="btn btn-success" value="Add!"/></td>';
	echo '</tr>';
	echo '</form>';
	
	echo '</table>';
} else {
	echo '<h4>Add Voting Site</h4>';
	echo '<table class="table table-striped table-bordered table-hover">';
	echo '<tr>';
	echo '<th>Title</th>';
	echo '<th>Link (full url including http)</th>';
	echo '<th>Reward</th>';
	echo '<th>Vote Every</th>';
	echo '<th></th>';
	echo '</tr>';
	echo '<form action="index.php?module=modules_manager&config=vote" method="post">';
	echo '<tr>';
	echo '<td><input name="votesite_add_title" class="form-control" type="text"/></td>';
	echo '<td><input name="votesite_add_link" class="form-control" type="text"/></td>';
	echo '<td><input name="votesite_add_reward" class="form-control" type="text"/> credit(s)</td>';
	echo '<td><input name="votesite_add_time" class="form-control" type="text"/> hour(s)</td>';
	echo '<td><input type="submit" name="votesite_add_submit" class="btn btn-success" value="Add!"/></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
}

?>