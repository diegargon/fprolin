<?php

function netinterfaces_init(Web $web)
{
    $plugin_name = 'netinterfaces';

    $cfg = $web->getConfig();
    //$sm = $web->getProvider('SessionManager');
    require_once('lang/' . $cfg['lang'] . '/' . $plugin_name . '.lang.php');
    $lng = $web->setLang($lng);


    $web->setPage(['interfaces' => ['name' => 'interfaces', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_interfaces']]);
    $web->setPage(['interface' => ['name' => 'interface', 'plugin_name' => $plugin_name, 'func_name' => $plugin_name . '_page_interface']]);

    $side_elements['network'] = ['href' => 'javascript:void(0)', 'caption' => $lng['L_NETWORK']];
    $side_elements['network']['submenu']['interfaces'] = ['href' => '/?page=interfaces', 'caption' => $lng['L_INTERFACES']];
    $menu['load_tpl']['fprolin']['sidebar'] = $side_elements;
    $web->addWebData($menu);

    //    $web->setRetrieve('interfaces', 'interfaces_state.py', '', 'static');
    //    $web->setRetrieve('interfaces', 'interfaces_config.py', '', 'static');
    $web->setRetrieve('interfaces', 'interfaces_basic_info.py', '', 'static');
}

function netinterfaces_page_interfaces($web)
{
    $plugin_name = 'netinterfaces';

    $cfg = $web->getConfig();
    $page = $web->getWebData();
    $page['page'] = 'interfaces';
    $interfaces_data =  $web->retrieve($page['page'], 'static');
    $page['css'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];

    $page['load_tpl']['interfaces'] = [
        'plugin' => $plugin_name,
        'tpl' => 'interfaces',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    $page['load_tpl']['interfaces']['img_path'] = '/plugins/' . $plugin_name . '/tpl/' . $cfg['theme']  . '/img';

    if (isset($interfaces_data['data']) && is_array($interfaces_data['data'])) {
        $interfaces_data = $interfaces_data['data'];
        //pr_dbg($page_data);

        foreach ($interfaces_data ?? [] as $data) {
            if ($data['id'] == 'interfaces_state') {
                $interfaces_state = $data['value'][0];
            } else if ($data['id'] == 'interfaces_config') {
                $interfaces_config = $data['value'][0];
            } else if ($data['id'] == 'interfaces_info') {
                $interfaces_info = $data['value'][0];
            }
            //$page['load_tpl']['interfaces']['ifaces'] = $vdata;
        }
        
        
        if (isset($interfaces_info)) {
            if (isset($interfaces_info['lo'])) {
                unset($interfaces_info['lo']);
            }
            usort($interfaces_info, function($a, $b) {
                return $a['devtype'] <=> $b['devtype'];
            });            
            $page['load_tpl']['interfaces']['ifaces'] = $interfaces_info;
        }
        //pr_dbg($page['load_tpl']['interfaces']['ifaces']);

    }
    //pr_dbg($page);
    return $page;
}


function netinterfaces_page_interface($web)
{
    $plugin_name = 'netinterfaces';

    $cfg = $web->getConfig();
    $page = $web->getWebData();    
    $page['page'] = 'view_interface';

    $interfaces_data =  $web->retrieve($page['page'], 'static');

    $page['css'][] = ['name' => $plugin_name, 'plugin' => $plugin_name];

    $page['load_tpl']['view_interfaces']['img_path'] = '/plugins/' . $plugin_name . '/tpl/' . $cfg['theme']  . '/img';

    $page['load_tpl']['view_interfaces'] = [
        'plugin' => $plugin_name,
        'tpl' => 'view_interface',
        'tpl_father' => 'fprolin',
        'tpl_spot' => 'main'
    ];

    return $page;
}
