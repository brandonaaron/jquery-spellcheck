/*! Copyright (c) 2010 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Version 0.2.1
 * Edited and enhanced by Chris 'CJ' Jones (29-02-2012)
 */
(function(jQuery){

/*
 * Creates an instance of a SpellChecker for each matched element.
 * The SpellChecker has several configurable options.
 *  - lang: the 2 letter language code, defaults to en for english
 *  - events: a space separated string of events to use, default is 'keypress blur paste'
 *  - autocheck: number of milliseconds to check spelling after a key event, default is 750.
 *  - url: url of the spellcheck service on your server, default is spellcheck.php
 *  - checker: the type of spell checker to use - 'google_toolbar' (default); 'pspell'; 'google_soap_spi' (deprecated).
 *  - ignorecaps: 1 to ignore words with all caps, 0 to check them
 *  - ignoredigits: 1 to ignore digits, 0 to check them
 */
jQuery.fn.spellcheck = function(options) {
    return this.each(function() {
        var jQuerythis = jQuery(this);
        if ( !jQuerythis.is('[type=password]') && !jQuery(this).data('spellchecker') )
            jQuery(this).data('spellchecker', new jQuery.SpellChecker(this, options));
    });
};

/**
 * Forces a spell check on an element that has an instance of SpellChecker.
 */
jQuery.fn.checkspelling = function() {
    return this.each(function() {
        var spellchecker = jQuery(this).data('spellchecker');
        spellchecker && spellchecker.checkSpelling();
    });
};


jQuery.SpellChecker = function(element, options) {
    this.jQueryelement = jQuery(element);
    this.options = jQuery.extend({
        lang: 'en',
        autocheck: 750,
        events: 'keypress blur paste',
        url: 'spellcheck.php',
        //Chris 'CJ' Jones added this bit to tell which spell checker you want to use. (29-02-2012)
        checker: 'google_toolbar',
        ignorecaps: 1,
        ignoredigits: 1
    }, options);
    this.bindEvents();
};

jQuery.SpellChecker.prototype = {
    bindEvents: function() {
        if ( !this.options.events ) return;
        var self = this, timeout;
        this.jQueryelement.bind(this.options.events, function(event) {
            if ( /^key[press|up|down]/.test(event.type) ) {
                if ( timeout ) clearTimeout(timeout);
                timeout = setTimeout(function() { self.checkSpelling(); }, self.options.autocheck);
            } else
                self.checkSpelling(); 
        });
    },
    
    checkSpelling: function() {
        var prevText = this.text, text = this.jQueryelement.val(), self = this;
        if ( prevText === text ) return;
        this.text = this.jQueryelement.val();
        jQuery.get(this.options.url, {
            checker: this.options.checker,
            text: this.text, 
            lang: this.options.lang,
            ignorecaps: this.options.ignorecaps,
            ignoredigits: this.options.ignoredigits
        }, function(r) { self.parseResults(r); });
    },
    
    parseResults: function(results) {
        var self = this;
        //alert(self.options.checker);
        this.results = [];
        jQuery(results).find('c').each(function() {
            var jQuerythis = jQuery(this),
                offset = jQuerythis.attr('o'),
                length = jQuerythis.attr('l');
            self.results.push({
                //Chris 'CJ' Jones added these two lines to send the offset and length as well. (29-02-2012)
                offset: offset,
                length: length,
                word: self.text.substr(offset, length),
                suggestions: jQuerythis.text().split(/\s/)
            });
        });
        this.displayResults();
    },
    
    displayResults: function() {
        jQuery('#spellcheckresults').remove();
        if ( !this.results.length ) return;
        var jQuerycontainer = jQuery('<div id="spellcheckresults"></div>').appendTo('body'),
            dl = [], self = this, offset = this.jQueryelement.offset(), height = this.jQueryelement[0].offsetHeight, i, k;
        for ( i=0; i<this.results.length; i++ ) {
            var result = this.results[i], suggestions = result.suggestions;
            dl.push('<dl><dt>'+result.word+'</dt>');
            for ( k=0; k<suggestions.length; k++ )
                dl.push('<dd offset="' + result.offset + '" length="' + result.length +'">'+suggestions[k]+'</dd>');
            dl.push('<dd class="ignore" offset="' + result.offset + '" length="' + result.length +'">ignore</dd></dl>');
        }
        jQuerycontainer.append(dl.join('')).find('dd').bind('click', function(event) {
            var jQuerythis = jQuery(this), jQueryparent = jQuerythis.parent();
            //Chris 'CJ' Jones changed this if statment to account for multiple instances of the same miss spelt word. (29-02-2012)
            if ( !jQuerythis.is('.ignore') ) {
                var before = self.jQueryelement.val().substring(0, jQuerythis.attr('offset'));
                var string = self.jQueryelement.val().substring(jQuerythis.attr('offset'), self.jQueryelement.val().length);
                string = string.replace( jQueryparent.find('dt').text(), jQuerythis.text() );
                var replaces = before + string;
                self.jQueryelement.val( self.jQueryelement.val().replace( self.jQueryelement.val(), replaces ) );
            }
            jQueryparent.remove();
            if ( jQuery('#spellcheckresults').is(':empty') )
                jQuery('#spellcheckresults').remove();
            this.blur();
        }).end().css({ top: offset.top + height, left: offset.left });
    }
    
};

})(jQuery);