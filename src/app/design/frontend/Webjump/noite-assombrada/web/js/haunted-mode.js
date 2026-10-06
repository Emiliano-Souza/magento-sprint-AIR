define([], function () {
    'use strict';

    var storageKey = 'oak-barrel-haunted-mode';
    var activeClass = 'haunted-mode-active';

    function isEnabled() {
        return window.localStorage.getItem(storageKey) === '1';
    }

    function applyMode(enabled) {
        document.documentElement.classList.toggle(
            activeClass,
            enabled
        );

        window.localStorage.setItem(
            storageKey,
            enabled ? '1' : '0'
        );
    }

    function updateToggle(button, enabled) {
        if (!button) {
            return;
        }

        button.setAttribute(
            'aria-pressed',
            enabled ? 'true' : 'false'
        );
    }

    function initialize() {
        var enabled = isEnabled();
        var button = document.querySelector(
            '[data-haunted-mode-toggle]'
        );

        applyMode(enabled);
        updateToggle(button, enabled);

        if (!button) {
            return;
        }

        button.addEventListener('click', function () {
            enabled = !enabled;

            applyMode(enabled);
            updateToggle(button, enabled);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initialize
        );
    } else {
        initialize();
    }
});