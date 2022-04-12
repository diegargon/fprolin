<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

function fprolin_init(Web $web)
{
    $cfg = $web->getConfig();
    //$sm = $web->getProvider('SessionManager');
    require_once('lang/' . $cfg['lang'] . '/main.lang.php');
    $web->setLang($lng);

    $plugin_name = 'fprolin';

    $web->setPage(['index' => ['name' => 'index', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_index']]);
    $web->setPage(['error_page' => ['name' => 'error_page', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_error_page']]);
    $web->setPage(['test' => ['name' => 'test', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_test']]);
    $web->setPage(['system' => ['name' => 'test', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_system']]);
    $web->setPage(['login' => ['name' => 'login', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_login']]);
    $web->setPage(['logout' => ['name' => 'logout', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_logout']]);
    $web->setPage(['options' => ['name' => 'options', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_options']]);

    $web->setRetrieve('system', 'get_system.py', '', 'static');
    $web->setRetrieve('system', 'get_system_refresh.py', '', 'dinamic');
    $web->setRetrieve('system', 'get_network.py', '', 'all');
}

function fprolin_main_sketch(Web $web)
{
    $plugin_name = 'fprolin';

    $lng = $web->getLang();

    $topnav_elements['home'] = ['href' => '/', 'caption' => $lng['L_HOME']];
    $topnav_elements['logout'] = ['href' => '/?page=logout', 'caption' => $lng['L_LOGOUT']];

    $side_elements['dashboard'] = ['href' => 'javascript:void(0)', 'caption' => $lng['L_DASHBOARD']];
    $side_elements['dashboard']['submenu']['system'] = ['href' => '/?page=system', 'caption' => $lng['L_SYSTEM']];
    $side_elements['dashboard']['submenu']['test'] = ['href' => '/?page=test', 'caption' => 'Test'];
    $side_elements['network'] = ['href' => '/?page=options', 'caption' => $lng['L_OPTIONS']];

    $page['css'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];
    $page['script_link'][] = 'https://code.jquery.com/jquery-3.6.0.min.js';
    $page['script_file'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];
    $page['load_tpl']['fprolin'] = [
        'plugin' => $plugin_name,
        'tpl' => 'fprolin',
        'sidebar' => $side_elements,
        'topnav' => $topnav_elements,
    ];

    return $page;
}

function fprolin_page_index(Web $web)
{
    $page = [];
    $plugin_name = 'fprolin';

    $sm = $web->getProvider('SessionManager');

    if ($sm === false) {
        $page['redirect'] = 'error_page';
        $page['tdata']['msg'] = 'Error:Missing session manager';
        return $page;
    }
    //pr($sm->getUser());
    if (!$sm->isAdmin()) {
        $page['redirect'] = 'login';
        return $page;
    }

    $page = fprolin_main_sketch($web);

    $page['page'] = 'index';
    $page['load_tpl']['fprolin']['msg'] = 'Hello ' . $sm->getUsername() . '!';

    return $page;
}

function fprolin_error_page(Web $web)
{
    $page = [];
    $plugin_name = 'fprolin';

    $page['load_tpl'][] = [
        'plugin' => $plugin_name,
        'tpl' => 'error_page',
    ];

    $page['plugin'] = $plugin_name;
    $page['tpl'] = 'error_page';
    $page['tpl_plugin'] = $plugin_name;

    return $page;
}

function fprolin_page_login(Web $web)
{

    $page = [];
    $plugin_name = 'fprolin';

    $lng = $web->getLang();
    $sm = $web->getProvider('SessionManager');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $username = Filters::postUsername('username');
        $password = Filters::postPassword('password');

        if (!empty($username) && !empty($password)) {

            $userid = $sm->checkUser($username, $password);

            if (!empty($userid) && $userid > 0) {
                $sm->setUser($userid);
                $page['redirect'] = 'index';
                return $page;
            }
        }
    }

    $page['page'] = 'login';
    $page['css'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];

    $page['load_tpl'][] = [
        'plugin' => $plugin_name,
        'tpl' => 'login',
        'log_in' => $lng['L_LOGIN'],
    ];

    return $page;
}

function fprolin_page_logout(Web $web)
{
    $sm = $web->getProvider('SessionManager');
    $sm->destroy();
    $page['redirect'] = 'login';

    return $page;
}


function fprolin_page_options(Web $web)
{
    $page = fprolin_main_sketch($web);

    $plugin_name = 'fprolin';

    $page['page'] = 'options';
    $page['load_tpl']['options'] = [
        'plugin' => $plugin_name,
        'tpl' => 'options',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    return $page;
}

function fprolin_page_system(Web $web)
{
    $plugin_name = 'fprolin';

    $page = fprolin_main_sketch($web);

    $page['page'] = 'system';


    //Tpl test  Added to main var in fprolin template
    $page['load_tpl']['system'] = [
        'plugin' => $plugin_name,
        'tpl' => 'system',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main',
    ];

    $page_data =  $web->retrieve($page['page'], 'static');
    $page_data = $page_data['data'];

    //pr_dbg($page_data);
    foreach ($page_data as $data) {
        if (!empty($data['id']) && !empty($data['value'])) {
            $page['load_tpl']['system'][$data['id']] = $data['value'];
        }
    }


    //pr_dbg($page);

    return $page;
}
function fprolin_page_test(Web $web)
{
    $plugin_name = 'fprolin';

    $page = fprolin_main_sketch($web);

    $page['page'] = 'test';

    //Direct add
    $page['load_tpl']['fprolin']['msg'] = "Test page";
    //Tpl test  Added to main var in fprolin template
    $page['load_tpl']['test'] = [
        'plugin' => $plugin_name,
        'tpl' => 'test',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    //pr($page);

    return $page;
}
