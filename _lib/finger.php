<?php
    
function finger($user, $host = FINGER_HOST, $port = FINGER_PORT)
{
    $err = '';
    $sock = fsockopen(FINGER_HOST, FINGER_PORT, $errno, $errstr, $timeout = FINGER_TIMEOUT);
    if(is_resource($sock))
    {
    	fwrite($sock, FINGER_USER . "\n");
    	$buf = stream_get_contents($sock);
    	fclose($sock);
    }
    else
    {
        throw new Exception($errstr);
    }
    return $buf;
}
