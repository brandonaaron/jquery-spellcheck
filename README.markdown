# jQuery Spellcheck Plugin

A jQuery plugin that adds spellcheck support to inputs. It uses Google's spell checking API and requires a server to handle the communication with the API. An example php implementation is provided.

It has several configurable options.
 
* `lang`: the 2 letter language code, defaults to en for english
* `events`: a space separated string of events to use, default is 'keypress blur paste'
* `autocheck`: number of milliseconds to check spelling after a key event, default is 750.
* `url`: url of the spellcheck service on your server, default is spellcheck.php
* `ignorecaps`: 1 to ignore words with all caps, 0 to check them
* `ignoredigits`: 1 to ignore digits, 0 to check them

If there are spelling errors it outputs them to a div with the ID "spellcheckresults" appended to the body and positioned directly under the input. Within this div it creates a definition list (&lt;dl&gt;) with the misspelled word as the title (&lt;dt&gt;) and each suggestion as the definition (&lt;dd&gt;).

## License

The spellcheck plugin is licensed under the MIT License (LICENSE.txt).

Copyright (c) 2010 [Brandon Aaron](http://brandonaaron.net)