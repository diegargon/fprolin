<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('config/config.priv.php');

/*
if (!file_exists($cfg['cfg_file'])) {
    echo '<br> Missed config file ' . $cfg['cfg_file'] . ', please copy the default file (config/' . $cfg['appname'] . '.php) to /etc/' . $cfg['appname'] . ' directory and rename it';
    exit();
}
require_once($cfg['cfg_file']);
*/
require_once('lang/' . $cfg['lang'] . '/main.lang.php');

require_once('include/Database.php');
require_once('include/Filters.php');
require_once('include/User.php');
require_once('include/Web.php');
require_once('include/Frontend.php');
