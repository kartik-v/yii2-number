/*!
 * @package yii2-number
 * v1.0.6
 *
 * Krajee number control jQuery plugin
 *
 * http://demos.krajee.com/number
 *
 * Author: Kartik Visweswaran
 * Copyright: 2018 - 2019, Kartik Visweswaran, Krajee.com
 */
(function (factory) {
    "use strict";
    //noinspection JSUnresolvedVariable
    if (typeof define === 'function' && define.amd) { // jshint ignore:line
        // AMD. Register as an anonymous module.
        define(['jquery'], factory); // jshint ignore:line
    } else { // noinspection JSUnresolvedVariable
        if (typeof module === 'object' && module.exports) { // jshint ignore:line
            // Node/CommonJS
            // noinspection JSUnresolvedVariable
            module.exports = factory(require('jquery')); // jshint ignore:line
        } else {
            // Browser globals
            factory(window.jQuery);
        }
    }
}(function ($) {
    "use strict";

    var NumberControl = function (element, options) {
        var self = this;
        self.$elSave = $(element);
        self.$elDisp = $('#' + options.displayId);
        self.options = options;
        self.init();
    };

    NumberControl.prototype = {
        constructor: NumberControl,
        init: function () {
            var self = this, $elDisp = self.$elDisp, $elSave = self.$elSave, opts = self.options.maskedInputOptions,
                NS = '.numberControl', events = ['change', 'blur', 'keypress', 'keydown'].join(NS + ' ') + NS,
                originalValue = $elDisp.inputmask('unmaskedvalue'), radixPre = opts.radixPoint || '.';
            if (radixPre !== '.') {
                originalValue = (originalValue + '').replace('.', radixPre);
            }
            $elDisp.val(originalValue);
            $elDisp.off(NS).on(events, function (e) {
                var event = e.type, key = e.keyCode || e.which, enterKeyPressed = key && parseInt(key) === 13;
                if (event === 'keypress' && !enterKeyPressed) {
                    return;
                }
                if (event !== 'keydown' || enterKeyPressed) {
                    var num = $elDisp.inputmask('unmaskedvalue'), radix = opts.radixPoint || '.';
                    if (radix !== '.') {
                        num = (num + '').replace(radix, '.');
                    }
                    $elSave.val(num).trigger('change');
                }
            }).inputmask(opts);
        },
        destroy: function () {
            var self = this, $elDisp = self.$elDisp, $elSave = self.$elSave;
            $elDisp.off('.numberControl').removeData('inputmask');
            $elSave.removeData('numberControl');
        }
    };

    $.fn.numberControl = function (option) {
        var args = Array.apply(null, arguments), retvals = [];
        args.shift();
        this.each(function () {
            var self = $(this), opts, data = self.data('numberControl'), options = typeof option === 'object' && option;
            if (!data) {
                opts = $.extend(true, {}, $.fn.numberControl.defaults, options, self.data());
                data = new NumberControl(this, opts);
                self.data('numberControl', data);
            }

            if (typeof option === 'string') {
                retvals.push(data[option].apply(data, args));
            }
        });
        switch (retvals.length) {
            case 0:
                return this;
            case 1:
                return retvals[0];
            default:
                return retvals;
        }
    };

    $.fn.numberControl.defaults = {
        displayId: '',
        maskedInputOptions: {}
    };
}));