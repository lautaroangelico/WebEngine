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
			<span>'.lang('module_titles_txt_11',true).' &rarr; '.lang('module_titles_txt_21',true).'</span>
		</div>
		<div class="page-content">';
		
if(mconfig('active')) {
			echo '
			<div class="paypal-gateway-container">
				<div class="paypal-gateway-content">
					<div class="paypal-gateway-logo"></div>
					<div class="paypal-gateway-form">
						<div>';
						
							if(mconfig('paypal_enable_sandbox')) {
								echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">';
							} else {
								echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
							}
							
							$order_id = md5(time());
							
							echo '
							<input type="hidden" name="cmd" value="_xclick" />
							<input type="hidden" name="business" value="'.mconfig('paypal_email').'" />
							<input type="hidden" name="item_name" value="'.mconfig('paypal_title').'" />
							<input type="hidden" name="item_number" value="'.$order_id.'" />
							<input type="hidden" name="currency_code" value="'.mconfig('paypal_currency').'" />
							$ <input type="text" name="amount" id="amount" maxlength="3"/> '.mconfig('paypal_currency').' = <span id="result">0</span> '.lang('donation_txt_2',true).'
						</div>
					</div>
					<div class="paypal-gateway-continue">
						<input type="hidden" name="no_shipping" value="1" />
						<input type="hidden" name="shipping" value="0.00" />
						<input type="hidden" name="return" value="'.mconfig('paypal_return_url').'" />
						<input type="hidden" name="cancel_return" value="'.mconfig('paypal_return_url').'" />
						<input type="hidden" name="notify_url" value="'.mconfig('paypal_notify_url').'" />
						<input type="hidden" name="custom" value="'.Encode($_SESSION['userid']).'" />
						<input type="hidden" name="no_note" value="1" />
						<input type="hidden" name="tax" value="0.00" />
						<input type="submit" name="submit" value="" />
						</form>
					</div>
				</div>
			</div>';
	
?>

	<script type="text/javascript">
	document.getElementById('amount').onkeyup = function(ev) {
	  var num = 0;
	  var c = 0;
	  var event = window.event || ev;
	  var code = (event.keyCode) ? event.keyCode : event.charCode;
	  for(num=0;num<this.value.length;num++) {
		c = this.value.charCodeAt(num);
		if(c<48 || c>57) {
		  document.getElementById('result').innerHTML = '0';
		  return false;
		}
	  }
	  num = parseInt(this.value);
	  if(isNaN(num)) {
		document.getElementById('result').innerHTML = '0';
	  } else {
		var result = (<?php echo mconfig('paypal_conversion_rate'); ?>*num).toString();
		document.getElementById('result').innerHTML = result;
	  }
	}
	</script>
	
<?php
	
} else {
	message('error', lang('error_47',true));
}

echo '
		</div>
	</div>
	';
	
?>