$(document).ready(function() {
    $('#link-create-account').click(function() {
        $('#login').hide();
        $('#create-account').show();
    });
    $('#cancel-button').click(function() {
        $('#create-account').hide();
        $('#login').show();
    });

    $.toggleShowPassword({
        field: '#login-password',
        control: '#show-login-password'
    });

    $.toggleShowPassword({
        field: '#create-account-password',
        control: '#show-create-account-password'
    });

    // If there is error messages for the create account form...
    if( $('#create-account-error-messages').length ) {
        $('#login').hide();
        $('#create-account').show();
    }

    if( $('form .error-messages').length ) {
        $('form .error-messages li').each(function(index, value) {
            var fieldName = $(this).data('fieldnameerror'),
                form = this.parentElement.parentElement;

            $(form).find('[name="'+ fieldName +'"]').parent().addClass('error');
        });
    }
});

(function ($) {
    $.toggleShowPassword = function (options) {
        var settings = $.extend({
            field: "#password",
            control: "#toggle_show_password",
        }, options);

        var control = $(settings.control);
        var field = $(settings.field)

        control.bind('click', function () {
            if (control.is(':checked')) {
                field.attr('type', 'text');
            } else {
                field.attr('type', 'password');
            }
        })
    };
}(jQuery));