<?php
/**
 *
 *
 * Author:      Brandon Aaron
 * Project:     Google Toolbar jQuery Spellcheck
 * Date:        2010
 * Edited Date: Wed Feb 29 11:15:30 2012
 * Edited By:   Chris 'CJ' Jones
 * Version:     1
 */

header("Content-Type: text/xml; charset=utf-8");
$lang = $_GET['lang'];
$text = str_replace("\n", " ", urldecode($_GET['text']));
$url="https://www.google.com/tbproxy/spell?lang=" . $lang;
$body = '<?xml version="1.0" encoding="utf-8" ?>';
$body .= '<spellrequest textalreadyclipped="0" ignoredups="1" ignoredigits="' . $_GET['ignoredigits'] . '" ignoreallcaps="' . $_GET['ignorecaps'] . '">';
$body .= '<text>' . $text . '</text>';
$body .= '</spellrequest>';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$contents = curl_exec($ch);
curl_close($ch);
//echo "<!--Google Toolbar Spell Check-->\n";
echo $contents;
?>