<?php
/*

*/

function pr_af($data, $field) {
    if(!is_array($data) && !empty($data[$field])) {
        return $data[$field];
    }

    return null;
}
function pr($data)
{
    echo '<hr/><pre>';
    print_r($data); 
    echo '</pre><hr/>';
}
