<?php
/**
 *
 *
 * Author:      Chris 'CJ' Jones
 * Project:     PSpell jQuery Spellcheck
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
?>