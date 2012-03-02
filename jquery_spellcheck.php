<?php
define("SPELLCHECKER_URL", "http://" . $_SERVER['SERVER_NAME'] . "/spellcheck/spellcheck.php");
define("SPELLCHECKER_CHECKER", "pspell");
define("SPELLCHECKER_EVENTS", "blur");

echo "\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"/spellcheck/spellcheck.css\" />\n";
echo "<script src=\"/spellcheck/jquery.spellcheck.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
echo "<script type=\"text/javascript\" charset=\"utf-8\">
    jQuery(document).ready(function(jQuery) {
        jQuery('textarea').each(
            function() {
                var self = this;
                if(jQuery(self).hasClass('spellcheck')) {
                    jQuery(self).spellcheck({ events: '" . SPELLCHECKER_EVENTS . "', url: '" . SPELLCHECKER_URL . "', checker: '" . SPELLCHECKER_CHECKER . "', ignorecaps: 0 });
                }
            }
        );
        jQuery('input[type=text]').each(
            function() {
                var self = this;
                if(jQuery(self).hasClass('spellcheck')) {
                    jQuery(self).spellcheck({ events: '" . SPELLCHECKER_EVENTS . "', url: '" . SPELLCHECKER_URL . "', checker: '" . SPELLCHECKER_CHECKER . "', ignorecaps: 0 });
                }
            }
        );
    });
</script>";
echo "\n";
?>