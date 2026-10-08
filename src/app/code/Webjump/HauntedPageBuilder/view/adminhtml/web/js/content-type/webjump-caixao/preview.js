define([
    'Magento_PageBuilder/js/content-type/preview'
], function (PreviewBase) {
    'use strict';

    function Preview(parent, config, stageId) {
        PreviewBase.call(this, parent, config, stageId);
    }

    Preview.prototype = Object.create(PreviewBase.prototype);
    Preview.prototype.constructor = Preview;

    return Preview;
});
