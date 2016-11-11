/**
 * Created by eoto on 08/11/16.
 */
var ES = {
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
    }
};