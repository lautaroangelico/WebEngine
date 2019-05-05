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
<h1 class="page-header">Cron Job Manager</h1>
<?php
$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);

if(check_value($_REQUEST['reset']) && $_REQUEST['reset'] == 1) {
	$resetCrons = $database->query("UPDATE ".WEBENGINE_CRON." SET cron_last_run = NULL");
	if($resetCrons) {
		message('success','Crons successfully reset!');
	} else {
		message('error', 'Could not reset crons.');
	}
}

if(check_value($_REQUEST['delete'])) {
	deleteCronJob($_REQUEST['delete']);
}

if(check_value($_REQUEST['togglestatus'])) {
	togglestatusCronJob($_REQUEST['togglestatus']);
}

$cronJobs = $database->query_fetch("SELECT * FROM ".WEBENGINE_CRON." ORDER BY cron_id ASC");
if(is_array($cronJobs)) {
	echo '<table class="table table-striped table-bordered">';
		echo '<tr>';
			echo '<th></th>';
			echo '<th>Cron</th>';
			echo '<th>File</th>';
			echo '<th>Run Time</th>';
			echo '<th>Last Run</th>';
			echo '<th>Status</th>';
		echo '</tr>';
	foreach($cronJobs as $thisCron) {
	
		if(is_null($thisCron['cron_last_run'])) {
			$thisCron['cron_last_run'] = '<i>never</i>';
		} else {
			$thisCron['cron_last_run'] = date("Y/m/d H:i", $thisCron['cron_last_run']);
		}
		
		if($thisCron['cron_status'] == 1) {
			$status = '<a href="index.php?module=managecron&togglestatus='.$thisCron['cron_id'].'" class="btn btn-success btn-circle btn-xs"><i class="fa fa-check"></i></a>';
		} else {
			$status = '<a href="index.php?module=managecron&togglestatus='.$thisCron['cron_id'].'" class="btn btn-default btn-circle btn-xs"><i class="fa fa-check"></i></a>';
		}
		
		$cron_t = sec_to_hms($thisCron['cron_run_time']);
		
		echo '<tr>';
			echo '<td style="text-align:center;"><a href="index.php?module=managecron&delete='.$thisCron['cron_id'].'" class="btn btn-danger" ><i class="fa fa-remove"></i></a></td>';
			echo '<td><strong>'.$thisCron['cron_name'].'</strong><br /><small>'.$thisCron['cron_description'].'</small></td>';
			echo '<td>'.$thisCron['cron_file_run'].'</td>';
			echo '<td>'.$cron_t[0].'h '.$cron_t[1].'m</td>';
			echo '<td>'.$thisCron['cron_last_run'].'</td>';
			echo '<td style="text-align:center;">'.$status.'</td>';
		echo '</tr>';
		
	}
	echo '</table>';
} else {
	message('error','No cron jobs added.');
}

echo '<hr>';
echo '<a class="btn btn-info" href="index.php?module='.$_REQUEST['module'].'&reset=1">RESET LAST RUN</a>';