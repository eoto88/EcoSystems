/**
 * Created by eoto on 31/10/14.
 */
var Widget = Class.extend({
    init: function(cssId) {
        this.cssId = cssId;
    }
});



var WidgetLogs = Widget.extend({
    init: function() {
        this._super( 'widget-logs' );
    }
});

var WidgetLive = Widget.extend({
    init: function() {
        this._super( 'widget-live' );
    }
});