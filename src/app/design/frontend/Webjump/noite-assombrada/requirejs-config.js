var config = {
    deps: [
        'js/haunted-mode',
        'js/bindings/shake'
    ],

    config: {
        mixins: {
            'Magento_Checkout/js/view/minicart': {
                'js/minicart-mixin': true
            }
        }
    }
};
