define([
    'ko',
    'mage/translate'
], function (ko, $t) {
    'use strict';

    return function (Minicart) {
        return Minicart.extend({
            initialize: function () {
                this._super();

                this.hauntedMessage = ko.computed(function () {
                    var quantity = Number(
                        this.getCartParam('summary_count') || 0
                    );

                    if (quantity === 0) {
                        return $t('Your cauldron is empty');
                    }

                    if (quantity >= 13) {
                        return $t('Thirteen items. Brave.');
                    }

                    return $t('Items in the cauldron:') + ' ' + quantity;
                }, this);

                return this;
            }
        });
    };
});