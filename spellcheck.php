<?php
/**
 *
 *
 * Author:      Chris 'CJ' Jones
 * Project:     jQuery Spellcheck
 * Date:        Wed Feb 29 11:15:30 2012
 * Version:     1
 */

function strposa($haystack, $needles = array(), $offset = 0) {
    $chr = array();
    foreach($needles as $needle)
    {
            $res = strpos($haystack, $needle, $offset);
            if ($res !== false) $chr[$needle] = $res;
    }
    if(empty($chr)) return false;
    return min($chr);
}

switch($_REQUEST['checker']) {
    case "pspell":
        /**
         *
         *
         * Author:      Chris 'CJ' Jones
         * Project:     PSpell jQuery Spellcheck
         * Date:        Wed Feb 29 11:15:30 2012
         * Version:     1
         */
        
        header("Content-Type: text/xml; charset=utf-8");
        $lang = $_GET['lang'];
        $text = str_replace("\n", " ", urldecode($_GET['text']));
        $lastpos = 0;
        $total_length = strlen($text);
        $words = explode(" ", $text);
        $pspell_link = pspell_new($lang);
        $out = "<!--PSpell Spell Check-->\n<spellresult error=\"0\" clipped=\"0\" charschecked=\"{$total_length}\">\n";
        foreach($words as $word) {
            if(
               ($_GET['ignorecaps'] == 1) &&
               ($word == strtoupper($word))
              ) {
                //Do nothing.
            } elseif(
                     ($_GET['ignoredigits'] == 1) &&
                     (strposa($word, array("0","1","2","3","4","5","6","7","8","9")) !== false)
                    ) {
                //Do nothing.
            } else {
                if (!pspell_check($pspell_link, $word)) {
                    $suggestions = pspell_suggest($pspell_link, $word);
                    $word_length = strlen($word);
                    $pos = strpos($text, $word, $lastpos);
                    $lastpos = $pos + $word_length;
                    $out .= "\t<c o=\"{$pos}\" l=\"{$word_length}\" s=\"1\">";
                    $word_list = "";
                    foreach($suggestions as $suggestion) {
                        $word_list .= $suggestion . " ";
                    }
                    $out .= trim($word_list) . "</c>\n";
                }
            }
        }
        $out .= "</spellresult>";
        echo $out;
        break;
    case "google_soap_api":
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
        $GoogleSearchService->doSpellingSuggestion($configs['GoogleSearchAPI']['key'], $text);
        break;
    case "google_toolbar":
    default:
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
}
?>