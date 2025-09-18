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

echo '<h1 class="page-header">New Registrations</h1>';

	$db = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
	$newRegs = $db->query_fetch("SELECT TOP 200 memb_guid, memb___id, mail_addr FROM MEMB_INFO ORDER BY memb_guid DESC");
	
	if(is_array($newRegs)) {
		echo '<table id="new_registrations" class="table display">';
			echo '<thead>';
			echo '<tr>';
				echo '<th>Id</th>';
				echo '<th>Username</th>';
				echo '<th>Email</th>';
				echo '<th></th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach($newRegs as $thisReg) {
				echo '<tr>';
					echo '<td>'.$thisReg['memb_guid'].'</td>';
					echo '<td>'.$thisReg['memb___id'].'</td>';
					echo '<td>'.$thisReg['mail_addr'].'</td>';
					echo '<td style="text-align:right;"><a href="'.admincp_base("accountinfo&id=".$thisReg['memb_guid']).'" class="btn btn-xs btn-default">Account Information</a></td>';
				echo '</tr>';
			}
			echo '</tbody>';
		echo '</table>';
	}
?>