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

echo '<h1 class="page-header">Credit Configurations</h1>';

$creditSystem = new CreditSystem();

// NEW CONFIG
if(check_value($_POST['new_submit'])) {
	try {
		if(!check_value($_POST['new_title'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_database'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_table'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_credits_column'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_user_column'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_user_column_id'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_checkonline'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['new_display'])) throw new Exception("Please fill all the required fields.");
		
		$creditSystem->setConfigTitle($_POST['new_title']);
		$creditSystem->setConfigDatabase($_POST['new_database']);
		$creditSystem->setConfigTable($_POST['new_table']);
		$creditSystem->setConfigCreditsColumn($_POST['new_credits_column']);
		$creditSystem->setConfigUserColumn($_POST['new_user_column']);
		$creditSystem->setConfigUserColumnId($_POST['new_user_column_id']);
		$creditSystem->setConfigCheckOnline($_POST['new_checkonline']);
		$creditSystem->setConfigDisplay($_POST['new_display']);
		$creditSystem->saveConfig();
		
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// EDIT CONFIG
if(check_value($_POST['edit_submit'])) {
	try {
		if(!check_value($_POST['edit_id'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_title'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_database'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_table'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_credits_column'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_user_column'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_user_column_id'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_checkonline'])) throw new Exception("Please fill all the required fields.");
		if(!check_value($_POST['edit_display'])) throw new Exception("Please fill all the required fields.");
		
		$creditSystem->setConfigId($_POST['edit_id']);
		$creditSystem->setConfigTitle($_POST['edit_title']);
		$creditSystem->setConfigDatabase($_POST['edit_database']);
		$creditSystem->setConfigTable($_POST['edit_table']);
		$creditSystem->setConfigCreditsColumn($_POST['edit_credits_column']);
		$creditSystem->setConfigUserColumn($_POST['edit_user_column']);
		$creditSystem->setConfigUserColumnId($_POST['edit_user_column_id']);
		$creditSystem->setConfigCheckOnline($_POST['edit_checkonline']);
		$creditSystem->setConfigDisplay($_POST['edit_display']);
		
		$creditSystem->editConfig();
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// DELETE CONFIG
if(check_value($_GET['delete'])) {
	try {
		$creditSystem->setConfigId($_GET['delete']);
		$creditSystem->deleteConfig();
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-md-4">';
		
		if(!check_value($_GET['edit'])) {
			// ADD NEW CONFIG
			echo '<div class="panel panel-primary">';
			echo '<div class="panel-heading">New Configuration</div>';
			echo '<div class="panel-body">';

				echo '<form role="form" action="'.admincp_base("creditsconfigs").'" method="post">';
					echo '<div class="form-group">';
						echo '<label for="input_1">Title:</label>';
						echo '<input type="text" class="form-control" id="input_1" name="new_title"/>';
					echo '</div>';

					echo '<label>Database:</label>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="new_database" id="databaseRadios1" value="MuOnline" checked> ' . config('SQL_DB_NAME', true);
						echo '</label>';
					echo '</div>';
					
					if(config('SQL_USE_2_DB',true)) {
						echo '<div class="radio">';
							echo '<label>';
								echo '<input type="radio" name="new_database" id="databaseRadios1" value="Me_MuOnline"> ' . config('SQL_DB_2_NAME', true);
							echo '</label>';
						echo '</div><br />';
					}					

					echo '<div class="form-group">';
						echo '<label for="input_2">Table:</label>';
						echo '<input type="text" class="form-control" id="input_2" name="new_table"/>';
					echo '</div>';

					echo '<div class="form-group">';
						echo '<label for="input_3">Credits Column:</label>';
						echo '<input type="text" class="form-control" id="input_3" name="new_credits_column"/>';
					echo '</div>';

					echo '<div class="form-group">';
						echo '<label for="input_4">User Column:</label>';
						echo '<input type="text" class="form-control" id="input_4" name="new_user_column"/>';
					echo '</div>';
					
					echo '<label>User Identifier:</label>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="new_user_column_id" id="coRadios1" value="userid" checked> User ID';
						echo '</label>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo '<label>';
							echo '<input type="radio" name="new_user_column_id" id="coRadios1" value="username"> Username';
						echo '</label>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo '<label>';
							echo '<input type="radio" name="new_user_column_id" id="coRadios1" value="email"> Email';
						echo '</label>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo '<label>';
							echo '<input type="radio" name="new_user_column_id" id="coRadios1" value="character"> Character Name';
						echo '</label>';
					echo '</div><br />';

					echo '<label>Check Online Status:</label>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="new_checkonline" id="coRadios1" value="1" checked> Yes';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="new_checkonline" id="coRadios1" value="0"> No';
						echo '</label>';
					echo '</div><br />';

					echo '<label>Display in My Account:</label>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="new_display" id="coRadios1" value="1" checked> Yes';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="new_display" id="coRadios1" value="0"> No';
						echo '</label>';
					echo '</div><br />';	

					echo '<button type="submit" name="new_submit" value="1" class="btn btn-default">Save Configuration</button>';
				echo '</form>';

			echo '</div>';
			echo '</div>';
		} else {
			// EDIT
			$creditSystem->setConfigId($_GET['edit']);
			$cofigsData = $creditSystem->showConfigs(true);
			echo '<div class="panel panel-yellow">';
			echo '<div class="panel-heading">Edit Configuration</div>';
			echo '<div class="panel-body">';
				echo '<form role="form" action="'.admincp_base("creditsconfigs").'" method="post">';
				echo '<input type="hidden" name="edit_id" value="'.$cofigsData['config_id'].'"/>';
					echo '<div class="form-group">';
						echo '<label for="input_1">Title:</label>';
						echo '<input type="text" class="form-control" id="input_1" name="edit_title" value="'.$cofigsData['config_title'].'"/>';
					echo '</div>';

					echo '<label>Database:</label>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="edit_database" id="databaseRadios1" value="MuOnline" '.($cofigsData['config_database'] == "MuOnline" ? 'checked' : null).'> ' . config('SQL_DB_NAME', true);
						echo '</label>';
					echo '</div>';
					
					if(config('SQL_USE_2_DB',true)) {
						echo '<div class="radio">';
							echo '<label>';
								echo '<input type="radio" name="edit_database" id="databaseRadios1" value="Me_MuOnline" '.($cofigsData['config_database'] == "Me_MuOnline" ? 'checked' : null).'> ' . config('SQL_DB_2_NAME', true);
							echo '</label>';
						echo '</div><br />';
					}

					echo '<div class="form-group">';
						echo '<label for="input_2">Table:</label>';
						echo '<input type="text" class="form-control" id="input_2" name="edit_table" value="'.$cofigsData['config_table'].'"/>';
					echo '</div>';

					echo '<div class="form-group">';
						echo '<label for="input_3">Credits Column:</label>';
						echo '<input type="text" class="form-control" id="input_3" name="edit_credits_column" value="'.$cofigsData['config_credits_col'].'"/>';
					echo '</div>';

					echo '<div class="form-group">';
						echo '<label for="input_4">User Column:</label>';
						echo '<input type="text" class="form-control" id="input_4" name="edit_user_column" value="'.$cofigsData['config_user_col'].'"/>';
					echo '</div>';

					echo '<label>User Identifier:</label>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="edit_user_column_id" id="coRadios1" value="userid" '.($cofigsData['config_user_col_id'] == "userid" ? 'checked' : null).'> User ID';
						echo '</label>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo '<label>';
							echo '<input type="radio" name="edit_user_column_id" id="coRadios1" value="username" '.($cofigsData['config_user_col_id'] == "username" ? 'checked' : null).'> Username';
						echo '</label>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo '<label>';
							echo '<input type="radio" name="edit_user_column_id" id="coRadios1" value="email" '.($cofigsData['config_user_col_id'] == "email" ? 'checked' : null).'> Email';
						echo '</label>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo '<label>';
							echo '<input type="radio" name="edit_user_column_id" id="coRadios1" value="character" '.($cofigsData['config_user_col_id'] == "character" ? 'checked' : null).'> Character Name';
						echo '</label>';
					echo '</div><br />';

					echo '<label>Check Online Status:</label>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="edit_checkonline" id="coRadios1" value="1" '.($cofigsData['config_checkonline'] == 1 ? 'checked' : null).'> Yes';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="edit_checkonline" id="coRadios1" value="0" '.($cofigsData['config_checkonline'] == 0 ? 'checked' : null).'> No';
						echo '</label>';
					echo '</div><br />';

					echo '<label>Display in My Account:</label>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="edit_display" id="coRadios1" value="1" '.($cofigsData['config_display'] == 1 ? 'checked' : null).'> Yes';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="edit_display" id="coRadios1" value="0" '.($cofigsData['config_display'] == 0 ? 'checked' : null).'> No';
						echo '</label>';
					echo '</div><br />';

					echo '<button type="submit" name="edit_submit" value="1" class="btn btn-warning">Save Configuration</button>';
				echo '</form>';
			echo '</div>';
			echo '</div>';
		}
	
	echo '</div>';
	echo '<div class="col-md-8">';
		
		$cofigsList = $creditSystem->showConfigs();
		if(is_array($cofigsList)) {
			foreach($cofigsList as $data) {
				
				$checkOnline = ($data['config_checkonline'] ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>');
				$configdisplay = ($data['config_display'] ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>');
				$databaseDisplay = $data['config_database'] == 'MuOnline' ? config('SQL_DB_NAME', true) : config('SQL_DB_2_NAME', true);
				
				echo '<div class="panel panel-default">';
					echo '<div class="panel-heading">';
						echo $data['config_title'];
						echo '<a href="'.admincp_base("creditsconfigs&delete=".$data['config_id']).'" class="btn btn-danger btn-xs pull-right">Delete</a>';
						echo '<a href="'.admincp_base("creditsconfigs&edit=".$data['config_id']).'" class="btn btn-default btn-xs pull-right" style="margin-right:5px;">Edit</a>';
					echo '</div>';
					echo '<div class="panel-body">';
					
						echo '<table class="table" style="margin-bottom:0px;">';
							echo '<tbody>';
								echo '<tr>';
									echo '<th>Config Id</th>';
									echo '<td>'.$data['config_id'].'</td>';
									echo '<th>User Column Identifier</th>';
									echo '<td>'.$data['config_user_col_id'].'</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<th>Database</th>';
									echo '<td>'.$databaseDisplay.'</td>';
									echo '<th>Online Check</th>';
									echo '<td>'.$checkOnline.'</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<th>Table</th>';
									echo '<td>'.$data['config_table'].'</td>';
									echo '<th>Display in My Account</th>';
									echo '<td>'.$configdisplay.'</td>';
								echo '</tr>';
								echo '<tr>';
									echo '<th>Credits Column</th>';
									echo '<td>'.$data['config_credits_col'].'</td>';
									echo '<th></th>';
									echo '<td></td>';
								echo '</tr>';
								echo '<tr>';
									echo '<th>User Column</th>';
									echo '<td>'.$data['config_user_col'].'</td>';
									echo '<th></th>';
									echo '<td></td>';
								echo '</tr>';
							echo '</tbody>';
						echo '</table>';
						
					echo '</div>';
				echo '</div>';
				
			}
		} else {
			message('warning', 'You have not created any configuration.');
		}
		
	echo '</div>';
echo '</div>';