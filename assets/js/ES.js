/**
 * Created by eoto on 08/11/16.
 */
var ES = {

    cmpCount: 0,
    cmpTypes: [],

    register: function(classCmp) {
        var cmpType = classCmp.substring(classCmp.lastIndexOf('.') + 1).toLowerCase();

        this.cmpTypes[cmpType] = classCmp;
    },

    create: function(cmpType) {
        var classCmp = this.cmpTypes[cmpType];

        if(classCmp) {
            var arrClsCmp = classCmp.split(".");

            if(arrClsCmp.length == 2) {
                classCmp = window['ES'][arrClsCmp[1]];
                return ES.isFunction(classCmp) ? new classCmp() : null;
            } else  if(arrClsCmp.length == 3) {
                classCmp = window['ES'][arrClsCmp[1]][arrClsCmp[2]];
                return ES.isFunction(classCmp) ? new classCmp() : null;
            } else {
                return null;
            }
        } else {
            console.warn("Unknown cmpType: "+ cmpType);
        }
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

    /**
     *
     * @param {Object} params
     * @param {string} params.url
     * @param {string} [params.method]
     * @param {Object} params.data
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
     * @deprecated
     * @returns {undefined|Number}
     */
    getActiveInstanceId: function() {
        var pathname = window.location.pathname;
        if(/^\/instance/.test(pathname)) {
            return pathname.replace('/instance/', '');
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