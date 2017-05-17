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
<h1 class="page-header">Cron Job Manager</h1>
<?php

$cron_times = array(
	1 => 60*5,
	2 => 60*10,
	3 => 60*15,
	4 => 60*30,
	5 => 60*60,
	6 => 3600*2,
	7 => 3600*4,
	8 => 3600*8,
	9 => 3600*10,
	10 => 3600*12,
	11 => 86400,
	12 => 86400*3,
	13 => 86400*7,
	14 => 604800*2,
	15 => 604800*3,
	16 => 604800*4
);

if(check_value($_REQUEST['cache']) && $_REQUEST['cache'] == 1) {
	$cacheDATA = BuildCacheData($dB->query_fetch("SELECT * FROM WEBENGINE_CRON"));
	UpdateCache('cron.cache',$cacheDATA);
	message('success','Cron jobs cache successfully updated!');
}

if(check_value($_REQUEST['reset']) && $_REQUEST['reset'] == 1) {
	$resetCrons = $dB->query("UPDATE WEBENGINE_CRON SET cron_last_run = NULL");
	if($resetCrons) {
		$cacheDATA = BuildCacheData($dB->query_fetch("SELECT * FROM WEBENGINE_CRON"));
		UpdateCache('cron.cache',$cacheDATA);
		message('success','Crons successfully reset and cache updated!');
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

$cronJobs = $dB->query_fetch("SELECT * FROM WEBENGINE_CRON ORDER BY cron_id ASC");
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
echo '<a class="btn btn-info" href="index.php?module='.$_REQUEST['module'].'&cache=1">UPDATE CRON JOBS CACHE</a> &nbsp;';
echo '<a class="btn btn-info" href="index.php?module='.$_REQUEST['module'].'&reset=1">RESET ALL CRON JOBS</a>';