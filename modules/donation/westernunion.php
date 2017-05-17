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

(!isLoggedIn()) ? redirect(1,'login') : null;

echo '
<div class="page-container">
	<div class="page-title">
		<span>'.lang('module_titles_txt_11',true).' &rarr; '.lang('module_titles_txt_23',true).'</span>
	</div>
	<div class="page-content">';
		
	if(mconfig('active')) {
		
		echo '
			<table class="myaccount-table" cellspacing="0">
				<tr>
					<td colspan="2" align="center"><b>(1) SEND PAYMENT TO:</b></td>
				</tr>
				<tr>
					<td>Name:</td>
					<td>--</td>
				</tr>
				<tr>
					<td>Address:</td>
					<td>--</td>
				</tr>
				<tr>
					<td>City / State:</td>
					<td>--</td>
				</tr>
				<tr>
					<td>Country:</td>
					<td>--</td>
				</tr>
			</table>
			
			<p>(2) Send and email to <b>donations@email.com</b> with the following information:</p>
			<ul>
				<li>Sender Name</li>
				<li>Sender Country</li>
				<li>MTCN</li>
				<li>Amount Sent</li>
				<li>Username</li>
			</ul>';

	} else {
		message('error', lang('error_47',true));
	}
	
echo '
	</div>
</div>
';
?>