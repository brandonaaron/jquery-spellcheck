<?php
header("Content-Type: text/xml; charset=utf-8");
$url="https://www.google.com/tbproxy/spell?lang=" . $_GET['lang'];
$text = urldecode($_GET['text']);

$body = '<?xml version="1.0" encoding="utf-8" ?>';
$body .= '<spellrequest textalreadyclipped="0" ignoredups="1" ignoredigits="' . $_GET['ignoredigits'] . '" ignoreallcaps="' . $_GET['ignorecaps'] . '">';
$body .= '<text>' . $text . '</text>';
$body .= '</spellrequest>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$contents = curl_exec ($ch);
curl_close ($ch); 

print $contents;
?>
