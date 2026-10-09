define([], function () {
    'use strict';

    var storageKey = 'oak-barrel-haunted-mode';
    var activeClass = 'haunted-mode-active';
    var scareEvent = 'haunted-mode:enabled';
    var mobileMedia = window.matchMedia('(max-width: 767px)');

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

    function dispatchScareEvent() {
        window.dispatchEvent(
            new CustomEvent(scareEvent)
        );
    }

    function setupResponsivePanel(panel) {
        var navigation = document.querySelector(
            '.nav-sections .navigation'
        );

        var originalParent;
        var placeholder;

        if (!panel || !navigation) {
            return;
        }

        originalParent = panel.parentNode;
        placeholder = document.createComment(
            'haunted-mode-panel-position'
        );

        originalParent.insertBefore(
            placeholder,
            panel
        );

        function updatePanelPosition() {
            if (mobileMedia.matches) {
                if (panel.parentNode !== navigation.parentNode) {
                    navigation.parentNode.insertBefore(
                        panel,
                        navigation
                    );
                }

                return;
            }

            if (
                placeholder.parentNode &&
                panel.parentNode !== originalParent
            ) {
                placeholder.parentNode.insertBefore(
                    panel,
                    placeholder.nextSibling
                );
            }
        }

        updatePanelPosition();

        if (typeof mobileMedia.addEventListener === 'function') {
            mobileMedia.addEventListener(
                'change',
                updatePanelPosition
            );
        } else {
            mobileMedia.addListener(updatePanelPosition);
        }
    }

    function initialize() {
        var enabled = isEnabled();
        var button = document.querySelector(
            '[data-haunted-mode-toggle]'
        );

        var panel = document.querySelector(
            '.haunted-mode-panel'
        );

        applyMode(enabled);
        updateToggle(button, enabled);
        setupResponsivePanel(panel);

        if (!button) {
            return;
        }

        button.addEventListener('click', function () {
            enabled = !enabled;

            applyMode(enabled);
            updateToggle(button, enabled);

            if (enabled) {
                dispatchScareEvent();
            }
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