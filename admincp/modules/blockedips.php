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
<h1 class="page-header">Block IP Address <small>(web)</small></h1>
<form class="form-inline" role="form" method="post">
	<div class="form-group">
		<input type="text" class="form-control" id="input_1" name="ip_address" placeholder="0.0.0.0"/>
	</div>
	<button type="submit" class="btn btn-primary" name="submit_block" value="ok">Block</button>
</form>
<br />
<?php
if(check_value($_POST['submit_block'], $_POST['ip_address'])) {
	if($common->blockIpAddress($_POST['ip_address'],$_SESSION['username'])) {
		message('success','IP address blocked.');
	} else {
		message('error','Error blocking IP.');
	}
}

if(check_value($_GET['unblock'])) {
	if($common->unblockIpAddress($_REQUEST['unblock'])) {
		message('success','IP address unblocked.');
	} else {
		message('error','Error unblocking IP.');
	}
}

$blockedIPs = $common->retrieveBlockedIPs();
if(is_array($blockedIPs)) {
	echo '<div class="row">';
	echo '<div class="col-md-6">';
	echo '<table id="blocked_ips" class="table table-striped table-condensed table-hover">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>IP Address</th>';
				echo '<th>Blocked By</th>';
				echo '<th>Date Blocked</th>';
				echo '<th></th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($blockedIPs as $thisIP) {
			echo '<tr>';
				echo '<td>'.$thisIP['block_ip'].'</td>';
				echo '<td><a href="'.admincp_base("accountinfo&id=".$common->retrieveUserID($thisIP['block_by'])).'">'.$thisIP['block_by'].'</a></td>';
				echo '<td>'.date("m/d/Y H:i", $thisIP['block_date']).'</td>';
				echo '<td style="text-align:right;"><a href="'.admincp_base($_REQUEST['module']."&unblock=".$thisIP['id']).'" class="btn btn-xs btn-danger">Lift Block</a></td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
}

?>