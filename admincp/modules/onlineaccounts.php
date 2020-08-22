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

echo '<h1 class="page-header">Online Accounts</h1>';

$Account = new Account();
$serverList = $Account->getServerList();

if(is_array($serverList)) {
	echo '<div class="row">';
		echo '<h3>By Server:</h3>';
		foreach($serverList as $server) {
			echo '<div class="col-xs-12 col-md-4 col-lg-3 text-center">';
				echo '<pre><strong>'.$server.'</strong>: '.number_format($Account->getOnlineAccountCount($server)).'</pre>';
			echo '</div>';
		}
	echo '</div>';
}

echo '<div class="row">';
	echo '<h3>Total Online:</h3>';
	echo '<div class="col-xs-12 col-md-4 col-lg-3 text-center">';
		echo '<pre><strong>TOTAL</strong>: '.number_format($Account->getOnlineAccountCount()).'</pre>';
	echo '</div>';
echo '</div>';

$onlineAccounts = $Account->getOnlineAccountList();
echo '<div class="row">';
	echo '<h3>Account List:</h3>';
	if(is_array($onlineAccounts)) {
		echo '<table class="table table-condensed table-hover">';
			echo '<thead>';
				echo '<tr>';
					echo '<th>Account</th>';
					echo '<th>IP Address</th>';
					echo '<th>Server</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach($onlineAccounts as $row) {
				echo '<tr>';
					echo '<td><a href="'.admincp_base('accountinfo&u='.$row[_CLMN_MS_MEMBID_]).'" target="_blank">'.$row[_CLMN_MS_MEMBID_].'</a></td>';
					echo '<td>'.$row[_CLMN_MS_IP_].'</td>';
					echo '<td>'.$row[_CLMN_MS_GS_].'</td>';
				echo '</tr>';
			}
			echo '</tbody>';
		echo '</table>';
	} else {
		message('warning', 'There are no online accounts.');
	}
echo '</div>';