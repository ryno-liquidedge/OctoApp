/**
 * Tab component - javascript file
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
var com_menu = function(id, page_id, options) {
    // options
    var options = $.extend({
        disable_collapse: false,
        disable_headers: true,
    }, (options == undefined ? {} : options));

	// properties
	this.id = id;
	this.page_id = page_id;
	this.menu_wrapper = $('#' + this.id + '.ui-menu');
    this.column = this.menu_wrapper.parent('div[class^=col]');
	this.selectedMenu =  $('.ui-menu .list-group .list-group-item').first();
	this.collapsed = null;
	this.disable_collapse = options.disable_collapse;

	// set state
    this.set_default_state();

    // remove headers
    if (options.disable_headers) {
        this.column.find(':header').remove();
    }

	// hover
}
//--------------------------------------------------------------------------------
com_menu.prototype = {
	//--------------------------------------------------------------------------------
    set_default_state: function() {
        // params
        var collapsed = (this.disable_collapse ? false : core.storage.local.get('ui_menu', this.page_id, 'collapsed'));
        var column = this.menu_wrapper.parent('div[class^=col]');

        // expand
        if (!collapsed || collapsed == null || typeof collapsed === 'object') {
            this.collapsed = false;
            this.menu_wrapper.removeClass('d-none');
            this.store_collapsed(this.collapsed);
        }
        else {
            this.collapsed = true;
            $('#' + this.id + '_expand').removeClass('d-none');
            this.menu_wrapper.parent('div[class^=col]').css({
                'max-width':'54px',
            });
            column.find(':header, .form-group').addClass('d-none');
        }
    },
	//--------------------------------------------------------------------------------
    store_collapsed: function(state) {
        core.storage.local.set('ui_menu', this.page_id, 'collapsed', state);
    },
	//--------------------------------------------------------------------------------
	select: function(el, options) {
        // options
        options = $.extend({
        }, (options == undefined ? {} : options));

        // rotate caret icon if applicable
        $(el).find('.fa-caret-right').toggleClass('fa-rotate-90');
    },
	//--------------------------------------------------------------------------------
    select_active_menu:function(index){
        this.menu_wrapper.find('.list-group-item').removeClass('active');
        this.menu_wrapper.find('.list-group-item[data-index='+index+']').addClass('active');
    },
	//--------------------------------------------------------------------------------
    toggle: function() {
        // disabled
        if (this.disable_collapse) {
            return;
        }

        // toggle
	    if (this.collapsed) {
	        this.expand();

        }
	    else {
	        this.collapse();
        }
    },
	//--------------------------------------------------------------------------------
    collapse: function() {
	    // elements
        var menu_wrapper = this.menu_wrapper;
        var column = menu_wrapper.parent('div[class^=col]');

        // disabled
        if (this.disable_collapse) {
            return;
        }

	    // column
        column.css({
            'max-width':'54px',
            'transition':'max-width 0.5s'
        });
        column.find(':header, .form-group').fadeOut(50);
        this.menu_wrapper.fadeOut(50);

        // buttons
        $('#' + this.id + '_collapse').hide(50);
        $('#' + this.id + '_expand').show(100);
        $('#' + this.id + '_expand').removeClass('d-none');

        // flag
        this.collapsed = true;
        this.store_collapsed(this.collapsed);
    },
    //--------------------------------------------------------------------------------
    expand: function() {
        // elements
        var menu_wrapper = this.menu_wrapper;
        var column = menu_wrapper.parent('div[class^=col]');

        // disabled
        if (this.disable_collapse) {
            return;
        }

	    // column
        column.css({
            'max-width':'',
            'transition':'max-width 0.5s'
        });
        column.find(':header, .form-group').fadeIn(50).removeClass('d-none');
        this.menu_wrapper.fadeIn(50);
        this.menu_wrapper.removeClass('d-none');

        // buttons
        $('#' + this.id + '_collapse').show(100);
        $('#' + this.id + '_expand').hide(50);

        // flag
        this.collapsed = false;
        this.store_collapsed(this.collapsed);
    },
    //--------------------------------------------------------------------------------
}