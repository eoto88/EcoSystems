/**
 * @deprecated
 */
ES.WidgetForm = ES.Widget.extend({
    init: function() {
        var me = this;
        me._super( 'widget-form' );

        var $component = $('#' + this.cssId);

        $component.find('form').submit(function(e) {
            var $form = $(this),
                method = 'POST',
                url = BASE_URL +'api/'+ $form.data('api-url'),
                requiresIdInstance = $form.data('requires-id-instance'),
                jsonData = $form.serializeFormJSON();

            e.preventDefault();

            if(requiresIdInstance) {
                var id_instance = ES.getActiveInstanceId();
                url = BASE_URL +'api/instances/'+id_instance+'/'+ $form.data('api-url'); // TODO PUT   +'/'+ 1,
            }

            me.submitForm({
                form: $form,
                url: url,
                method: method,
                data: jsonData,
                success: function(data) {

                }
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