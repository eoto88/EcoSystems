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
    highchartsOptions.global =  {
        timezoneOffset: -4,
        useUTC: false
    };

    if( I18n.lang == 'fr') {
        highchartsOptions.lang = highchartsLangFr;
    }

    $('[name="params"]').selectize({
        maxItems: null,
        valueField: 'id',
        labelField: 'title',
        searchField: 'title',
        preload: true,
        hideSelected: true,
        optgroupField: 'id_category',
        optgroupValueField: 'id_category',
        optgroupLabelField: 'category_title',
        //options: [
        //    {id: 1, title: 'Spectrometer', url: 'http://en.wikipedia.org/wiki/Spectrometers'},
        //    {id: 2, title: 'Star Chart', url: 'http://en.wikipedia.org/wiki/Star_chart'},
        //    {id: 3, title: 'Electrical Tape', url: 'http://en.wikipedia.org/wiki/Electrical_tape'}
        //],
        plugins: ['remove_button','optgroup_columns'],
        create: false,
        load: function(query, callback) {
            //if (!query.length) return callback();
            $.ajax({
                url: BASE_URL + "api/params/", // 'https://api.github.com/legacy/repos/search/' + encodeURIComponent(query),
                type: 'GET',
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res.entities);
                }
            });
        }
    });

    Highcharts.setOptions(highchartsOptions);

    $('#dropdown-instances li a').click(function (e) {
        e.preventDefault();
        //window.history.pushState($(this).text(), $(this).text(), $(this).attr('href'));
        $('#select-instance span').text($(this).text());
        $('#dropdown-instances .active').toggleClass('active');
        $(this).parent().toggleClass('active');

        if(ES.isDashboard()) {
            document.location = BASE_URL + 'live/'+ ES.getCurrentInstanceId();
        } else {
            var url = $('#left-panel .active a').attr('href');
            document.location = url +'/'+ ES.getCurrentInstanceId();
        }
    });

    $('#left-panel a.require-instance-id').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        document.location = url +'/'+ ES.getCurrentInstanceId();
    });

    $('#minify-menu').click(function() {
        $(this).find('i').toggleClass('fa-arrow-circle-right').toggleClass('fa-arrow-circle-left');
        $('body').toggleClass('minified-menu');
    });

    $('#mobile-menu-icon').click(function() {
        $(this).toggleClass('active');
        $('#left-panel').toggleClass('mobile-closed');
    });

    $('.icon-picker').iconpicker({
        title: "Choose an icon", // TODO
        icons: $.merge([
                'wi-day-sunny',
                'wi-day-cloudy',
                'wi-day-cloudy-gusts',
                'wi-day-cloudy-windy',
                'wi-day-fog',
                'wi-day-hail',
                'wi-day-haze',
                'wi-day-lightning',
                'wi-day-rain',
                'wi-day-rain-mix',
                'wi-day-rain-wind',
                'wi-day-showers',
                'wi-day-sleet',
                'wi-day-sleet-storm',
                'wi-day-snow',
                'wi-day-snow-thunderstorm',
                'wi-day-snow-wind',
                'wi-day-sprinkle',
                'wi-day-storm-showers',
                'wi-day-sunny-overcast',
                'wi-day-thunderstorm',
                'wi-day-windy',
                'wi-solar-eclipse',
                'wi-hot',
                'wi-day-cloudy-high',
                'wi-day-light-wind',
                'wi-night-clear',
                'wi-night-alt-cloudy',
                'wi-night-alt-cloudy-gusts',
                'wi-night-alt-cloudy-windy',
                'wi-night-alt-hail',
                'wi-night-alt-lightning',
                'wi-night-alt-rain',
                'wi-night-alt-rain-mix',
                ''],
                // https://erikflowers.github.io/weather-icons/
            $.iconpicker.defaultOptions.icons),
        fullClassFormatter: function(val){
            if(val.match(/^fa-/)){
                return 'fa '+val;
            }else{
                return 'wi '+val;
            }
        }
    });
});

var App = {
    factory: function (class_) {
        return new class_();
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