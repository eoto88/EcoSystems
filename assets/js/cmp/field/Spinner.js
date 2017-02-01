/**
 * Created by eoto88 on 17/01/17.
 */
ES.Field.Spinner = ES.Field.Text.extend({
    initVal: 0,
    minVal: null,
    maxVal: null,
    step: 1,
    decimals: 0,
    $spinner: null,

    initCmp: function(config) {
        var me = this;

        ES.apply(me, config);
        me._super(me.cmpType);
        me.appendTo(config.parent);
        me.$spinner = me.getInput();

        var initval = me.$spinner.data('initval'),
            min = me.$spinner.data('min'),
            max = me.$spinner.data('max'),
            step = me.$spinner.data('step'),
            decimals = me.$spinner.data('decimals');

        me.$spinner.TouchSpin({
            initval: initval,
            min: min,
            max: max,
            step: step,
            decimals: decimals,
            boostat: 5,
            maxboostedstep: 10
        });
    },

    getTpl: function() {
        var me = this;
            //label = me.getLabel();

        me.attr += ' data-initval="'+ me.initVal +'"';
        if( ! ES.isEmpty(me.minVal) ) {
            me.attr += ' data-min="'+ me.minVal +'"';
        }
        if( ! ES.isEmpty(me.maxVal) ) {
            me.attr += ' data-max="'+ me.maxVal +'"';
        }
        if( ! ES.isEmpty(me.step) ) {
            me.attr += ' data-step="'+ me.step +'"';
        }
        if( ! ES.isEmpty(me.decimals) ) {
            me.attr += ' data-decimals="'+ me.decimals +'"';
        }

        return me._super();
    },

    /**
     * @override
     */
    destroy: function() {
        var me = this;

        me.$spinner.destroy();
    }
}, 'ES.Field.Spinner');