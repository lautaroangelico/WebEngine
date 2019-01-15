<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.1.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	echo '<div class="page-title"><span>'.lang('module_titles_txt_11',true).'</span></div>';

	echo '<div class="row">';
		echo '<div class="col-xs-4">';
			echo '<a href="'.__BASE_URL__.'donation/paypal/" class="thumbnail"><img src="'.__PATH_TEMPLATE_IMG__.'donation/paypal.jpg"></a>';
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}