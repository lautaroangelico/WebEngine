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

echo '<h1 class="page-header">Language Phrases (current language)</h1>';

try {
	
	if(!is_array($lang)) throw new Exception('Language file is empty.');
	
	echo '<table class="table table-condensed table-bordered table-hover table-striped">';
	echo '<thead>';
		echo '<tr>';
			echo '<th>Phrase Name</th>';
			echo '<th>Content</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		foreach($lang as $phrase => $value) {
		echo '<tr>';
			echo '<td>'.$phrase.'</td>';
			echo '<td>'.htmlspecialchars($value).'</td>';
		echo '</tr>';
		}
	echo '</tbody>';
	echo '</table>';
	
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}