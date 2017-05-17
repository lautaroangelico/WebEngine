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

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	echo '<div class="page-title"><span>'.lang('module_titles_txt_11',true).'</span></div>';

	echo '<div class="row">';
		echo '<div class="col-xs-4">';
			echo '<a href="'.__BASE_URL__.'donation/superrewards/" class="thumbnail"><img src="'.__PATH_TEMPLATE_IMG__.'donation/superrewards.jpg"></a>';
		echo '</div>';
		echo '<div class="col-xs-4">';
			echo '<a href="'.__BASE_URL__.'donation/paypal/" class="thumbnail"><img src="'.__PATH_TEMPLATE_IMG__.'donation/paypal.jpg"></a>';
		echo '</div>';
		echo '<div class="col-xs-4">';
			echo '<a href="'.__BASE_URL__.'donation/westernunion/" class="thumbnail"><img src="'.__PATH_TEMPLATE_IMG__.'donation/westernunion.jpg"></a>';
		echo '</div>';
		echo '<div class="col-xs-4">';
			echo '<a href="'.__BASE_URL__.'donation/paymentwall/" class="thumbnail"><img src="'.__PATH_TEMPLATE_IMG__.'donation/paymentwall.jpg"></a>';
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}