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
<h1 class="page-header">Online Accounts</h1>
<?php
$onlinedb = (config('SQL_USE_2_DB',true) == true ? $dB2 : $dB);
$online = $onlinedb->query_fetch("SELECT "._CLMN_MS_MEMBID_.","._CLMN_MS_GS_." FROM "._TBL_MS_." WHERE "._CLMN_CONNSTAT_." = 1");
if(is_array($online)) {
	message('',count($online),'Total Online:');
	
	echo '<table class="table table-condensed table-hover">
	<thead>
	<tr>
	<th>Account</th>
	<th>Server</th>
	<th></th>
	</tr>
	</thead>
	<tbody>';
	foreach($online as $thisAccount) {
		echo '<tr>';
		echo '<td>'.$thisAccount[_CLMN_MS_MEMBID_].'</td>';
		echo '<td>'.$thisAccount[_CLMN_MS_GS_].'</td>';
		echo '<td style="text-align:right;"><a href="'.admincp_base("accountinfo&id=".$common->retrieveUserID($thisAccount[_CLMN_MS_MEMBID_])).'" class="btn btn-xs btn-default">Account Information</a></td>';
		echo '</tr>';
	}
	echo '
	</tbody>
	</table>';
} else {
	message('error','No online accounts.');
}

?>