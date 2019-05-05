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

class Email {
	
	private $_active = false;
	private $_smtp = false;
	
	private $_from;
	private $_name;
	private $_templates = array();
	private $_templatesPath = __PATH_EMAILS__;
	
	private $_smtpHost;
	private $_smtpPort;
	private $_smtpUser;
	private $_smtpPass;
	
	private $_template;
	private $_message;
	private $_to = array();
	private $_subject;
	private $_variables = array();
	private $_values = array();
	
	private $_isCustomTemplate=false;
	
	function __construct() {
		# load configs
		$configs = gconfig('email',true);
		if(!is_array($configs)) throw new Exception(lang('error_90'));
		
		# set configurations
		$this->_active = $configs['active'];
		$this->_smtp = $configs['smtp_active'];
		$this->_from = $configs['send_from'];
		$this->_name = $configs['send_name'];
		$this->_smtpHost = $configs['smtp_host'];
		$this->_smtpPort = $configs['smtp_port'];
		$this->_smtpUser = $configs['smtp_user'];
		$this->_smtpPass = $configs['smtp_pass'];
		
		# check if templates exist
		if(!is_array($configs['email_templates']['template'])) throw new Exception();
		
		# load templates list
		$templates = array();
		foreach($configs['email_templates']['template'] as $template) {
			$templates[$template['filename']] = str_replace("{SERVER_NAME}", config('server_name',true), $template['subject']);
		}
		
		# server name variable
		$this->addVariable("{SERVER_NAME}", config('server_name',true));
		
		# save templates
		$this->_templates = $templates;
		
		# phpmailer instance
		$this->mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
	}
	
	public function setSubject($subject) {
		$this->_subject = $subject;
	}
	
	public function setFrom($email, $name="Unknown") {
		$this->_from = $email;
		$this->_name = $name;
	}
	
	public function setMessage($message) {
		$this->_message = $message;
	}
	
	public function setTemplate($template) {
		if(!array_key_exists($template, $this->_templates)) throw new Exception(lang('error_91'));
		$this->_template = $template;
		$this->_subject = $this->_templates[$template];
	}
	
	public function addVariable($variable, $value) {
		$this->_variables[] = $variable;
		$this->_values[] = $value;
	}
	
	public function addAddress($email) {
		if(!Validator::Email($email)) throw new Exception(lang('error_92'));
		$this->_to[] = $email;
	}
	
	private function _loadTemplate() {
		if(!$this->_template) throw new Exception(lang('error_93'));
		
		// custom template
		if($this->_isCustomTemplate) {
			if(!file_exists($this->_template)) throw new Exception(lang('error_94'));
			return file_get_contents($this->_template);
		}
		
		if(!file_exists($this->_templatesPath . $this->_template . '.txt')) throw new Exception(lang('error_91'));
		return file_get_contents($this->_templatesPath . $this->_template . '.txt');
	}
	
	private function _prepareTemplate() {
		return str_replace($this->_variables, $this->_values, $this->_loadTemplate());
	}
	
	public function send() {
		if(!$this->_active) throw new Exception(lang('error_48',true));
		
		if(!$this->_message) {
			if(!$this->_template) throw new Exception(lang('error_95'));
		}
		
		if(!is_array($this->_to)) throw new Exception(lang('error_96'));
		
		if($this->_smtp) {
			$this->mail->IsSMTP();
			$this->mail->SMTPAuth = true;
			$this->mail->Host = $this->_smtpHost;
			$this->mail->Port = $this->_smtpPort;
			$this->mail->Username = $this->_smtpUser;
			$this->mail->Password = $this->_smtpPass;
		}
		
		$this->mail->SetFrom($this->_from, $this->_name);
		
		foreach($this->_to as $address) {
			$this->mail->AddAddress($address);
		}
		
		if(!$this->_subject) throw new Exception(lang('error_97'));
		$this->mail->Subject = $this->_subject;
		
		if(!$this->_message) {
			$this->mail->MsgHTML($this->_prepareTemplate());
		} else {
			$this->mail->MsgHTML($this->_message);
		}
		
		if($this->mail->Send()) return true;
		return false;
	}
	
	public function setCustomTemplate($template) {
		$this->_template = $template;
		$this->_isCustomTemplate = true;
	}
	
}