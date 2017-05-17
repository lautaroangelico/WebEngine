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
		<span>'.lang('module_titles_txt_11',true).' &rarr; '.lang('module_titles_txt_22',true).'</span>
	</div>
	<div class="page-content">';
	
		if(mconfig('active')) {
			echo '<iframe src="http://www.superrewards-offers.com/super/offers?h='.mconfig('sr_h').'&uid='.$_SESSION['username'].'" frameborder="0" width="636" height="1200" scrolling="no"></iframe>';
		} else {
			message('error', lang('error_47',true));
		}

echo '
	</div>
</div>
';
	
?>