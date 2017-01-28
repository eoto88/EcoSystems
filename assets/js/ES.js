/**
 * Created by eoto on 08/11/16.
 */
var ES = {

    cmpCount: 0,
    cmpTypes: [],
    createdCmps: [],

    dateFormat: 'YYYY-MM-DD',
    datetimeFormat: 'YYYY-MM-DD h:mm:ss',

    register: function(classCmp) {
        var cmpType = classCmp.substring(classCmp.lastIndexOf('.') + 1).toLowerCase();

        this.cmpTypes[cmpType] = classCmp;
    },

    create: function(cmpType) {
        var me = this,
            classCmp = this.cmpTypes[cmpType];

        if(classCmp) {
            var cmp = null,
                arrClsCmp = classCmp.split(".");

            if(arrClsCmp.length == 2) {
                classCmp = window['ES'][arrClsCmp[1]];
            } else  if(arrClsCmp.length == 3) {
                classCmp = window['ES'][arrClsCmp[1]][arrClsCmp[2]];
            }
            if(ES.isFunction(classCmp)) {
                cmp = new classCmp();
            }
            if(cmp == null) {
                console.warn("Unknown cmpType: "+ cmpType);
            }
            return cmp;
        } else {
            console.warn("Unknown cmpType: "+ cmpType);
        }
    },

    getCmp: function(cmpId) {
        return this.createdCmps[cmpId];
    },

    factory: function (class_) {
        return new class_();
    },

    apply: function(object, config) {
        if (object && config && typeof config === 'object') {
            var i;

            for (i in config) {
                object[i] = config[i];
            }
        }

        return object;
    },

    parseData: function(data) {
        try {
            return JSON.parse(data);
        } catch(err) {
            console.error("Error parsing data JSON:" + data);
        }
    },

    scrollToMe: function(cmp) {
        var me = this,
            $cmp = me.getJQueryObject(cmp);
        $('html, body').animate({
            scrollTop: ($cmp.offset().top - 130) // 130 = header
        }, 1500);
    },

    /**
     *
     * @param {Object} params
     * @param {string} params.url
     * @param {string} [params.method]
     * @param {Object} [params.data]
     * @param {Function} params.success
     * @param {Function} [params.failure]
     */
    ajax: function(params) {
        if( ! params.method ) {
            params.method = 'GET';
        }
        // TODO Default values for success and fail methods
        return $.ajax({
            url: params.url,
            type: params.method,
            contentType: 'application/json',
            data: JSON.stringify(params.data),
            success: params.success,
            dataType: 'json'
        }).fail(function(data) {
            if( params.failure ) {
                params.failure(data);
            }
        });
    },

    /**
     * return active instance's id or null
     *
     * @returns {null|Number}
     */
    getActiveInstanceId: function() {
        var pathname = window.location.pathname;
        if(/^\/instance/.test(pathname)) {
            return pathname.replace('/instance/', '');
        } else if(/^\/live/.test(pathname)) {
            return pathname.replace('/live/', '');
        }
        return null;
    },

    capitalizeFirstLetter: function(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },

    getNextCmpId: function() {
        this.cmpCount++;
        return this.cmpCount;
    },

    isDashboard: function() {
        return window.location.pathname == '/';
    },

    /**
     * Returns `true` if the passed value is a JavaScript Function, `false` otherwise.
     * @param {Object} value The value to test.
     * @return {Boolean}
     * @method
     */
    isFunction:
    // Safari 3.x and 4.x returns 'function' for typeof <NodeList>, hence we need to fall back to using
    // Object.prototype.toString (slower)
        (typeof document !== 'undefined' && typeof document.getElementsByTagName('body') === 'function') ? function(value) {
            return !!value && toString.call(value) === '[object Function]';
        } : function(value) {
            return !!value && typeof value === 'function';
        },

    isInt: function(n){
        return Number(n) === n && n % 1 === 0;
    },

    isFloat: function(n){
        return Number(n) === n && n % 1 !== 0;
    },

    getJQueryObject: function(obj) {
        var me = this;
        if(me.isJQueryObject(obj)) {
            return obj;
        } else {
            return $(obj);
        }
    },

    isJQueryObject: function(obj) {
        return (obj instanceof jQuery);
    },

    isEmpty: function(object) {
        return (object === undefined || object === null || object === '');
    }
};

/*  http://ejohn.org/blog/simple-javascript-inheritance/  */

/* Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 */
// Inspired by base2 and Prototype
(function(){
    var initializing = false, fnTest = /xyz/.test(function(){xyz;}) ? /\b_super\b/ : /.*/;

    // The base Class implementation (does nothing)
    this.Class = function(){};

    // Create a new Class that inherits from this class
    Class.extend = function(prop, className) {
        var _super = this.prototype;

        if(className) {
            ES.register(className);
        }

        // Instantiate a base class (but only create the instance,
        // don't run the init constructor)
        initializing = true;
        var prototype = new this();
        initializing = false;

        // Copy the properties over onto the new prototype
        for (var name in prop) {
            // Check if we're overwriting an existing function
            prototype[name] = typeof prop[name] == "function" &&
            typeof _super[name] == "function" && fnTest.test(prop[name]) ?
                (function(name, fn){
                    return function() {
                        var tmp = this._super;

                        // Add a new ._super() method that is the same method
                        // but on the super-class
                        this._super = _super[name];

                        // The method only need to be bound temporarily, so we
                        // remove it when we're done executing
                        var ret = fn.apply(this, arguments);
                        this._super = tmp;

                        return ret;
                    };
                })(name, prop[name]) :
                prop[name];
        }

        // The dummy class constructor
        function Class() {
            // All construction is actually done in the init method
            if ( !initializing && this.init )
                this.init.apply(this, arguments);
        }

        // Populate our constructed prototype object
        Class.prototype = prototype;

        // Enforce the constructor to be what we expect
        Class.prototype.constructor = Class;

        // And make this class extendable
        Class.extend = arguments.callee;

        return Class;
    };
})();