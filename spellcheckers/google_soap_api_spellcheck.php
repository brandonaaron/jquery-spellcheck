<?php
#########################################
## This has been deprecated by google. ##
#########################################
/**
 *
 *
 * Author:      Chris 'CJ' Jones
 * Project:     Google Search API jQuery Spellcheck
 * Date:        Wed Feb 29 11:15:30 2012
 * Version:     1
 */

header("Content-Type: text/xml; charset=utf-8");
$lang = $_GET['lang'];
$text = str_replace("\n", " ", urldecode($_GET['text']));
$configs['GoogleSearchAPI']['key'] = "";
require_once("GoogleSearchService.php");
$GoogleSearchService = new GoogleSearchService();
$GoogleSearchService->doSpellingSuggestion($configs['GoogleSearchAPI']['key'], $text)
?>