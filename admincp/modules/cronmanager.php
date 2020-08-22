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

echo '<h1 class="page-header">Cron Job Manager</h1>';

try {
	
	$cronManager = new CronManager();
	$cronList = $cronManager->getCronList();
	
	// Actions
	if(check_value($_GET['action'])) {
		try {
			switch($_GET['action']) {
				case 'enable':
					if(!check_value($_GET['id'])) throw new Exception(lang('error_105'));
					$cronManager->setId($_GET['id']);
					$cronManager->enableCron();
					break;
				case 'disable':
					if(!check_value($_GET['id'])) throw new Exception(lang('error_105'));
					$cronManager->setId($_GET['id']);
					$cronManager->disableCron();
					break;
				case 'delete':
					if(!check_value($_GET['id'])) throw new Exception(lang('error_105'));
					$cronManager->setId($_GET['id']);
					$cronManager->deleteCron();
					break;
				case 'reset':
					if(!check_value($_GET['id'])) throw new Exception(lang('error_105'));
					$cronManager->setId($_GET['id']);
					$cronManager->resetCronLastRun();
					break;
				case 'allenable':
					$cronManager->enableAll();
					break;
				case 'alldisable':
					$cronManager->disableAll();
					break;
				case 'allreset':
					$cronManager->resetAllLastRun();
					break;
				default:
					throw new Exception(lang('error_105'));
			}
			redirect(3, admincp_base('cronmanager'));
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	// Add Cron
	if(check_value($_POST['submit'])) {
		try {
			$cronManager->setName($_POST['cron_name']);
			$cronManager->setFile($_POST['cron_file']);
			$cronManager->setInterval($_POST['cron_time']);
			$cronManager->addCron();
			redirect(3, admincp_base('cronmanager'));
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="row">';
		echo '<div class="col-xs-12 col-md-12 col-lg-3">';
			
			// New Cron Form
			$cron_times = $cronManager->getCommonIntervals();
			echo '<div class="panel panel-primary">';
				echo '<div class="panel-heading">Add New Cron</div>';
				echo '<div class="panel-body">';
					echo '<form action="'.admincp_base('cronmanager').'" method="post">';
						echo '<div class="form-group">';
							echo '<label for="input_1">Name:</label>';
							echo '<input type="text" class="form-control" id="input_1" name="cron_name" autofocus required/>';
						echo '</div>';

						echo '<div class="form-group">';
							echo '<label for="input_2">File:</label>';
							echo '<select class="form-control" id="input_2" name="cron_file">';
								echo $cronManager->listCronFiles();
							echo '</select>';
						echo '</div>';
						
						echo '<div class="form-group">';
							echo '<label for="input_3">Repeat:</label>';
							echo '<select class="form-control" id="input_3" name="cron_time">';
								if(is_array($cron_times)) {
									foreach($cron_times as $seconds => $description) {
										echo '<option value="'.$seconds.'">'.$description.'</option>';
									}
								} else {
									echo '<option value="300">5 Minutes</option>';
								}
							echo '</select>';
						echo '</div>';
						
						echo '<button type="submit" name="submit" value="Add" class="btn btn-primary">Add Cron</button>';
					echo '</form>';
				echo '</div>';
			echo '</div>';
			
			echo '<hr>';
			
			// Actions
			echo '<h4>Actions:</h4>';
			echo '<p>';
				echo '<a href="'.admincp_base('cronmanager&action=allenable').'" class="btn btn-xs btn-default">Enable All</a>&nbsp;';
				echo '<a href="'.admincp_base('cronmanager&action=alldisable').'" class="btn btn-xs btn-default">Disable All</a>&nbsp;';
				echo '<a href="'.admincp_base('cronmanager&action=allreset').'" class="btn btn-xs btn-default">Reset All</a>';
			echo '</p>';
			
			echo '<hr>';
			
			// Cron Info
			echo '<h4>Setting up the master cron:</h4>';
			echo '<p>WebEngine CMS\' cron job system is designed to automatically run heavy tasks in the background. This helps to make sure the website always loads as fast as possible to all visitors.</p>';
			echo '<p>Please refer to the following link if your cron jobs are not being executed automatically:</p>';
			echo '<ul>';
				echo '<li><a href="https://github.com/lautaroangelico/WebEngine/wiki/Setting-up-the-master-cron-job" target="_blank">WebEngine CMS Github Wiki</a></li>';
			echo '</ul>';
			
			echo '<hr>';
			
			// Cron API Info
			echo '<h4>Cron Jobs API:</h4>';
			echo '<p>If unable to set-up the master cron on your web server, you may alternatively use the cron job api along with a third-party service such as <a href="https://cron-job.org/" target="_blank">cron-job.org</a> to execute your master cron.</p>';
			echo '<p>Cron API URL:</p>';
			echo '<p><input type="text" class="form-control" value="'.$cronManager->getCronApiUrl().'" disabled/></p>';
			
		echo '</div>';
		
		// Cron List
		echo '<div class="col-xs-12 col-md-12 col-lg-9">';
		if(is_array($cronList)) {
			echo '<table class="table table-hover">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Id</th>';
						echo '<th>Name</th>';
						echo '<th>File</th>';
						echo '<th>Repeat</th>';
						echo '<th>Last Run</th>';
						echo '<th>Actions</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				foreach($cronList as $row) {
					$interval = sec_to_hms($row['cron_run_time']);
					$lastRun = check_value($row['cron_last_run']) ? date('Y/m/d H:i A', $row['cron_last_run']) : '<i>Never</i>';
					$status = $row['cron_status'] == 1 ? '<a href="'.admincp_base('cronmanager&action=disable&id='.$row['cron_id']).'" class="btn btn-xs btn-success"><i class="fa fa-check"></i></a>' : '<a href="'.admincp_base('cronmanager&action=enable&id='.$row['cron_id']).'" class="btn btn-xs btn-default"><i class="fa fa-check"></i></a>';
					echo '<tr>';
						echo '<td>'.$row['cron_id'].'</td>';
						echo '<td>'.$row['cron_name'].'</td>';
						echo '<td>'.$row['cron_file_run'].'</td>';
						echo '<td>'.$interval[0].'h '.$interval[1].'m</td>';
						echo '<td>'.$lastRun.'</td>';
						echo '<td>';
							echo '<a href="'.admincp_base('cronmanager&action=reset&id='.$row['cron_id']).'" class="btn btn-xs btn-default" title="Reset"><i class="fa fa-repeat"></i></a>&nbsp;';
							echo '<a href="'.$cronManager->getCronApiUrl($row['cron_id']).'" target="_blank" class="btn btn-xs btn-default">Run</a>&nbsp;';
							echo $status.'&nbsp;';
							echo '<a href="'.admincp_base('cronmanager&action=delete&id='.$row['cron_id']).'" class="btn btn-xs btn-danger">Delete</a>';
						echo '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
		} else {
			message('warning', lang('error_104'));
		}
		
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}