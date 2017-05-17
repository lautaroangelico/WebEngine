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

if(!defined('access') or !access or access != 'install') die();

if(check_value($_GET['action'])) {
	if($_GET['action'] == 'install') {
		$_SESSION['install_cstep']++;
		header('Location: install.php');
		die();
	}
}
?>
<h3>Welcome!</h3>
<p>This panel will guide you through the installation process of WebEngine CMS. If you need help installing your website, feel free to join our support forum at: <a href="http://forum.muengine.net/" target="_blank">http://forum.muengine.net/</a></p>
<br />

<p>WebEngine recommends you to install the website in an external web server (unix based). While we guarantee that our CMS is hack-proof, hosting your website in the same server as your game server is always a potential risk.</p>
<br />

<p>If you like our work please support us by giving us feedback and suggestions at our support forum.</p>
<br />

<p>To proceed with the installation process click the "Start Installation" button below.</p>

<a href="?action=install" class="btn btn-success">Start Installation</a>