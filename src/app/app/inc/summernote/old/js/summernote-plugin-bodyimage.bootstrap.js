// globals
var wysiwyg_panelsrc_gbl = false;

(function (factory) {
    /* global define */
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(window.jQuery);
    }
}(function ($) {
    // Extend Plugins for adding our plugin.
    $.extend($.summernote.plugins, {
        'bodyImagePlugin': function (context) {
            // ensure data exists for use
            if (typeof context.options.callbacks.getBodyImagePluginData !== 'function') {
                return;
            }

            // init
            let ui = $.summernote.ui;
            let plugin_data = context.options.callbacks.getBodyImagePluginData();

            // create button
            context.memo('button.bodyImagePlugin', function () {
                let event = ui.buttonGroup([
                    ui.button({
                        contents: '<i class=\"fa fa-file-image\" data-editor-id=\"\"></i>',
                        container: false,
                        tooltip: 'Insert image',
                        click: function() {
                            // params
                            let parent_id = $(this).parents('div.note-editor').siblings('textarea').attr('id');
                            let url = plugin_data.url+'&parent_id='+parent_id;

                            // save current range (position), restore on click later
                            context.invoke('editor.saveRange');

                            // trigger popup
                            wysiwyg_panelsrc_gbl.popup(url);
                        },
                    })
                ]);

                // done
                return event.render();   // return button as jquery object
            });
        }
    });
}));