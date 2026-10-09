define([
    'ko'
], function (ko) {
    'use strict';

    ko.bindingHandlers.shake = {
        init: function (element, valueAccessor) {
            var options = ko.unwrap(valueAccessor()) || {};
            var intensity = Number(ko.unwrap(options.intensity)) || 4;
            var duration = Number(ko.unwrap(options.duration)) || 300;

            intensity = Math.max(0, intensity);
            duration = Math.max(0, duration);

            function shake() {
                if (!intensity || !duration) {
                    return;
                }

                if (
                    window.matchMedia &&
                    window.matchMedia('(prefers-reduced-motion: reduce)').matches
                ) {
                    return;
                }

                if (typeof element.animate !== 'function') {
                    return;
                }

                element.animate([
                    { transform: 'translateX(0)' },
                    { transform: 'translateX(-' + intensity + 'px)' },
                    { transform: 'translateX(' + intensity + 'px)' },
                    { transform: 'translateX(-' + intensity + 'px)' },
                    { transform: 'translateX(' + intensity + 'px)' },
                    { transform: 'translateX(0)' }
                ], {
                    duration: duration,
                    easing: 'ease-in-out'
                });
            }

            element.addEventListener('mouseenter', shake);

            ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
                element.removeEventListener('mouseenter', shake);
            });
        }
    };

    return ko.bindingHandlers.shake;
});
