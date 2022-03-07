<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

function dashboard_init(Web $web)
{   
    //$web->addPage(['name' => 'index', 'module' => 'dashboard']);
    //$web->langProvider()
}

function dashboard_upgrade($from)
{
}
function dashboard_install()
{
}

function dashboard_uninstall()
{
}


function page_dashboard_index() {
    return page_dashboard_dashboard();
}

function page_dashboard_dashboard() {

}