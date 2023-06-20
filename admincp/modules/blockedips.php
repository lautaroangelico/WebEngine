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
<h1 class="page-header">Banear IP <small>(web)</small></h1>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<form class="form-inline" role="form" method="post">
					<div class="form-group">
						<input type="text" class="form-control" id="input_1" name="ip_address" placeholder="0.0.0.0"/>
					</div>
					<button type="submit" class="btn btn-primary" name="submit_block" value="ok">Banear</button>
				</form>
			</div>
		</div>
	</div>
</div>
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
		echo '<div class="col-md-12">';
			echo '<div class="card">';
				echo '<div class="card-body">';
					echo '<table id="zero_config" class="table table-striped table-bordered">';
						echo '<thead>';
							echo '<tr>';
								echo '<th>IP</th>';
								echo '<th>Baneado por</th>';
								echo '<th>Fecha del Ban</th>';
								echo '<th></th>';
							echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						foreach($blockedIPs as $thisIP) {
							echo '<tr>';
								echo '<td>'.$thisIP['block_ip'].'</td>';
								echo '<td><a href="'.admincp_base("accountinfo&id=".$common->retrieveUserID($thisIP['block_by'])).'">'.$thisIP['block_by'].'</a></td>';
								echo '<td>'.date("m/d/Y H:i", $thisIP['block_date']).'</td>';
								echo '<td style="text-align:right;"><a href="'.admincp_base($_REQUEST['module']."&unblock=".$thisIP['id']).'" class="btn btn-xs btn-danger">Quitar Ban</a></td>';
							echo '</tr>';
						}
						echo '</tbody>';
					echo '</table>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';		
}

?>