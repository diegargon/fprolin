<?php

function netconn_init(Web $web)
{
    $cfg = $web->getConfig();
    //$sm = $web->getProvider('SessionManager');
    $lng = $web->getLang();
    require_once('lang/' . $cfg['lang'] . '/netconn.lang.php');
    $lng = $web->setLang($_lng);

    $plugin_name = 'netconn';

    $web->setPage(['localconn' => ['name' => 'localconn', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_localconn']]);
    $web->setPage(['forwardconn' => ['name' => 'forwardconn', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_forwardconn']]);
    $web->setPage(['forwardconnbrief' => ['name' => 'forwardconnbrief', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_forwardconnbrief']]);

    $side_elements['network'] = ['href' => 'javascript:void(0)', 'caption' => $lng['L_NETWORK']];
    $side_elements['network']['submenu']['forwardconn'] = ['href' => '/?page=forwardconn', 'caption' => $lng['L_FORWARDCONN']];
    $side_elements['network']['submenu']['forwardconnbrief'] = ['href' => '/?page=forwardconnbrief', 'caption' => $lng['L_FORWARDCONNBRIEF']];
    $side_elements['network']['submenu']['localconn'] = ['href' => '/?page=localconn', 'caption' => $lng['L_LOCALCONN']];
    $menu['load_tpl']['fprolin']['sidebar'] = $side_elements;
    $web->addWebData($menu);

    $web->setRetrieve('localconn', 'network_conn.py', '', 'all');
    $web->setRetrieve('forwardconn', 'network_fconn.py', '', 'all');
    $web->setRetrieve('forwardconnbrief', 'network_fconn.py', '', 'all');
}

function netconn_page_localconn($web)
{
    $plugin_name = 'netconn';

    $page = $web->getWebData();
    $page['page'] = 'localconn';
    $page_data =  $web->retrieve($page['page'], 'static');
    $page['css'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];

    $page['load_tpl']['localconn'] = [
        'plugin' => $plugin_name,
        'tpl' => 'localconn',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    if (isset($page_data['data']) && is_array($page_data['data'])) {
        $page_data = $page_data['data'];
        //pr_dbg($page_data);

        foreach ($page_data as $data) {
            //pr_dbg($data);
            if (!empty($data['id']) && !empty($data['value'])) {
                $page['load_tpl']['localconn'][$data['id']] = $data['value'];
            }
        }
    }
    //pr_dbg($page);

    return $page;
}

function netconn_page_forwardconn($web)
{
    $plugin_name = 'netconn';

    $page = $web->getWebData();
    $page['page'] = 'forwardconn';
    //$page_data =  $web->retrieve($page['page'], 'static');
    $page['css'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];

    $page['load_tpl']['forwardconn'] = [
        'plugin' => $plugin_name,
        'tpl' => 'forwardconn',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    if (isset($page_data['data']) && is_array($page_data['data'])) {
        $page_data = $page_data['data'];

        //pr_dbg($page_data);

        foreach ($page_data as $data) {
            //pr_dbg($data);
            if (!empty($data['id']) && !empty($data['value'])) {
                $page['load_tpl']['forwardconn'][$data['id']] = $data['value'];
            }
        }
    }
    //pr_dbg($page);

    return $page;
}

function netconn_page_forwardconnbrief($web)
{
    $plugin_name = 'netconn';

    $page = $web->getWebData();
    $page['page'] = 'forwardconnbief';
    $page['css'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];
    //$page_data =  $web->retrieve($page['page'], 'static');

    $page['load_tpl']['forwardconnbrief'] = [
        'plugin' => $plugin_name,
        'tpl' => 'forwardconnbrief',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    if (isset($page_data['data']) && is_array($page_data['data'])) {
        $page_data = $page_data['data'];

        //pr_dbg($page_data);

        foreach ($page_data as $data) {
            //pr_dbg($data);
            if (!empty($data['id']) && !empty($data['value'])) {
                $page['load_tpl']['forwardconnbrief'][$data['id']] = $data['value'];
            }
        }
    }
    //pr_dbg($page);

    return $page;
}
