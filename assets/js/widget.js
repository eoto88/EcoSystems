/**
 * Created by eoto on 31/10/14.
 */
App.Widget = Class.extend({
    init: function(cssId) {
        this.cssId = cssId;

        var $component = $('#' + this.cssId);
        $component.find('.widget-expand').click(function() {
            $component.find('.widget-body').slideToggle();
            $(this).find('i').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
        });
    }
});

$(document).ready(function() {
    $(".widget").each(function() {
        var widget = WidgetList[$(this).attr('id')];
        if( widget ) {
            App.factory(WidgetList[$(this).attr('id')]);
        }
    });
});




/*App.WidgetLogs = App.Widget.extend({
    init: function() {
        this._super( 'widget-logs' );
    }
});

App.WidgetLive = App.Widget.extend({
    init: function() {
        this._super( 'widget-live' );
    }
});*/