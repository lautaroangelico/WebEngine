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

if(!@include_once(__PATH_CLASSES__ . 'phpmailer/PHPMailer.php')) throw new Exception('Could not load class (phpmailer.PHPMailer).');
if(!@include_once(__PATH_CLASSES__ . 'phpmailer/SMTP.php')) throw new Exception('Could not load class (phpmailer.SMTP).');
if(!@include_once(__PATH_CLASSES__ . 'phpmailer/Exception.php')) throw new Exception('Could not load class (phpmailer.Exception).');