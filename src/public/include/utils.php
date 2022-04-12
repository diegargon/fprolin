<?php
/*

*/

function pr_field($data, $field)
{
    if (is_array($data) && !empty($data[$field])) {
        return $data[$field];
    }

    return null;
}
function pr_dbg($data)
{
    echo '<hr/>';
    echo '<pre style="padding-left:150px">';
    echo debug_backtrace()[1]['function'] . '<br>';
    print_r($data);
    echo '</pre><hr/>';
}
