<?php

class WOS
{
    private Web $web;

    function __construct(Web $web)
    {
        $this->web = $web;
    }

    function sendCMD(array $cmds)
    {
        $response = [];

        $cfg = $this->web->getConfig();

        $ret = '';
        $sock = @stream_socket_client('unix://' . $cfg['wos_socket'], $errno, $errstr);

        if (!$sock) {
            $response = [
                'result' => 'fail',
                'module' => 'wos',
                'error' => 'socket',
                'error_msg' => ['errno' => $errno, 'errstr' => $errstr],
            ];
            return $response;
        }

        $request['request'] = $cmds;

        $json_request = json_encode($request);
        fwrite($sock, $json_request);
        fwrite($sock, "\r\n\r\n");
        $response = '';
        $i = 1;
        $iMax = 50;
        while (true) {
            $_r = fread($sock, 4096);
            if (strpos($_r, "\r\n\r\n")) {
                $response .= str_replace("\r\n\r\n", '', $_r);
                break;
            }
            $response .= $_r;
        }
        fclose($sock);

        //pr_dbg($response);
        //Receive json  decode to array
        $response = json_decode($response, true);
        //pr_dbg($response);

        return $response;
    }
}
