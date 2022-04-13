<?php

function netconn_init(Web $web)
{
    $cfg = $web->getConfig();
    //$sm = $web->getProvider('SessionManager');
    $lng = $web->getLang();
    require_once('lang/' . $cfg['lang'] . '/netconn.lang.php');
    $lng = $web->setLang($_lng);
    //pr_dbg($_lng);
    //pr_dbg($lng);
    //$l = array_merge($_lng, $lng);
    
    //
    $plugin_name = 'netconn';

    $web->setPage(['localconn' => ['name' => 'localconn', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_localconn']]);

    $side_elements['network'] = ['href' => 'javascript:void(0)', 'caption' => $lng['L_NETWORK']];
    $side_elements['network']['submenu']['system'] = ['href' => '/?page=localconn', 'caption' => $lng['L_LOCALCONN']];
    $menu['load_tpl']['fprolin']['sidebar'] = $side_elements;
    $web->addWebData($menu);    
    
    $web->setRetrieve('localconn', 'network_conn.py', '', 'all');
    
}

function netconn_page_localconn($web) {
    $plugin_name = 'netconn';

    $page['page'] = 'localconn';
    $page_data =  $web->retrieve($page['page'], 'static');
    $page_data = $page_data['data'];
    //pr_dbg($page_data);

    $page = $web->getWebData();
    $page['page'] = 'localconn';

    $page['load_tpl']['localconn'] = [
        'plugin' => $plugin_name,
        'tpl' => 'localconn',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    foreach ($page_data as $data) {
        //pr_dbg($data);
        if (!empty($data['id']) && !empty($data['value'])) {
            $page['load_tpl']['localconn'][$data['id']] = $data['value'];
        }
    }

    //pr_dbg($page);

    return $page;    
}
?>
