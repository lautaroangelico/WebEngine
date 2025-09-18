<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(isset($_GET['name'])) {
	try {
		if(!Validator::AlphaNumeric($_GET['name'])) throw new Exception("Invalid character name.");
		$Character = new Character();
		if(!$Character->CharacterExists($_GET['name'])) throw new Exception("Character does not exist.");
		
		if(isset($_POST['characteredit_submit'])) {
			try {
				if($_POST['characteredit_name'] != $_GET['name']) throw new Exception("Invalid character name.");				
				if(!isset($_POST['characteredit_account'])) throw new Exception("Invalid account name.");
				if(!Validator::UnsignedNumber($_POST['characteredit_class'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_level'])) throw new Exception("All the entered values must be numeric.");
				if(isset($_POST['characteredit_resets'])) if(!Validator::UnsignedNumber($_POST['characteredit_resets'])) throw new Exception("All the entered values must be numeric.");
				if(isset($_POST['characteredit_gresets'])) if(!Validator::UnsignedNumber($_POST['characteredit_gresets'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_zen'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_lvlpoints'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_pklevel'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_str'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_agi'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_vit'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_ene'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_cmd'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_mlevel'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_mlexp'])) throw new Exception("All the entered values must be numeric.");
				if(isset($_POST['characteredit_mlnextexp'])) if(!Validator::UnsignedNumber($_POST['characteredit_mlnextexp'])) throw new Exception("All the entered values must be numeric.");
				if(!Validator::UnsignedNumber($_POST['characteredit_mlpoint'])) throw new Exception("All the entered values must be numeric.");
				
				// check online
				if($common->accountOnline($_POST['characteredit_account'])) throw new Exception("The account is currently online.");
				
				// update database
				$updateData = array(
					'name' => $_POST['characteredit_name'],
					'class' => $_POST['characteredit_class'],
					'level' => $_POST['characteredit_level'],
					'zen' => $_POST['characteredit_zen'],
					'lvlpoints' => $_POST['characteredit_lvlpoints'],
					'pklevel' => $_POST['characteredit_pklevel'],
					'str' => $_POST['characteredit_str'],
					'agi' => $_POST['characteredit_agi'],
					'vit' => $_POST['characteredit_vit'],
					'ene' => $_POST['characteredit_ene'],
					'cmd' => $_POST['characteredit_cmd']
				);
				
				if(isset($_POST['characteredit_resets'])) {
					$updateData['resets'] = $_POST['characteredit_resets'];
				}
				
				if(isset($_POST['characteredit_gresets'])) {
					$updateData['gresets'] = $_POST['characteredit_gresets'];
				}
				
				$query = "UPDATE Character SET ";
					$query .= "Class" . " = :class,";
					$query .= "cLevel" . " = :level,";
					if(check_value($updateData['resets'])) $query .= "ResetCount" . " = :resets,";
					if(check_value($updateData['gresets'])) $query .= "MasterResetCount" . " = :gresets,";
					$query .= "Money" . " = :zen,";
					$query .= "LevelUpPoint" . " = :lvlpoints,";
					$query .= "PkLevel" . " = :pklevel,";
					$query .= "Strength" . " = :str,";
					$query .= "Dexterity" . " = :agi,";
					$query .= "Vitality" . " = :vit,";
					$query .= "Energy" . " = :ene,";
					$query .= "Leadership" . " = :cmd";
					$query .= " WHERE Name" . " = :name";
				
				$updateCharacter = $dB->query($query, $updateData);
				if(!$updateCharacter) throw new Exception("Could not update character data.");
				
				// Update master level info
				$updateMlData = array(
					'name' => $_POST['characteredit_name'],
					'level' => $_POST['characteredit_mlevel'],
					'exp' => $_POST['characteredit_mlexp'],
					'points' => $_POST['characteredit_mlpoint']
				);
				
				if(isset($_POST['characteredit_mlnextexp'])) {
					$updateMlData['nextexp'] = $_POST['characteredit_mlnextexp'];
				}
				
				$mlQuery = "UPDATE MasterSkillTree SET ";
					$mlQuery .= "MasterLevel" . " = :level,";
					$mlQuery .= "MasterExperience" . " = :exp,";
					if(check_value($updateMlData['nextexp'])) $mlQuery .= "NextExperience = :nextexp,";
					$mlQuery .= "MasterPoint" . " = :points";
					$mlQuery .= " WHERE Name" . " = :name";
				
				$updateMlCharacter = $dB->query($mlQuery, $updateMlData);
				if(!$updateCharacter) throw new Exception("Master level data could not be updated.");
				
			} catch(Exception $ex) {
				message('error', $ex->getMessage());
			}
		}
		
		$charData = $Character->CharacterData($_GET['name']);
		if(!$charData) throw new Exception("Could not retrieve character information (invalid character).");
		
		echo '<h1 class="page-header">Edit Character: <small>'.$charData['Name'].'</small></h1>';
		
		echo '<form role="form" method="post">';
		echo '<input type="hidden" name="characteredit_name" value="'.$charData['Name'].'"/>';
		echo '<input type="hidden" name="characteredit_account" value="'.$charData['AccountID'].'"/>';
		
		echo '<div class="row">';
			echo '<div class="col-md-6">';
				
				// COMMON
				echo '<div class="panel panel-primary">';
				echo '<div class="panel-heading">Common</div>';
				echo '<div class="panel-body">';
					echo '<table class="table table-no-border table-hover">';
						echo '<tr>';
							echo '<th>Account:</th>';
							echo '<td><a href="'.admincp_base("accountinfo&id=".$common->retrieveUserID($charData['AccountID'])).'">'.$charData['AccountID'].'</a></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<th>Class:</th>';
							echo '<td>';
								echo '<select class="form-control" name="characteredit_class">';
									foreach($custom['character_class'] as $classID => $thisClass) {
										if($classID == $charData['Class']) {
											echo '<option value="'.$classID.'" selected="selected">'.$thisClass[0].' ('.$thisClass[1].')</option>';
										} else {
											echo '<option value="'.$classID.'">'.$thisClass[0].' ('.$thisClass[1].')</option>';
										}
									}
								echo '</select>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<th>Level:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_level" value="'.$charData['cLevel'].'"/></td>';
						echo '</tr>';
						
						if(defined('ResetCount')) {
							echo '<tr>';
								echo '<th>Resets:</th>';
								echo '<td><input class="form-control" type="number" name="characteredit_resets" value="'.$charData['ResetCount'].'"/></td>';
							echo '</tr>';
						}
						
						if(defined('MasterResetCount')) {
							echo '<tr>';
								echo '<th>Grand Resets:</th>';
								echo '<td><input class="form-control" type="number" name="characteredit_gresets" value="'.$charData['MasterResetCount'].'"/></td>';
							echo '</tr>';
						}
						
						echo '<tr>';
							echo '<th>Money:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_zen" value="'.$charData['Money'].'"/></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<th>Level-Up Points:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_lvlpoints" value="'.$charData['LevelUpPoint'].'"/></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<th>PK Level:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_pklevel" value="'.$charData['PkLevel'].'"/></td>';
						echo '</tr>';
					echo '</table>';
				echo '</div>';
				echo '</div>';
				
			echo '</div>';
			echo '<div class="col-md-6">';
			
				// STATS
				echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">Stats</div>';
				echo '<div class="panel-body">';
					echo '<table class="table table-no-border table-hover">';
						echo '<tr>';
							echo '<th>Strength:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_str" value="'.$charData['Strength'].'"/></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<th>Dexterity:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_agi" value="'.$charData['Dexterity'].'"/></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<th>Vitality:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_vit" value="'.$charData['Vitality'].'"/></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<th>Energy:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_ene" value="'.$charData['Energy'].'"/></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<th>Command:</th>';
							echo '<td><input class="form-control" type="number" name="characteredit_cmd" value="'.$charData['Leadership'].'"/></td>';
						echo '</tr>';
					echo '</table>';
				echo '</div>';
				echo '</div>';
				
				// MASTER LEVEL
				if(defined('"MasterSkillTree"')) {
					$mLinfo = $dB->query_fetch_single("SELECT * FROM MasterSkillTree WHERE Name = ?", array($charData['Name']));
					echo '<div class="panel panel-default">';
					echo '<div class="panel-heading">Master Level</div>';
					echo '<div class="panel-body">';
						if(is_array($mLinfo)) {
							echo '<table class="table table-no-border table-hover">';
								echo '<tr>';
									echo '<th>Master Level:</th>';
									echo '<td><input class="form-control" type="number" name="characteredit_mlevel" value="'.$mLinfo['MasterLevel'].'"/></td>';
								echo '</tr>';
								echo '<tr>';
									echo '<th>Experience:</th>';
									echo '<td><input class="form-control" type="number" name="characteredit_mlexp" value="'.$mLinfo['MasterExperience'].'"/></td>';
								echo '</tr>';
								if(defined('NextExperience')) {
									echo '<tr>';
										echo '<th>Next Experience:</th>';
										echo '<td><input class="form-control" type="number" name="characteredit_mlnextexp" value="'.$mLinfo['NextExperience'].'"/></td>';
									echo '</tr>';
								}
								echo '<tr>';
									echo '<th>Points:</th>';
									echo '<td><input class="form-control" type="number" name="characteredit_mlpoint" value="'.$mLinfo['MasterPoint'].'"/></td>';
								echo '</tr>';
							echo '</table>';
						} else {
							message('warning', 'Could not retrieve Master Level information.', ' ');
						}
					echo '</div>';
					echo '</div>';
				}
				
			echo '</div>';
		echo '</div>';
		
		echo '<div class="row">';
			echo '<div class="col-md-12">';
				echo '<button type="submit" class="btn btn-large btn-block btn-success" name="characteredit_submit" value="ok">Save Changes</button>';
			echo '</div>';
		echo '</div>';
		
		echo '</form>';
	} catch(Exception $ex) {
		echo '<h1 class="page-header">Account Information</h1>';
		message('error', $ex->getMessage());
	}
	
} else {
	echo '<h1 class="page-header">Account Information</h1>';
	message('error', 'Please provide a valid user id.');
}
?>