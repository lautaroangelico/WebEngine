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

include('../includes/webengine.php');

$cs = cs_CalculateTimeLeft();
$timeLeft = (check_value($cs) ? $cs : 0);

echo json_encode(
	array(
		'TimeLeft' => $timeLeft
	)
);
