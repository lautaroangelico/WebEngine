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

echo '<h1 class="page-header">Navigation Menu</h1>';

try {
	
	if(check_value($_GET['delete'])) {
		try {
			# cfg
			$newCfg = loadConfig('navbar');
			if(!is_array($newCfg)) throw new Exception('Navbar configs empty.');
			
			if(!check_value($_GET['delete'])) throw new Exception('Invalid id.');
			if(!array_key_exists($_GET['delete'], $newCfg)) throw new Exception('Invalid id.');
			
			unset($newCfg[$_GET['delete']]);
			
			# encode
			$navbarJson = json_encode($newCfg, JSON_PRETTY_PRINT);
			
			# save changes
			$cfgFile = fopen(__PATH_CONFIGS__.'navbar.json', 'w');
			if(!$cfgFile) throw new Exception('There was a problem opening the navbar file.');
			fwrite($cfgFile, $navbarJson);
			fclose($cfgFile);
			
			message('success', 'Changes successfully saved!');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	if(check_value($_POST['navbar_submit'])) {
		try {
			# cfg
			$newCfg = loadConfig('navbar');
			if(!is_array($newCfg)) throw new Exception('Navbar configs empty.');
			
			if(!check_value($_POST['navbar_id'])) throw new Exception('Please fill all the form fields.');
			if(!check_value($_POST['navbar_type'])) throw new Exception('Please fill all the form fields.');
			if(!check_value($_POST['navbar_phrase'])) throw new Exception('Please fill all the form fields.');
			if(!in_array($_POST['navbar_type'], array('internal','external'))) throw new Exception('Link type is not valid.');
			if(!in_array($_POST['navbar_visibility'], array('user','guest','always'))) throw new Exception('Link visibility is not a valid option.');
			
			$elementId = $_POST['navbar_id'];
			
			# build new element data array
			$newElementData = array(
				'active' => (bool) ($_POST['navbar_status'] == 1 ? true : false),
				'type' => $_POST['navbar_type'],
				'phrase' => $_POST['navbar_phrase'],
				'link' => (check_value($_POST['navbar_link']) ? $_POST['navbar_link'] : ''),
				'visibility' => $_POST['navbar_visibility'],
				'newtab' => (bool) ($_POST['navbar_newtab'] == 1 ? true : false),
				'order' => (int) $_POST['navbar_order']
			);
			
			# modify navbar array
			$newCfg[$elementId] = $newElementData;
			
			# sort by order
			# http://stackoverflow.com/questions/2699086/sort-multi-dimensional-array-by-value
			usort($newCfg, function($a, $b) {
				return $a['order'] - $b['order'];
			});
			
			# encode
			$navbarJson = json_encode($newCfg, JSON_PRETTY_PRINT);
			
			# save changes
			$cfgFile = fopen(__PATH_CONFIGS__.'navbar.json', 'w');
			if(!$cfgFile) throw new Exception('There was a problem opening the navbar file.');
			fwrite($cfgFile, $navbarJson);
			fclose($cfgFile);
			
			message('success', 'Changes successfully saved!');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	if(check_value($_POST['new_submit'])) {
		try {
			# cfg
			$newCfg = loadConfig('navbar');
			if(!is_array($newCfg)) throw new Exception('Navbar configs empty.');
			
			if(!check_value($_POST['navbar_type'])) throw new Exception('Please fill all the form fields.');
			if(!check_value($_POST['navbar_phrase'])) throw new Exception('Please fill all the form fields.');
			if(!in_array($_POST['navbar_type'], array('internal','external'))) throw new Exception('Link type is not valid.');
			if(!in_array($_POST['navbar_visibility'], array('user','guest','always'))) throw new Exception('Link visibility is not a valid option.');
			
			# build new element data array
			$newElementData = array(
				'active' => (bool) ($_POST['navbar_status'] == 1 ? true : false),
				'type' => $_POST['navbar_type'],
				'phrase' => $_POST['navbar_phrase'],
				'link' => (check_value($_POST['navbar_link']) ? $_POST['navbar_link'] : ''),
				'visibility' => $_POST['navbar_visibility'],
				'newtab' => (bool) ($_POST['navbar_newtab'] == 1 ? true : false),
				'order' => (int) $_POST['navbar_order']
			);
			
			# modify navbar array
			$newCfg[] = $newElementData;
			
			# sort by order
			# http://stackoverflow.com/questions/2699086/sort-multi-dimensional-array-by-value
			usort($newCfg, function($a, $b) {
				return $a['order'] - $b['order'];
			});
			
			# encode
			$navbarJson = json_encode($newCfg, JSON_PRETTY_PRINT);
			
			# save changes
			$cfgFile = fopen(__PATH_CONFIGS__.'navbar.json', 'w');
			if(!$cfgFile) throw new Exception('There was a problem opening the navbar file.');
			fwrite($cfgFile, $navbarJson);
			fclose($cfgFile);
			
			message('success', 'Navbar successfully updated!');
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	$cfg = loadConfig('navbar');
	if(!is_array($cfg)) throw new Exception('Navbar configs empty.');
	
	echo '<table class="table table-condensed table-bordered table-hover table-striped">';
	echo '<thead>';
		echo '<tr>';
			echo '<th></th>';
			echo '<th>Order</th>';
			echo '<th>Status</th>';
			echo '<th>Link Type</th>';
			echo '<th>Link</th>';
			echo '<th>Phrase</th>';
			echo '<th>Visibility</th>';
			echo '<th>New Tab</th>';
			echo '<th></th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		foreach($cfg as $id => $navbarElement) {
			echo '<form action="?module=navbar" method="post">';
			echo '<input type="hidden" name="navbar_id" value="'.$id.'"/>';
			echo '<tr>';
				echo '<td class="text-center" style="vertical-align:middle;"><a href="?module=navbar&delete='.$id.'" class="btn btn-danger btn-xs"><span class="fa fa-times" aria-hidden="true"></span></a></td>';
				echo '<td style="max-width:70px;"><input type="text" name="navbar_order" class="form-control" value="'.$navbarElement['order'].'"/></td>';
				echo '<td class="text-center" style="vertical-align:middle;">';
					echo '<label class="radio-inline">';
						echo '<input type="radio" name="navbar_status" value="1" '.($navbarElement['active'] ? 'checked' : '').'> Show';
					echo '</label>';
					echo '<label class="radio-inline">';
						echo '<input type="radio" name="navbar_status" value="0" '.(!$navbarElement['active'] ? 'checked' : '').'> Hide';
					echo '</label>';
				echo '</td>';
				echo '<td>';
					echo '<select name="navbar_type" class="form-control">';
						echo '<option value="internal" '.($navbarElement['type'] == 'internal' ? 'selected' : '').'>internal</option>';
						echo '<option value="external" '.($navbarElement['type'] == 'external' ? 'selected' : '').'>external</option>';
					echo '</select>';
				echo '</td>';
				echo '<td><input type="text" name="navbar_link" class="form-control" value="'.$navbarElement['link'].'"/></td>';
				echo '<td><input type="text" name="navbar_phrase" class="form-control" value="'.$navbarElement['phrase'].'"/></td>';
				echo '<td>';
					echo '<select name="navbar_visibility" class="form-control">';
						echo '<option value="user" '.($navbarElement['visibility'] == 'user' ? 'selected' : '').'>user</option>';
						echo '<option value="guest" '.($navbarElement['visibility'] == 'guest' ? 'selected' : '').'>guest</option>';
						echo '<option value="always" '.($navbarElement['visibility'] == 'always' ? 'selected' : '').'>always</option>';
					echo '</select>';
				echo '</td>';
				echo '<td class="text-center" style="vertical-align:middle;">';
					echo '<label class="radio-inline">';
						echo '<input type="radio" name="navbar_newtab" value="1" '.($navbarElement['newtab'] ? 'checked' : '').'> Yes';
					echo '</label>';
					echo '<label class="radio-inline">';
						echo '<input type="radio" name="navbar_newtab" value="0" '.(!$navbarElement['newtab'] ? 'checked' : '').'> No';
					echo '</label>';
				echo '</td>';
				echo '<td class="text-center" style="vertical-align:middle;"><button type="submit" name="navbar_submit" value="ok" class="btn btn-primary">save</button></td>';
			echo '</tr>';
			echo '</form>';
		}
		
		# add new element
		echo '<form action="?module=navbar" method="post">';
		echo '<tr><th colspan="9" class="text-center"><br /><br />Add New Element</th></tr>';
		echo '<tr>';
			echo '<td></td>';
			echo '<td style="max-width:70px;"><input type="text" name="navbar_order" class="form-control" value="10"/></td>';
			echo '<td class="text-center" style="vertical-align:middle;">';
				echo '<label class="radio-inline">';
					echo '<input type="radio" name="navbar_status" value="1" checked> Show';
				echo '</label>';
				echo '<label class="radio-inline">';
					echo '<input type="radio" name="navbar_status" value="0"> Hide';
				echo '</label>';
			echo '</td>';
			echo '<td>';
				echo '<select name="navbar_type" class="form-control">';
					echo '<option value="internal" selected>internal</option>';
					echo '<option value="external">external</option>';
				echo '</select>';
			echo '</td>';
			echo '<td><input type="text" name="navbar_link" class="form-control" placeholder="rankings/resets"/></td>';
			echo '<td><input type="text" name="navbar_phrase" class="form-control" placeholder="lang_phrase_x"/></td>';
			echo '<td>';
				echo '<select name="navbar_visibility" class="form-control">';
					echo '<option value="user" selected>user</option>';
					echo '<option value="guest">guest</option>';
					echo '<option value="always">always</option>';
				echo '</select>';
			echo '</td>';
			echo '<td class="text-center" style="vertical-align:middle;">';
				echo '<label class="radio-inline">';
					echo '<input type="radio" name="navbar_newtab" value="1"> Yes';
				echo '</label>';
				echo '<label class="radio-inline">';
					echo '<input type="radio" name="navbar_newtab" value="0" checked> No';
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