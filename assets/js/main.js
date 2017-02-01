var config = {
    apiKey: ESconfig.FIREBASE_API_KEY,
    authDomain: ESconfig.FIREBASE_AUTH_DOMAIN,
    databaseURL: ESconfig.FIREBASE_DB_URL
};
firebase.initializeApp(config);
//var provider = new firebase.auth.GoogleAuthProvider();
//
//firebase.auth().signInWithPopup(provider).then(function(result) {
//    // This gives you a Google Access Token. You can use it to access the Google API.
//    var token = result.credential.accessToken;
//    // The signed-in user info.
//    var user = result.user;
//    // ...
//}).catch(function(error) {
//    // Handle Errors here.
//    var errorCode = error.code;
//    var errorMessage = error.message;
//    // The email of the user's account used.
//    var email = error.email;
//    // The firebase.auth.AuthCredential type that was used.
//    var credential = error.credential;
//    // ...
//});

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
        'widget-logs': ES.WidgetLogs,
        'widget-formdata': ES.WidgetFormData
    };

    $(".widget").each(function() {
        var widget = WidgetList[$(this).attr('id')];
        if( widget ) {
            ES.factory(WidgetList[$(this).attr('id')]);
        }
    });
});