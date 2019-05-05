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
<h2>Top Voters</h2>
<?php
$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);

$currentMonth = date("m");
$nextMonth = $currentMonth+1;

$ts1 = strtotime(date("m/01/Y 00:00"));
$ts2 = strtotime(date("$nextMonth/01/Y 00:00"));
$voteLogs = $database->query_fetch("SELECT TOP 100 user_id, COUNT(*) as totalvotes FROM ".WEBENGINE_VOTE_LOGS." WHERE timestamp BETWEEN ? AND ? GROUP BY user_id ORDER BY totalvotes DESC", array($ts1,$ts2));

if($voteLogs && is_array($voteLogs)) {
	
	echo '<table class="table table-condensed table-hover">';
		echo '<tr>';
			echo '<th>#</th>';
			echo '<th>Account</th>';
			echo '<th>Votes</th>';
		echo '</tr>';
		
		foreach($voteLogs as $key => $thisVote) {
			$accountInfo = $common->accountInformation($thisVote['user_id']);
			$keyx = $key+1;
			echo '<tr>';
				echo '<td>'.$keyx.'</td>';
				echo '<td>'.$accountInfo[_CLMN_USERNM_].'</td>';
				echo '<td>'.$thisVote['totalvotes'].'</td>';
			echo '</tr>';
		}
	echo '</table>';
	
} else {
	message('error', 'No vote logs found. This feature needs vote logs enabled.');
}
?>