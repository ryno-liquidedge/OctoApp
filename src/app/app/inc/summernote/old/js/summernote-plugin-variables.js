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
        'customVariablesPlugin': function (context) {
            // ensure data exists for use
            if (typeof context.options.callbacks.getVariablesPluginData !== 'function') {
                return;
            }

            // init
            let ui = $.summernote.ui;
            let plugin_data = context.options.callbacks.getVariablesPluginData();
            let list = '';

            // build dropdown items
            for (let prop in plugin_data) {
                // params
                let key = prop;
                let desc = plugin_data[prop];

                // divider or entry depending on value
                if (desc.trim().toString() == '-') {
                    list += '<li class=\"dropdown-divider\"></li>';
                }
                else {
                    list += '<li><a class=\"dropdown-item\" data-event=\"variablesDropdown\" href=\"javascript:void(0);\" data-value=\"'+key+'\">'+key+' - '+desc+'</a></li>';
                }
            }

            // create button
            context.memo('button.customVariablesPlugin', function () {
                let event = ui.buttonGroup([
                    ui.button({
                        contents: '<i class=\"fa fa-tags\" aria-hidden=\"true\"></i>',
                        container: false,
                        tooltip: 'Insert variable',
                        data: {
                            toggle: 'dropdown'
                        }
                    }),
                    ui.dropdown({
                        items: list, // add items to dropdown
                        callback: function (items) {
                            // bind clicks to each item
                            $(items).find('li a').on('click', function(){
                                context.invoke("editor.insertText", $(this).data('value'))
                            })
                        }
                    })
                ]);

                // done
                return event.render();   // return button as jquery object
            });
        }
    });
}));