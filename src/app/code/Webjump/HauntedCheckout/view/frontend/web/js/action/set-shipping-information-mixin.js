define([
    'mage/utils/wrapper',
    'uiRegistry',
    'Magento_Checkout/js/model/quote'
], function (wrapper, registry, quote) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(
            setShippingInformationAction,
            function (originalAction) {
                var provider = registry.get('checkoutProvider'),
                    shippingAddress = quote.shippingAddress(),
                    message = provider
                        ? provider.get('shippingAddress.haunted_message')
                        : '';

                if (shippingAddress) {
                    shippingAddress.extension_attributes =
                        shippingAddress.extension_attributes || {};

                    shippingAddress.extension_attributes.haunted_message =
                        String(message || '').trim();
                }

                return originalAction();
            }
        );
    };
});
