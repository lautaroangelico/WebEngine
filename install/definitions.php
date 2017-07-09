<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9.5
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2017 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!defined('access') or !access or access != 'install') die();

/**
 * INSTALLER_VERSION
 * installer version (do not change)
 */
define('INSTALLER_VERSION', '1.0.9.5');

$install['PDO_DSN'] = array(
	1 => 'dblib',
	2 => 'sqlsrv',
	3 => 'odbc',
);

$install['SERVER_FILES'] = array(
	'MUE',
	'IGCN',
	'CUSTOM',
);

$install['sql_list'] = array(
	'WEBENGINE_ACTIVE_SESSIONS',
	'WEBENGINE_BANS',
	'WEBENGINE_BAN_LOG',
	'WEBENGINE_BLOCKED_IP',
	'WEBENGINE_CREDITS_CONFIG',
	'WEBENGINE_CREDITS_LOGS',
	'WEBENGINE_CRON',
	'WEBENGINE_DOWNLOADS',
	'WEBENGINE_FLA',
	'WEBENGINE_NEWS',
	'WEBENGINE_PASSCHANGE_REQUEST',
	'WEBENGINE_PAYPAL_TRANSACTIONS',
	'WEBENGINE_PLUGINS',
	'WEBENGINE_REGISTER_ACCOUNT',
	'WEBENGINE_SR_ERROR_LOGS',
	'WEBENGINE_SR_TRANSACTIONS',
	'WEBENGINE_VOTES',
	'WEBENGINE_VOTE_LOGS',
	'WEBENGINE_VOTE_SITES',
);

$install['step_list'] = array(
	array('install_intro.php', 'Intro'),
	array('install_step_1.php', 'Web Server Requirements'),
	array('install_step_2.php', 'Database Connection'),
	array('install_step_3.php', 'Create Tables'),
	array('install_step_4.php', 'Add Cron Jobs'),
	array('install_step_5.php', 'Website Configuration'),
);

$install['cron_jobs'] = array(
	// cron_name,cron_description,cron_file_run,cron_run_time,cron_status,cron_protected,cron_file_md5
	array('Levels Ranking','Scheduled task to update characters level ranking','levels_ranking.php','43200','1','1'),
	array('Resets Ranking','Scheduled task to update characters reset ranking','resets_ranking.php','43200','1','1'),
	array('Killers Ranking','Scheduled task to update top killers ranking','killers_ranking.php','43200','1','1'),
	array('Master Level Ranking','Scheduled task to update characters master level ranking','masterlevel_ranking.php','43200','1','1'),
	array('Guilds Ranking','Scheduled task to update top guilds ranking','guilds_ranking.php','43200','1','1'),
	array('Grand Resets Ranking','Scheduled task to update characters grand reset ranking','grandresets_ranking.php','43200','1','1'),
	array('Online Ranking','Scheduled task to update top online ranking','online_ranking.php','43200','1','1'),
	array('Gens Ranking','Scheduled task to update gens ranking','gens_ranking.php','43200','1','1'),
	array('PvP Last Stand Ranking','Scheduled task to update characters pvp last stand ranking','pvplaststand_ranking.php','43200','1','1'),
	array('Votes Ranking','Scheduled task to update vote rankings','votes_ranking.php','43200','1','1'),
	array('Castle Siege Owner','Saves castle siege guild owner name and logo in cache','castle_siege.php','3600','1','1'),
	array('Ban System','Scheduled task to lift temporal bans','temporal_bans.php','3600','1','1'),
	array('Server Info','Scheduled task to update the sidebar statistics information','server_info.php','300','1','1'),
);