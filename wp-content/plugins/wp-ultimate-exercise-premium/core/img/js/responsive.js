// Based on https://github.com/kumailht/responsive-elements

var WPUEP_Responsive = {
    elementsSelector: '.wpuep-container',
    maxRefreshRate: 5,
    init: function() {
        var self = this;
        jQuery(function() {
            self.el = {
                window: jQuery(window),
                responsive_elements: jQuery(self.elementsSelector)
            };

            self.events();
        });
    },
    checkBreakpointOfAllElements: function() {
        var self = WPUEP_Responsive;
        self.el.responsive_elements.each(function(i, _el) {
            self.checkBreakpointOfElement(jQuery(_el));
        });
    },
    checkBreakpointOfElement: function(_el) {
        if(_el.width() < wpuep_responsive_data.breakpoint) {
            _el.find('.wpuep-responsive-mobile').css('display','block');
            _el.find('.wpuep-responsive-desktop').css('display','none');
        } else {
            _el.find('.wpuep-responsive-mobile').css('display','none');
            _el.find('.wpuep-responsive-desktop').css('display','block');
        }
    },
    events: function() {
        this.checkBreakpointOfAllElements();

        this.el.window.bind('resize', this.debounce(
            this.checkBreakpointOfAllElements, this.maxRefreshRate));
    },
    // Debounce is part of Underscore.js 1.5.2 http://underscorejs.org
    // (c) 2009-2013 Jeremy Ashkenas. Distributed under the MIT license.
    debounce: function(func, wait, immediate) {
        // Returns a function, that, as long as it continues to be invoked,
        // will not be triggered. The function will be called after it stops
        // being called for N milliseconds. If `immediate` is passed,
        // trigger the function on the leading edge, instead of the trailing.
        var result;
        var timeout = null;
        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) result = func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) result = func.apply(context, args);
            return result;
        };
    }
};

WPUEP_Responsive.init();