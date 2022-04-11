<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

function SSManager_init(Web $web)
{   
    require_once('include/SessionManager.php');

    $cfg = $web->getConfig();
    $db = $web->getProvider('Database');
    $sm = new SessionManager($cfg, $db);
    
    $provider['SessionManager'] =  ['value' => $sm, 'version' => '1.0'];
    $web->setProvider($provider);
 }

