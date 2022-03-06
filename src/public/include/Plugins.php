<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

class Plugins
{
    private Database $db;
    private array $reg_plugins = [];

    function __construct($db)
    {
        $this->db = $db;
    }

    function scanDir()
    {

        foreach (glob('modules/*', GLOB_ONLYDIR) as $plugin_dir) {
            $plugin_name = basename($plugin_dir);
            array_push($this->reg_plugins, $plugin_name);
        }
        //var_dump($this->reg_plugins);
    }
}
