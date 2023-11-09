// globals
var wysiwyg_editor_gbl = false;
var wysiwyg_layouteditable_gbl = false;
var wysiwyg_panelsrc_gbl = false;

(function (factory) {
  /* global define */
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else {
    // Browser globals: jQuery
    factory(window.jQuery);
  }
}(function ($) {
    // template
    var tmpl = $.summernote.renderer.getTemplate();

    /**
     * @class plugin.bodyimage
     *
     * Image buttons
     */
    $.summernote.addPlugin({
      name: 'bodyimage', // name of plugin
      buttons: { // buttons
          bodyimage: function (e, editor) {
              // get data
              var clickdata_arr = editor.getBodyImagePluginData();

              // build button
              return tmpl.iconButton('fas fa-file-image', {
                  event: 'bodyimage',
                  title: 'Insert image',
                  value: clickdata_arr.url,
              });
          },
      },

      events: { // events
          bodyimage: function (event, editor, layoutInfo, value) {
              // store editor and current editable node
              wysiwyg_editor_gbl = editor;
              wysiwyg_layouteditable_gbl = layoutInfo.editable();

              // save current range (position), restore on click later
              wysiwyg_editor_gbl.saveRange(wysiwyg_layouteditable_gbl);

              // trigger popup
              wysiwyg_panelsrc_gbl.popup(value);
          },
      }
  });
}));