<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

echo '<div class="page-title"><span>'.lang('module_titles_txt_26',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	if(isset($_POST['submit'])) {
		try {
			if(!isset($_POST['contact_email'])) throw new Exception(lang('error_41',true));
			if(!isset($_POST['contact_message'])) throw new Exception(lang('error_41',true));
			if(!Validator::Email($_POST['contact_email'])) throw new Exception(lang('error_9',true));
			if(!Validator::Length($_POST['contact_message'], 300, 10)) throw new Exception(lang('error_57',true));
			
			$emailConfigs = gconfig('email',true);
			if(!is_array($emailConfigs)) throw new Exception(lang('error_21', true));
			
			$email = new Email();
			$email->setSubject(mconfig('subject'));
			$email->setFrom($emailConfigs['send_from'], $emailConfigs['send_name'] . ' - Contact Form');
			$email->setReplyTo($_POST['contact_email']);
			$email->setMessage($_POST['contact_message']);
			$email->addAddress(mconfig('sendto'));
			$email->send();

			message('success', lang('success_22',true));
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}

	echo '<div class="panel panel-general">';
		echo '<div class="panel-body">';
			echo '<form action="" method="post">';
				echo '<div class="form-group">';
					echo '<label for="contactInput1">'.lang('contactus_txt_1',true).'</label>';
					echo '<input type="email" class="form-control" id="contactInput1" name="contact_email">';
				echo '</div>';
				echo '<div class="form-group">';
					echo '<label for="contactInput2">'.lang('contactus_txt_2',true).'</label>';
					echo '<textarea class="form-control" id="contactInput2" style="height:250px;" name="contact_message"></textarea>';
				echo '</div>';
				echo '<button type="submit" name="submit" value="submit" class="btn btn-primary">'.lang('contactus_txt_3',true).'</button>';
			echo '</form>';
		echo '</div>';
	echo '</div>';

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}