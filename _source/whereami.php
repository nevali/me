<?php

require_once(dirname(__FILE__) . '/../_lib/common.php');
require_once(dirname(__FILE__) . '/../_lib/finger.php');

$page->canonical = '/whereami';
$page->title = 'Where am I?';

$replacements = array(
	' -> ' => ' → ',
);

try
{
    $buf = finger(FINGER_USER);
}
catch(Exception $e)
{
    $buf = $e->message;
}

$search = array_keys($replacements);
$replace = array_values($replacements);
$buf = str_replace($search, $replace, $buf);
$htmlbuf = htmlspecialchars($buf, ENT_QUOTES, 'UTF-8');
$htmlbuf = preg_replace('!(https?://[^> \t\r\n]+)!i', '<a href="$1">$1</a>', $htmlbuf);

$page->html = $htmlbuf;

emit('whereami');
