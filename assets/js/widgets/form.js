App.WidgetForm = App.Widget.extend({
    init: function() {
        var me = this;
        me._super( 'widget-form' );

        var $component = $('#' + this.cssId);

        $component.find('form').submit(function(e) {
            $form = $(this);
            e.preventDefault();

            $.ajax({
                url: BASE_URL +'api/'+ $form.data('api-url'), // TODO PUT   +'/'+ 1,
                type: 'POST', // TODO PUT
                contentType: 'application/json',
                data: JSON.stringify($form.serializeFormJSON()),
                success: function() {
                    alert( "success" );
                },
                dataType: 'json'
            }).fail(function() {
                alert( "error" );
            });
        });
    }
});

(function ($) {
    $.fn.serializeFormJSON = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
})(jQuery);