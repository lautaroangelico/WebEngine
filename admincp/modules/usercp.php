<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.0.9.7
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

echo '<h1 class="page-header">UserCP Menu</h1>';

try {
	
	if(check_value($_GET['delete'])) {
		try {
			# cfg
			$newCfg = loadConfig('usercp');
			if(!is_array($newCfg)) throw new Exception('Usercp configs empty.');
			
			if(!check_value($_GET['delete'])) throw new Exception('Invalid id.');
			if(!array_key_exists($_GET['delete'], $newCfg)) throw new Exception('Invalid id.');
			
			unset($newCfg[$_GET['delete']]);
			
			# encode
			$usercpJson = json_encode($newCfg, JSON_PRETTY_PRINT);
			
			# save changes
			$cfgFile = fopen(__PATH_CONFIGS__.'usercp.json', 'w');
			if(!$cfgFile) throw new Exception('There was a problem opening the usercp file.');
			fwrite($cfgFile, $usercpJson);
			fclose($cfgFile);
			
			message('success', 'Changes successfully saved!');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	if(check_value($_POST['usercp_submit'])) {
		try {
			# cfg
			$newCfg = loadConfig('usercp');
			if(!is_array($newCfg)) throw new Exception('Usercp configs empty.');
			
			if(!check_value($_POST['usercp_id'])) throw new Exception('Please fill all the form fields.');
			if(!check_value($_POST['usercp_type'])) throw new Exception('Please fill all the form fields.');
			if(!check_value($_POST['usercp_phrase'])) throw new Exception('Please fill all the form fields.');
			if(!check_value($_POST['usercp_link'])) throw new Exception('Please fill all the form fields.');
			if(!in_array($_POST['usercp_type'], array('internal','external'))) throw new Exception('Link type is not valid.');
			if(!in_array($_POST['usercp_visibility'], array('user','guest','always'))) throw new Exception('Link visibility is not a valid option.');
			
			$elementId = $_POST['usercp_id'];
			
			# build new element data array
			$newElementData = array(
				'active' => (bool) ($_POST['usercp_status'] == 1 ? true : false),
				'type' => $_POST['usercp_type'],
				'phrase' => $_POST['usercp_phrase'],
				'link' => $_POST['usercp_link'],
				'icon' => (check_value($_POST['usercp_icon']) ? $_POST['usercp_icon'] : 'usercp_default.png'),
				'visibility' => $_POST['usercp_visibility'],
				'newtab' => (bool) ($_POST['usercp_newtab'] == 1 ? true : false),
				'order' => (int) $_POST['usercp_order']
			);
			
			# modify usercp array
			$newCfg[$elementId] = $newElementData;
			
			# sort by order
			# http://stackoverflow.com/questions/2699086/sort-multi-dimensional-array-by-value
			usort($newCfg, function($a, $b) {
				return $a['order'] - $b['order'];
			});
			
			# encode
			$usercpJson = json_encode($newCfg, JSON_PRETTY_PRINT);
			
			# save changes
			$cfgFile = fopen(__PATH_CONFIGS__.'usercp.json', 'w');
			if(!$cfgFile) throw new Exception('There was a problem opening the usercp file.');
			fwrite($cfgFile, $usercpJson);
			fclose($cfgFile);
			
			message('success', 'Changes successfully saved!');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	if(check_value($_POST['new_submit'])) {
		try {
			# cfg
			$newCfg = loadConfig('usercp');
			if(!is_array($newCfg)) throw new Exception('Usercp configs empty.');
			
			if(!check_value($_POST['usercp_type'])) throw new Exception('Please fill all the form fields.');
			if(!check_value($_POST['usercp_phrase'])) throw new Exception('Please fill all the form fields.');
			if(!check_value($_POST['usercp_link'])) throw new Exception('Please fill all the form fields.');
			if(!in_array($_POST['usercp_type'], array('internal','external'))) throw new Exception('Link type is not valid.');
			if(!in_array($_POST['usercp_visibility'], array('user','guest','always'))) throw new Exception('Link visibility is not a valid option.');
			
			# build new element data array
			$newElementData = array(
				'active' => (bool) ($_POST['usercp_status'] == 1 ? true : false),
				'type' => $_POST['usercp_type'],
				'phrase' => $_POST['usercp_phrase'],
				'link' => $_POST['usercp_link'],
				'icon' => (check_value($_POST['usercp_icon']) ? $_POST['usercp_icon'] : 'usercp_default.png'),
				'visibility' => $_POST['usercp_visibility'],
				'newtab' => (bool) ($_POST['usercp_newtab'] == 1 ? true : false),
				'order' => (int) $_POST['usercp_order']
			);
			
			# modify usercp array
			$newCfg[] = $newElementData;
			
			# sort by order
			# http://stackoverflow.com/questions/2699086/sort-multi-dimensional-array-by-value
			usort($newCfg, function($a, $b) {
				return $a['order'] - $b['order'];
			});
			
			# encode
			$usercpJson = json_encode($newCfg, JSON_PRETTY_PRINT);
			
			# save changes
			$cfgFile = fopen(__PATH_CONFIGS__.'usercp.json', 'w');
			if(!$cfgFile) throw new Exception('There was a problem opening the usercp file.');
			fwrite($cfgFile, $usercpJson);
			fclose($cfgFile);
			
			message('success', 'Usercp successfully updated!');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	$cfg = loadConfig('usercp');
	if(!is_array($cfg)) throw new Exception('Usercp configs empty.');
	
	echo '<table class="table table-condensed table-bordered table-hover table-striped">';
	echo '<thead>';
		echo '<tr>';
			echo '<th></th>';
			echo '<th>Order</th>';
			echo '<th>Status</th>';
			echo '<th>Link Type</th>';
			echo '<th>Link</th>';
			echo '<th>Phrase</th>';
			echo '<th>Icon</th>';
			echo '<th>Visibility</th>';
			echo '<th>New Tab</th>';
			echo '<th></th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		foreach($cfg as $id => $usercpElement) {
			echo '<form action="?module=usercp" method="post">';
			echo '<input type="hidden" name="usercp_id" value="'.$id.'"/>';
			echo '<tr>';
				echo '<td class="text-center" style="vertical-align:middle;"><a href="?module=usercp&delete='.$id.'" class="btn btn-danger btn-xs"><span class="fa fa-times" aria-hidden="true"></span></a></td>';
				echo '<td style="max-width:70px;"><input type="text" name="usercp_order" class="form-control" value="'.$usercpElement['order'].'"/></td>';
				echo '<td class="text-center" style="vertical-align:middle;">';
					echo '<label class="radio-inline">';
						echo '<input type="radio" name="usercp_status" value="1" '.($usercpElement['active'] ? 'checked' : '').'> Show';
					echo '</label>';
					echo '<label class="radio-inline">';
						echo '<input type="radio" name="usercp_status" value="0" '.(!$usercpElement['active'] ? 'checked' : '').'> Hide';
					echo '</label>';
				echo '</td>';
				echo '<td>';
					echo '<select name="usercp_type" class="form-control">';
						echo '<option value="internal" '.($usercpElement['type'] == 'internal' ? 'selected' : '').'>internal</option>';
						echo '<option value="external" '.($usercpElement['type'] == 'external' ? 'selected' : '').'>external</option>';
					echo '</select>';
				echo '</td>';
				echo '<td><input type="text" name="usercp_link" class="form-control" value="'.$usercpElement['link'].'"/></td>';
				echo '<td><input type="text" name="usercp_phrase" class="form-control" value="'.$usercpElement['phrase'].'"/></td>';
				echo '<td><input type="text" name="usercp_icon" class="form-control" value="'.$usercpElement['icon'].'"/></td>';
				echo '<td>';
					echo '<select name="usercp_visibility" class="form-control">';
						echo '<option value="user" '.($usercpElement['visibility'] == 'user' ? 'selected' : '').'>user</option>';
						echo '<option value="guest" '.($usercpElement['visibility'] == 'guest' ? 'selected' : '').'>guest</option>';
						echo '<option value="always" '.($usercpElement['visibility'] == 'always' ? 'selected' : '').'>always</option>';
					echo '</select>';
				echo '</td>';
				echo '<td class="text-center" style="vertical-align:middle;">';
					echo '<label class="radio-inline">';
						echo '<input type="radio" name="usercp_newtab" value="1" '.($usercpElement['newtab'] ? 'checked' : '').'> Yes';
					echo '</label>';
					echo '<label class="radio-inline">';
						echo '<input type="radio" name="usercp_newtab" value="0" '.(!$usercpElement['newtab'] ? 'checked' : '').'> No';
					echo '</label>';
				echo '</td>';
				echo '<td class="text-center" style="vertical-align:middle;"><button type="submit" name="usercp_submit" value="ok" class="btn btn-primary">save</button></td>';
			echo '</tr>';
			echo '</form>';
		}
		
		# add new element
		echo '<form action="?module=usercp" method="post">';
		echo '<tr><th colspan="10" class="text-center"><br /><br />Add New Element</th></tr>';
		echo '<tr>';
			echo '<td></td>';
			echo '<td style="max-width:70px;"><input type="text" name="usercp_order" class="form-control" value="10"/></td>';
			echo '<td class="text-center" style="vertical-align:middle;">';
				echo '<label class="radio-inline">';
					echo '<input type="radio" name="usercp_status" value="1" checked> Show';
				echo '</label>';
				echo '<label class="radio-inline">';
					echo '<input type="radio" name="usercp_status" value="0"> Hide';
				echo '</label>';
			echo '</td>';
			echo '<td>';
				echo '<select name="usercp_type" class="form-control">';
					echo '<option value="internal" selected>internal</option>';
					echo '<option value="external">external</option>';
				echo '</select>';
			echo '</td>';
			echo '<td><input type="text" name="usercp_link" class="form-control" placeholder="usercp/myaccount"/></td>';
			echo '<td><input type="text" name="usercp_phrase" class="form-control" placeholder="lang_phrase_x"/></td>';
			echo '<td><input type="text" name="usercp_icon" class="form-control" value="usercp_default.png"/></td>';
			echo '<td>';
				echo '<select name="usercp_visibility" class="form-control">';
					echo '<option value="user" selected>user</option>';
					echo '<option value="guest">guest</option>';
					echo '<option value="always">always</option>';
				echo '</select>';
			echo '</td>';
			echo '<td class="text-center" style="vertical-align:middle;">';
				echo '<label class="radio-inline">';
					echo '<input type="radio" name="usercp_newtab" value="1"> Yes';
				echo '</label>';
				echo '<label class="radio-inline">';
					echo '<input type="radio" name="usercp_newtab" value="0" checked> No';
				echo '</label>';
			echo '</td>';
			echo '<td class="text-center" style="vertical-align:middle;"><button type="submit" name="new_submit" value="ok" class="btn btn-success">add</button></td>';
		echo '</tr>';
		echo '</form>';
	echo '</tbody>';
	echo '</table>';
	
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}