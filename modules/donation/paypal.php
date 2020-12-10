<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.2
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!isLoggedIn()) redirect(1,'login');
if(!mconfig('active')) throw new Exception(lang('error_47'));

// Module title
echo '<div class="page-title"><span>'.lang('module_titles_txt_21').'</span></div>';

// PayPal conversion rate
echo '<span id="paypal_conversion_rate_value" style="display:none;">'.mconfig('paypal_conversion_rate').'</span>';

echo '<div class="paypal-gateway-container">';
	echo '<div class="paypal-gateway-content">';
		echo '<div class="paypal-gateway-logo"></div>';
		
		echo '<div class="paypal-gateway-form">';
			echo '<div>';

				if(mconfig('paypal_enable_sandbox')) {
					echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">';
				} else {
					echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
				}

				$order_id = md5(time());

				echo '<input type="hidden" name="cmd" value="_xclick" />';
				echo '<input type="hidden" name="business" value="'.mconfig('paypal_email').'" />';
				echo '<input type="hidden" name="item_name" value="'.mconfig('paypal_title').'" />';
				echo '<input type="hidden" name="item_number" value="'.$order_id.'" />';
				echo '<input type="hidden" name="currency_code" value="'.mconfig('paypal_currency').'" />';
				echo '$ <input type="text" name="amount" id="amount" maxlength="3"/> '.mconfig('paypal_currency').' = <span id="result">0</span> '.lang('donation_txt_2');
			echo '</div>';
		echo '</div>';
		
		echo '<div class="paypal-gateway-continue">';
			echo '<input type="hidden" name="no_shipping" value="1" />';
			echo '<input type="hidden" name="shipping" value="0.00" />';
			echo '<input type="hidden" name="return" value="'.mconfig('paypal_return_url').'" />';
			echo '<input type="hidden" name="cancel_return" value="'.mconfig('paypal_return_url').'" />';
			echo '<input type="hidden" name="notify_url" value="'.mconfig('paypal_notify_url').'" />';
			echo '<input type="hidden" name="custom" value="'.$_SESSION['userid'].'" />';
			echo '<input type="hidden" name="no_note" value="1" />';
			echo '<input type="hidden" name="tax" value="0.00" />';
			echo '<input type="submit" name="submit" value="" />';
			echo '</form>';
		echo '</div>';
		
	echo '</div>';
echo '</div>';