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
?>
<h1 class="page-header">PayPal Donations</h1>
<?php
try {
	$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);
	
	$paypalDonations = $database->query_fetch("SELECT * FROM ".WEBENGINE_PAYPAL_TRANSACTIONS." ORDER BY id DESC");
	if(!is_array($paypalDonations)) throw new Exception("There are no PayPal transactions in the database.");
	
	echo '<table id="paypal_donations" class="table table-condensed table-hover">';
	echo '<thead>';
		echo '<tr>';
			echo '<th>Transaction ID</th>';
			echo '<th>Account</th>';
			echo '<th>Amount</th>';
			echo '<th>PayPal Email</th>';
			echo '<th>Date</th>';
			echo '<th>Status</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach($paypalDonations as $data) {
		$userData = $common->accountInformation($data['user_id']);
		$donation_status = ($data['transaction_status'] == 1 ? '<span class="badge badge-success">ok</span>' : '<span class="badge badge-important">reversed</span>');
		
		echo '<tr>';
			echo '<td>'.$data['transaction_id'].'</td>';
			echo '<td><a href="'.admincp_base("accountinfo&id=".$data['user_id']).'">'.$userData[_CLMN_USERNM_].'</a></td>';
			echo '<td>$'.$data['payment_amount'].'</td>';
			echo '<td>'.$data['paypal_email'].'</td>';
			echo '<td>'.date("m/d/Y h:i A",$data['transaction_date']).'</td>';
			echo '<td>'.$donation_status.'</td>';
		echo '</tr>';
	}
	echo '
	</tbody>
	</table>';
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}
?>