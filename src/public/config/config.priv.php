<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

$cfg['appname'] = 'fprolin';
#$cfg['cfg_file'] = '/etc/'. $cfg['appname'] .'/'. $cfg['appname']. '.php';
$cfg['cfg_file'] = 'config/'. $cfg['appname']. '.php';
$cfg['version'] = 1;
$cfg['sqlite_db'] = './data/'. $cfg['appname'] .'.db';
$cfg['theme'] = 'default';
$cfg['css'] = 'default';
$cfg['charset'] = 'utf-8';
$cfg['web_title'] = $cfg['appname'];
$cfg['lang'] = 'en';
$cfg['sid_expire'] = 65000;
$cfg['rel_path'] = '/';
$cfg['wos_socket'] = '/var/run/wosproxy.socket';
