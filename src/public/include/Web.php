<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

class Web
{
    private User $user;
    private Database $db;
    private Plugins $plugins;    
    private array $cfg = [];
    private array $lng = [];
    private array $valid_pages = [];
    private $actions = [];


    function __construct(array $cfg, array $lng)
    {
        $this->cfg = $cfg;
        $this->lng = $lng;

        $this->db = new Database($cfg);
        $this->db->connect();

        $this->user = new User($cfg, $this->db);

        $this->plugins = new Plugins($this->db);

        $this->plugins->scanDir();
    }

    function show()
    {
        $page = $this->ValReqPage();
        if (empty($page)) {
            exit('Error:  Page not exists or validation error');
        }
        $page_data = $this->getPageData($page);
        //TODO Error/missing page if ($page_data === false)
        $this->render($page_data);
    }

    private function ValReqPage()
    {
        $req_page = Filters::getString('page');
        (!isset($req_page) || $req_page == '') ? $req_page = 'index' : null;

        empty($this->user) || $this->user->getId() < 1 ? $req_page = 'login' : null;

        //echo $this->user->getId();

        if (in_array($req_page, $this->valid_pages)) {
            return $req_page;
        }

        return false;
    }

    function getPageData(string $page)
    {
        $page_func = 'page_' . $page;

        $page_defaults = [];
        $page_data = [];

        $page_defaults = $this->page_defaults();

        //$page_data = $page_func($this->cfg, $this->lng, $this->user);
        if ($page == 'login') {
            $page_data = $this->page_login();
        } else if ($page == 'logout') {
            //TODO
            $page_data['page'] = 'index';
        } else if ($page === 'index') {
            $page_data['page'] = 'index';
            //  $page_data = page_index($this->cfg, $this->db, $this->lng, $this->user);
        }

        return array_merge($page_defaults, $page_data);
    }

    function render(array $page_data)
    {
        $frontend = new Frontend($this->cfg, $this->lng);
        $frontend->showPage($page_data);
    }

    function page_defaults()
    {

        $page = [];

        $user_profile = $this->user->getUser();

        !empty($user_profile['theme']) ? $this->cfg['theme'] = $user_profile['theme'] : null;
        !empty($user_profile['lang']) ? $this->cfg['lang'] = $user_profile['lang'] : null;
        !empty($user_profile['charset']) ? $this->cfg['charset'] = $user_profile['charset'] : null;;
        $page['web_title'] = $this->cfg['web_title'];

        return $page;
    }

    function page_login()
    {
        $page = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = Filters::postUsername('username');
            $password = Filters::postPassword('password');
            if (!empty($username) && !empty($password)) {

                $userid = $this->user->checkUser($username, $password);
                if (!empty($userid) && $userid > 0) {
                    $this->user->setUser($userid);
                    if (empty($cfg['rel_path'])) {
                        $cfg['rel_path'] = '/';
                    }
                    header("Location: {$cfg['rel_path']} ");

                    exit();
                }
            }
        }

        $page['page'] = 'login';
        $page['tpl'] = 'login';
        $page['log_in'] = $this->lng['L_LOGIN'];
        $page['username_placeholder'] = $this->lng['L_USERNAME'];
        $page['password_placeholder'] = $this->lng['L_PASSWORD'];

        return $page;
    }

    /*
        ACTIONS
    */

    function regAction($event, $func, $priority = 5)
    {
        $this->actions[$event][] = ['function_name' => $func, 'priority' => $priority];
    }

    function runAction($event, &$params = null)
    {

        if (isset($this->actions[$event])) {
            usort($this->actions[$event], function ($a, $b) {
                return $a['priority'] - $b['priority'];
            });

            $return = '';

            foreach ($this->actions[$event] as $func) {

                if (is_array($func['function_name'])) {
                    if (method_exists($func['function_name'][0], $func['function_name'][1])) {

                        $return .= call_user_func_array($func['function_name'], [&$params]);
                    }
                } else {
                    if (function_exists($func['function_name'])) {
                        $return .= call_user_func_array($func['function_name'], [&$params]);
                    }
                }
            }
        }
        return $return;
    }

    function issetAction($this_event)
    {

        foreach ($this->actions as $event => $func) {
            if (($event == $this_event) && function_exists($func[0])) {
                return true;
            }
        }

        return false;
    }
}
