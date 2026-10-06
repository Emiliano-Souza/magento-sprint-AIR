define([
    'uiComponent',
    'ko',
    'mage/translate'
], function (Component, ko, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magento_Theme/countdown',
            endDate: null
        },

        initialize: function () {
            this._super();

            this.remainingSeconds = ko.observable(0);

            this.message = ko.computed(function () {
                var total = this.remainingSeconds();

                if (total <= 0) {
                    return $t('The Haunted Night has ended');
                }

                var days = Math.floor(total / 86400);
                var hours = Math.floor((total % 86400) / 3600);
                var minutes = Math.floor((total % 3600) / 60);
                var seconds = total % 60;

                return $t('Ends in')
                    + ' '
                    + days + ' ' + $t('days')
                    + ', '
                    + hours + ' ' + $t('hours')
                    + ', '
                    + minutes + ' ' + $t('minutes')
                    + ' ' + $t('and') + ' '
                    + seconds + ' ' + $t('seconds');
            }, this);

            this.updateCountdown();

            this.timer = setInterval(
                this.updateCountdown.bind(this),
                1000
            );

            return this;
        },

        updateCountdown: function () {
            if (!this.endDate) {
                this.remainingSeconds(0);
                return;
            }

            var now = new Date();
            var end = new Date(this.endDate);
            var difference = Math.floor(
                (end.getTime() - now.getTime()) / 1000
            );

            this.remainingSeconds(
                difference > 0 ? difference : 0
            );
        }
    });
});