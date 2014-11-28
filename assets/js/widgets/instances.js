var WidgetInstances = Widget.extend({
    init: function() {
        this._super( 'widget-instances' );
    },
    refreshStatuses: function() {
        var me = this;
        $.ajax({
            url: BASE_URL + "ajax/getLiveData",
            cache: false,
            dataType: "json"
        }).done(function(data) {
            $.each(data, function(key, instance) {
                var $instanceRow = $("#widget-instances li[data-id='" + instance.idInstance + "']");

                var status;
                if(instance.stillAliveStatus == "still-alive")
                    status = '<i class="fa fa-check success"></i>';
                else
                    status = '<i class="fa fa-exclamation-triangle error"></i>';
                $instanceRow.find("#still_alive").html(status).attr("title", translations.lastCommunication + ": " + instance.lastCommunication);

                me.changeStatus($instanceRow, 'pump', 'Pump', instance.pumpStatus);
                me.changeStatus($instanceRow, 'light', 'Light', instance.lightStatus);
                me.changeStatus($instanceRow, 'fan', 'Fan', instance.fanStatus);
                me.changeStatus($instanceRow, 'heater', 'Heater', instance.heaterStatus);
            });
        });
    },
    changeStatus: function($instanceRow, relayId, relayName, status) {
        $("#"+ relayId +"_status").attr('title', relayName +' is '+ status);
        if(status === "on") {
            $instanceRow.find("#"+ relayId +"_status .status-icon").addClass(relayId +'-on');
        } else {
            $instanceRow.find("#"+ relayId +"_status .status-icon").removeClass(relayId +'-on');
        }
    }
});

$(document).ready(function() {
    if( $("#widget-instances").length ) {
        var widgetInstances = new WidgetInstances();
        setInterval(function() {
            widgetInstances.refreshStatuses();
        }, 10000);
    }
});