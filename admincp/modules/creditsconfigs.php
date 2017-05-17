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

echo '<h1 class="page-header">Credit Configurations</h1>';

$creditSystem = new CreditSystem($common, new Character(), $dB, $dB2);

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
							echo '<input type="radio" name="new_database" id="databaseRadios1" value="MuOnline" checked> MuOnline';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="new_database" id="databaseRadios1" value="Me_MuOnline"> Me_MuOnline';
						echo '</label>';
					echo '</div><br />';	

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
							echo '<input type="radio" name="edit_database" id="databaseRadios1" value="MuOnline" '.($cofigsData['config_database'] == "MuOnline" ? 'checked' : null).'> MuOnline';
						echo '</label>';
					echo '</div>';
					echo '<div class="radio">';
						echo '<label>';
							echo '<input type="radio" name="edit_database" id="databaseRadios1" value="Me_MuOnline" '.($cofigsData['config_database'] == "Me_MuOnline" ? 'checked' : null).'> Me_MuOnline';
						echo '</label>';
					echo '</div><br />';

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
		
		echo '<div class="panel panel-default">';
		echo '<div class="panel-heading">Configurations</div>';
		echo '<div class="panel-body">';
			$cofigsList = $creditSystem->showConfigs();
			if(is_array($cofigsList)) {
				echo '<table class="table table-condensed table-hover">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>Title</th>';
						echo '<th>Database</th>';
						echo '<th>Table</th>';
						echo '<th>Credits Column</th>';
						echo '<th>User Column</th>';
						echo '<th>User Column Identifier</th>';
						echo '<th>Online Check</th>';
						echo '<th>Display</th>';
						echo '<th></th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				foreach($cofigsList as $data) {
					
					$checkOnline = ($data['config_checkonline'] ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>');
					$configdisplay = ($data['config_display'] ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>');
					if(check_value($_GET['edit']) && $_GET['edit'] == $data['config_id']) {
						echo '<tr class="warning">';
					} else {
						echo '<tr>';
					}
						echo '<td>'.$data['config_title'].'</td>';
						echo '<td>'.$data['config_database'].'</td>';
						echo '<td>'.$data['config_table'].'</td>';
						echo '<td>'.$data['config_credits_col'].'</td>';
						echo '<td>'.$data['config_user_col'].'</td>';
						echo '<td>'.$data['config_user_col_id'].'</td>';
						echo '<td>'.$checkOnline.'</td>';
						echo '<td>'.$configdisplay.'</td>';
						echo '<td>';
							echo '<a href="'.admincp_base("creditsconfigs&edit=".$data['config_id']).'" class="btn btn-default btn-xs">Edit</a> ';
							echo '<a href="'.admincp_base("creditsconfigs&delete=".$data['config_id']).'" class="btn btn-danger btn-xs">Delete</a>';
						echo '</td>';
					echo '</tr>';
				}
				echo '
				</tbody>
				</table>';
			} else {
				message('warning', 'You have not created any configuration.');
			}
		echo '</div>';
		echo '</div>';
		
	echo '</div>';
echo '</div>';