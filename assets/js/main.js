var highchartsOptions ={},
    highchartsLangFr = {
    months: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin',
        'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
    weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi',
        'Jeudi', 'Vendredi', 'Samedi'],
    shortMonths: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil',
        'Aout', 'Sept', 'Oct', 'Nov', 'Déc'],
    decimalPoint: ',',
    downloadPNG: 'Télécharger en image PNG',
    downloadJPEG: 'Télécharger en image JPEG',
    downloadPDF: 'Télécharger en document PDF',
    downloadSVG: 'Télécharger en document Vectoriel',
    exportButtonTitle: 'Export du graphique',
    loading: 'Chargement en cours...',
    printButtonTitle: 'Imprimer le graphique',
    resetZoom: 'Réinitialiser le zoom',
    resetZoomTitle: 'Réinitialiser le zoom au niveau 1:1',
    thousandsSep: ' ',
    decimalPoint: ','
};

$(document).ready(function() {
    resizeMainSection()

    highchartsOptions.global =  {
        timezoneOffset: -4,
        useUTC: false
    };

    if( translations.lang == 'fr') {
        highchartsOptions.lang = highchartsLangFr;
    }

    Highcharts.setOptions(highchartsOptions);

    setInterval(function() {
        $.ajax({
            url: BASE_URL + "ajax/getLiveData",
            cache: false,
            dataType: "json"
        }).done(function(data) {
            var status;
            if(data.stillAliveStatus == "still-alive")
                status = '<i class="fa fa-check success"></i>';
            else
                status = '<i class="fa fa-exclamation-triangle error"></i>';
            $("#still_alive").html(status).attr("title", translations.lastCommunication + ": " + data.lastCommunication);
            
            changeStatus('pump', 'Pump', data.pumpStatus);
            changeStatus('light', 'Light', data.lightStatus);
            changeStatus('fan', 'Fan', data.fanStatus);
            changeStatus('heater', 'Heater', data.heaterStatus);
        });
    }, 15000);
    
    $("#tasks_list li").click(function() {
        var $todo = $(this);
        $todo.find('.check').addClass('done');
        var id = $todo.attr('id');
        id = id.replace("todo-", "");
        $.ajax({
            url: BASE_URL + "ajax/updateToDo/" + id,
            cache: false,
            dataType: "json"
        }).done(function(data) {
            $("#todo-" + data.id).animate({'height': 0, 'opacity': 0}, 500, function() {
                $(this).remove();
                if($("#tasks_list li").length === 0) {
                    $("#tasks_list").html('<li id="no-todo">' + translations.noTaskTodoList + '</li>');
                }
            });
        });
    });

    $("#instance_list li").click(function() {
        var $instance = $(this);
        var id_instance = $instance.attr('id');
        document.location = BASE_URL + '' /id_instance
    });

    $('#left-panel .parent').click(function(e) {
        e.preventDefault();
        $(this).toggleClass('open').find('ul').slideToggle();
    });

    $( window ).resize(function() {
        resizeMainSection();
    });
});

function resizeMainSection() {
    var winHeight = $( window ).height(),
        mainMinHeight = winHeight - 49,
        contentHeight = $('#content').height() + 80; /* Padding */
    if( mainMinHeight > contentHeight ) {
        $('#main').height( mainMinHeight );
    } else {
        $('#main').height( contentHeight );
    }
}

function changeStatus(relayId, relayName, status) {
    $("#"+ relayId +"_status").attr('title', relayName +' is '+ status);
    if(status === "on") {
        $("#"+ relayId +"_status").find('.status-icon').addClass(relayId +'-on');
    } else {
        $("#"+ relayId +"_status").find('.status-icon').removeClass(relayId +'-on');
    }
}

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
    Class.extend = function(prop) {
        var _super = this.prototype;

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