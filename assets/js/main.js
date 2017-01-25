$(document).ready(function() {
    $('#instances-menu a.instanceLink').click(function(e) {
        e.preventDefault();
        $(this).parent().find('ul').slideToggle();
    });

    $('#left-panel a.require-instance-id').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        document.location = url +'/'+ ES.getActiveInstanceId();
    });

    $('#minify-menu').click(function() {
        $(this).find('i').toggleClass('fa-arrow-circle-right').toggleClass('fa-arrow-circle-left');
        $('body').toggleClass('minified-menu');
    });

    $('#mobile-menu-icon').click(function() {
        $(this).toggleClass('active');
        $('#left-panel').toggleClass('mobile-closed');
    });

    var WidgetList= {
        'widget-form': ES.WidgetForm,
        'widget-todos': ES.WidgetTodos,
        'widget-waterTests': ES.WidgetWaterTests,
        'widget-instances': ES.WidgetInstances,
        'widget-params': ES.WidgetInstanceParams,
        'widget-history': ES.WidgetHistory,
        'widget-livedata': ES.WidgetLive,
        'widget-logs': ES.WidgetLogs
    };

    $(".widget").each(function() {
        var widget = WidgetList[$(this).attr('id')];
        if( widget ) {
            ES.factory(WidgetList[$(this).attr('id')]);
        }
    });
});