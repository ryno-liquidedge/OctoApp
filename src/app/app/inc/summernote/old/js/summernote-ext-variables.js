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
   * @class plugin.variables 
   * 
   * Variables buttons  
   */
  $.summernote.addPlugin({
    /** @property {String} name name of plugin */
    name: 'variables',
    /** 
     * @property {Object} buttons 
     * @property {Function} buttons.variables   function to make button
     */
    buttons: { // buttons
    	
      variables: function (e, editor) {
    	  
    	  	var availableVars = editor.getVariablesPluginData();
    	  	var list = "";
    	  
    	  	for (var prop in availableVars) {
    	  		var key = prop;
    	  		var desc = availableVars[prop];    	  		
    	  		if (desc.trim().toString()=='-') {
    	  			list += '<li class="divider"></li>';
    	  		} else { 
    	  			list += '<li><a class="dropdown-item" data-event="variablesDropdown" href="javascript:void(0);" data-value="'+key+'">'+key+' - '+desc+'</a></li>';
    	  		}
    	  	}
      		var dropdown = '<ul class="dropdown-menu">' + list + '</ul>';

	        return tmpl.iconButton('fa fa-tags', {
	          title: 'Insert variable',
	          hide: true,
	          dropdown : dropdown
	        });
     	},
    },

    /**
     * @property {Object} events 
     * @property {Function} variablesDropdown run function when button that has a 'variablesDropdown' event name  fires click 
     */
    events: { // events      
      variablesDropdown: function (event, editor, layoutInfo, value) {
        // Get current editable node
        var $editable = layoutInfo.editable();

        // Call insertText with value        
        editor.insertText($editable, value);             
      },     
    }
  });
}));