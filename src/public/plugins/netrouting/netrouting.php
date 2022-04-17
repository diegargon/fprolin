<?php

function netrouting_init(Web $web)
{
    $plugin_name = 'netrouting';

    $cfg = $web->getConfig();
    //$sm = $web->getProvider('SessionManager');
    require_once('lang/' . $cfg['lang'] . '/'. $plugin_name .'.lang.php');
    $lng = $web->setLang($lng);


    $web->setPage(['static_routes' => ['name' => 'static_routes', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_static_routes']]);

    $side_elements['network'] = ['href' => 'javascript:void(0)', 'caption' => $lng['L_NETWORK']];
    $side_elements['network']['submenu']['static_routes'] = ['href' => '/?page=static_routes', 'caption' => $lng['L_STATIC_ROUTES']];
    $menu['load_tpl']['fprolin']['sidebar'] = $side_elements;
    $web->addWebData($menu);

    $web->setRetrieve('static_routes', 'static_routes.py', '', 'all');
    /*
    $web->setRetrieve('localconn', 'network_conn.py', '', 'all');
    $web->setRetrieve('forwardconn', 'network_fconn.py', '', 'all');
    $web->setRetrieve('forwardconnbrief', 'network_fconn.py', '', 'all');
    */
}

function netrouting_page_static_routes($web){
    $plugin_name = 'netrouting';

    $page = $web->getWebData();
    $page['page'] = 'static_routes';
    $page_data =  $web->retrieve($page['page'], 'static');
    $page['css'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];

    $page['load_tpl']['netrouting'] = [
        'plugin' => $plugin_name,
        'tpl' => 'static_routes',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    if (isset($page_data['data']) && is_array($page_data['data'])) {
        $page_data = $page_data['data'];
        //pr_dbg($page_data);

        foreach ($page_data as $data) {
            //pr_dbg($data);
            if (!empty($data['id']) && !empty($data['value'])) {
                $page['load_tpl']['netrouting'][$data['id']] = $data['value'];
            }
        }
    }
    //pr_dbg($page);

    return $page;
}
