<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.4
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2022 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!defined('access') or !access or access != 'install') die();

if(check_value($_GET['action'])) {
	if($_GET['action'] == 'install') {
		$_SESSION['install_cstep']++;
		header('Location: install.php');
		die();
	}
}
?>
<h4>Getting Started:</h4><br />
<p>Installing WebEngine CMS is very easy. During the setup process, the installer will help you make sure your web server meets the specified requirements to run the CMS. If you are installing the CMS in a shared web hosting account, make sure your hosting provider allows outgoing remote connections to your Microsoft SQL server port (usually port 1433).</p>

<br />

<h4>Support:</h4><br />
<p>If you are having trouble completing the setup process, feel free to reach out to us in our community support forum and/or discord. If additional help is required, feel free to contact us regarding our WebEngine Premium Support service.</p>
<a href="https://forum.webenginecms.org/" target="_blank" class="btn btn-sm btn-default">WebEngine Support Forum</a>
<a href="https://webenginecms.org/discord" target="_blank" class="btn btn-sm btn-default">Discord</a>

<hr>

<h4>Latest Version:</h4><br />
<p>To ensure the best possible experience with our software, make sure you are installing the latest stable version.</p>
<a href="https://github.com/lautaroangelico/WebEngine/releases" target="_blank" class="btn btn-sm btn-default">GitHub Project</a>

<hr>

<h4>License:</h4><br />
<div class="panel panel-default">
	<div class="panel-body" style="font-size:10px;">
		The MIT License (MIT)<br /><br />

		Copyright (c) 2022 Lautaro Angelico<br /><br />

		Permission is hereby granted, free of charge, to any person obtaining a copy of<br />
		this software and associated documentation files (the "Software"), to deal in<br />
		the Software without restriction, including without limitation the rights to<br />
		use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of<br />
		the Software, and to permit persons to whom the Software is furnished to do so,<br />
		subject to the following conditions:<br /><br />

		The above copyright notice and this permission notice shall be included in all<br />
		copies or substantial portions of the Software.<br /><br />

		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR<br />
		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS<br />
		FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR<br />
		COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER<br />
		IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN<br />
		CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
	</div>
</div>

<hr>

<h4>Thank You:</h4><br />
<p>WebEngine CMS is open-source and we intend to keep it that way! We put a lot of effort and time into making this project possible, and we are very proud of what we have achieved so far. If you like our work, please consider supporting the continued development of the project by acquiring our premium services and/or products. Additionally you may also support this project by spreading the word about WebEngine CMS, giving us feedback and joining us in our support forum and discord.</p>
<br />

<p>To proceed with the installation process click the "Start Installation" button below.</p>

<a href="?action=install" class="btn btn-lg btn-success">Start Installation</a>