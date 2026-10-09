define([
    'uiComponent',
    'ko',
    'jquery',
    'Magento_Customer/js/customer-data',
    'mage/url',
    'mage/cookies'
], function (
    Component,
    ko,
    $,
    customerData,
    urlBuilder
) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Webjump_HauntedCustomerData/scare-counter'
        },

        initialize: function () {
            this._super();

            this.scareData = customerData.get('haunted-scares');
            this.isLoading = ko.observable(false);
            this.isJumpscareVisible = ko.observable(false);
            this.jumpscareTimeout = null;

            this.total = ko.pureComputed(function () {
                var data = this.scareData();

                return Number(data.total) || 0;
            }, this);

            if (typeof this.scareData().total === 'undefined') {
                customerData.reload(['haunted-scares'], false);
            }

            window.addEventListener(
                'haunted-mode:enabled',
                this.handleScare.bind(this)
            );

            return this;
        },

        handleScare: function () {
            this.showJumpscare();
            this.incrementScare();
        },

        showJumpscare: function () {
            var self = this;

            window.clearTimeout(this.jumpscareTimeout);

            this.isJumpscareVisible(true);

            this.jumpscareTimeout = window.setTimeout(function () {
                self.isJumpscareVisible(false);
            }, 1400);
        },

        incrementScare: function () {
            var self = this;

            if (this.isLoading()) {
                return;
            }

            this.isLoading(true);

            $.ajax({
                url: urlBuilder.build('haunted/scare/increment'),
                type: 'POST',
                dataType: 'json',
                data: {
                    form_key: $.mage.cookies.get('form_key')
                }
            }).fail(function (xhr) {
                console.error(
                    'Não foi possível incrementar o contador de sustos.',
                    xhr
                );
            }).always(function () {
                self.isLoading(false);
            });
        }
    });
});