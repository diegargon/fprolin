<?php

class Plugins
{
    function scanDir()
    {

        foreach (glob('modules/*', GLOB_ONLYDIR) as $plugins_dir) {
            $filename = str_replace('modules/', '', $plugins_dir);
            $full_json_filename = $plugins_dir . '/' . $filename . '.json';

            if (file_exists($full_json_filename)) {
                $jsondata = file_get_contents($full_json_filename);
                $plugin_data = json_decode($jsondata);

                array_push($this->registered_plugins, $plugin_data);
            }
        }
    }
}
