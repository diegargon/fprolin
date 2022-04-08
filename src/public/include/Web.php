<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

class Web
{
    private Database $db;
    private Plugins $plugins;
    private array $cfg = [];
    private array $lng = [];
    /*
        valid_pages['index'] => ['plugin_name' => 'mymodule',...];
    */
    private array $valid_pages = [];
    /*
        $provider['SessionManager'] = ['value' => $sm, 'version' => '1.0',...];
    */
    private array $provider = [];
    private $actions = [];


    function __construct(array $cfg)
    {
        $this->cfg = $cfg;
        //$this->lng = $lng;

        $this->db = new Database($cfg);
        $this->db->connect();
        $this->provider['Database'] = ['value' => $this->db, 'version' => '1.0'];
        //$this->provider['Language'] =  ['value' => $this->lng, 'version' => '1.0'];
        //$this->provider['Config'] =  ['value' => $this->cfg, 'version' => '1.0'];

        $this->plugins = new Plugins($this);
    }

    function show()
    {
        $req_page = Filters::getString('page');
        $page = $this->pageExists($req_page);

        if (!$page) {
            exit('Error: Page not exists');
        }
        //pr($page);
        $page_data = $this->getPageData($page);
        if (is_array($page_data) && !empty($page_data['redirect'])) {
            $tdata = [];
            $page = $this->pageExists($page_data['redirect']);
            //TODO: $page is false/not exists
            //var_dump($page);
            !empty($page_data['tdata']) ? $tdata = $page_data['tdata'] : null;
            //pr($page);
            $page_data = $this->getPageData($page);
            empty($page_data['tdata']) ? $page_data['tdata'] = $tdata : $page_data['tdata']  = array_merge($page_data['tdata'], $tdata);
        }
        //pr($page_data);

        if (is_array($page_data)) {
            $this->render($page_data);
        } else {
            //TODO Error no page_data
        }
    }

    function getPageData(array $page)
    {
        $page_func =  $page['func_name'];
        $page_data = [];
        if (function_exists($page_func)) {
            $page_data = $page_func($this);
        } else {
            //TODO error
        }

        return $page_data;
    }

    function setPage(array $page)
    {
        $this->valid_pages = array_merge($page, $this->valid_pages);
    }

    function getPage(string $page)
    {
        return $this->valid_pages[$page];
    }

    function getProvider(string $prov)
    {

        foreach ($this->provider as $key_prov => $val_prov) {
            if ($key_prov ==  $prov && !empty($val_prov['value'])) {
                return $val_prov['value'];
            }
        }

        return false;
    }

    function setProvider(array $providers)
    {
        foreach ($providers as $key_provider => $val_provider) {
            if (!isset($this->provider[$key_provider])) {
                $this->provider[$key_provider] = $val_provider;
            } else {
                //TODO: Error provider already set
            }
        }
        return true;
    }

    private function pageExists(string $req_page)
    {
        (!isset($req_page) || $req_page == '') ? $req_page = 'index' : null;

        foreach ($this->valid_pages as $k_val_page => $v_val_page) {
            if ($k_val_page == $req_page) {
                return $v_val_page;
            }
        }
        return false;
    }

    function render(array $page_data)
    {
        //pr($page_data);
        $frontend = new Frontend($this->cfg, $this->lng);
        $frontend->showPage($page_data);
    }

    function getConfig() 
    {
        return $this->cfg;        
    }
    function setConfig($cfg) {
        $this->cfg = array_merge($this->cfg, $cfg);
    }
    function getLang() {
        return $this->lng;
    }

    function setLang($lng) {
        $this->lng = array_merge($this->lng, $lng);
    }

    /* 
        Refresher
    */
    function refresh() {
        $req_page = Filters::getString('page');    
        $sm = $this->getProvider('SessionManager');
        $id = $sm->getId();        
        $isAdmin = $sm->isAdmin() ? true : false;

        $ret = [];

        if(empty($id) || $id < 1 || !$isAdmin) {
            echo '{"result": "fail", "error", "identification error"}';
            return false;
        }

        $ret[] = $this->runAction('refresh_'. $req_page, [$this]);

        foreach($ret as $result) {
            echo json_encode($result);
        }

    } 
    /*
        ACTIONS
    */

    function regAction(string $event, $func, $priority = 5)
    {
        $this->actions[$event][] = ['func_name' => $func, 'priority' => $priority];
    }

    function runAction(string $event, $params = null)
    {

        $return = [];

        if (isset($this->actions[$event])) {
            usort($this->actions[$event], function ($a, $b) {
                return $a['priority'] - $b['priority'];
            });

            foreach ($this->actions[$event] as $func) {
                if (is_array($func['func_name'])) {
                    if (method_exists($func['func_name'][0], $func['func_name'][1])) {
                        $return[] = call_user_func_array($func['func_name'], $params);
                    }
                } else {
                    if (function_exists($func['func_name'])) {
                        $return[] = call_user_func_array($func['func_name'], $params);
                    }
                }
            }
        }
        return $return;
    }

    function issetAction(string $this_event)
    {

        foreach ($this->actions as $event => $func) {
            if (($event == $this_event) && function_exists($func[0]['func_name'])) {
                return true;
            }
        }

        return false;
    }
}
