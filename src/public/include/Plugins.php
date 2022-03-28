<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

class Plugins
{
    private Web $web;
    private Database $db;
    /* Scan dir */
    private array $reg_plugins = [];
    /* plugins */
    private array $plugins_db = [];


    function __construct(Web $web)
    {
        $this->db = $web->getProvider('Database');
        $this->web = $web;

        $this->loadPluginsDB();
        $this->init();
        //pr($this->plugins_db);
    }


    public function init() {
        global $debug, $cfg;
        
        foreach ($this->plugins_db as &$plugin) 
        {
            if ($plugin['enable']) 
            {                
                if(! $this->startPlugin($plugin)) {
                    //TODO ERROR;                    
                    $plugin['fail'] = 1;
                    continue;
                } else {
                    $plugin['started'] = 1;
                }

            }            
        }
    }

    function startPlugin(array &$plugin) {
        require_once('plugins/' . $plugin['name'] . '/' . $plugin['name'] .'.php');
        
        $init_function = $plugin['name'] .'_init';
        if (!function_exists($init_function)) 
        {
            //TODO ERROR
            return false;
        
        } 
        $init_function($this->web);

        return true;
    }    

    private function loadPluginsDB() 
    {
        $result = $this->db->select('plugins');      
        if ($result) {
            $this->plugins_db = $this->db->fetchAll($result);
        }

    }

    private function scanDir()
    {

        foreach (glob('plugins/*', GLOB_ONLYDIR) as $plugin_dir) {
            
            $manifest = $plugin_dir . '/manifest.json';
            
            if (file_exists($manifest)) {
                $jsondata = file_get_contents($manifest);
                $plugin_data = json_decode($jsondata);                                
                array_push($this->reg_plugins, $plugin_data);                
            }
        }
             
    }
    
}
