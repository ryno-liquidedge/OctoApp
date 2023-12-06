/**
 * Dynamic content panel component - javascript file
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
var com_panel = function(id, csrf) {
	// properties
	this.id = id;
    this.elPanel = $('#panel_' + this.id);
	this.is_on_modal = (this.elPanel.parent(".modal .modal-body").length > 0);
	this.urlArr = [];
	this.currentUrl = false;
	this.urlHistoryArr = [];
	this.currentHistory = false;
	this.parent = false;
	this.csrf = csrf;
	this.hidden_update = false;
}
//--------------------------------------------------------------------------------
com_panel.prototype = {
	//--------------------------------------------------------------------------------
    clear: function() {
    	this.elPanel.html('');
    	this.elPanel.css('display', 'none');
    },
    //--------------------------------------------------------------------------------
    addUrl: function(url, overwrite) {
    	// params
		var overwrite = (overwrite == undefined ? false : overwrite);

    	// add panel id to url
    	url += '&p=' + this.id;

    	// overwrite current url
    	if (overwrite && this.currentUrl !== false) {
    		this.urlArr[this.currentUrl] = url;
    		urlIndex = this.currentUrl;
    	}
    	else {
        	// use existing enty if found
        	var urlIndex = $.inArray(url, this.urlArr);

	    	// add and load the given url
	    	if (urlIndex == -1) urlIndex = this.urlArr.push(url) - 1;
    	}

    	// end
    	return urlIndex;
    },
    //--------------------------------------------------------------------------------
    refresh: function(urlIndex, options) {
		// options
    	options = $.extend({
    		csrf: this.csrf,
    		hidden_update: this.hidden_update
    	}, (options == undefined ? {} : options));

    	// set current url if not set
    	if (this.currentUrl === false) this.currentUrl = 0;

    	// use current url if no index given
    	var urlIndex = (urlIndex == undefined ? this.currentUrl : urlIndex);

    	// check if new url exists
    	if (urlIndex >= this.urlArr.length && urlIndex >= 0) return;

    	// manage history
    	if (urlIndex != this.currentUrl) {
	    	// add history item when not currently in history
	    	if (this.currentHistory === false) this.addHistory(urlIndex);
	    	else {
	    		// truncate history when loading new url inside history
	    		this.urlHistoryArr.length = this.currentHistory + 1;
	    		this.currentHistory = false;
	    	}
    	}

    	// load new url
    	this.currentUrl = urlIndex;

    	// request updated panel information from server
    	this.elPanel.css('display', '');

		if (this.is_on_modal) {
			options.complete = function() {
				core.browser.move_popup_body_header_to_title();
			};
		}

    	return core.ajax.request_update(this.urlArr[this.currentUrl], '#panel_' + this.id, options);

		// debug message
		//alert(this.urlHistoryArr + ' [' + this.currentUrl + '] : ' + this.currentHistory);
    },
    //--------------------------------------------------------------------------------
    back: function(steps, options) {
    	// params
		var steps = (steps == undefined ? 1 : steps);

    	// check if currently in history
    	if (this.currentHistory === false) {
    		// break when no history
    		if (this.urlHistoryArr.length == 0) return;
    		else {
    			// go to start if we do not have enough steps to go back to
    			if (this.urlHistoryArr.length < steps) steps = this.urlHistoryArr.length;

    			// go to lastest step in history
    			this.currentHistory = this.urlHistoryArr.length - steps;

    			// add current url to history
    			this.urlHistoryArr.push(this.currentUrl);
    		}
    	}
    	else {
    		// break when at start of history
    		if (this.currentHistory == 0) return;
    		else {
    			// go to start if we do not have enough steps to go back to
    			if (this.currentHistory < steps) steps = this.currentHistory;

    			// go one step back in history
    			this.currentHistory = this.currentHistory - steps;
    		}
    	}

    	// load history url
    	this.currentUrl = this.urlHistoryArr[this.currentHistory];
    	return this.refresh(null, options);
    },
    //--------------------------------------------------------------------------------
    forward: function() {
    	// check if no history
    	if (this.currentHistory === false) return;

    	// go one step forward in history
    	this.currentHistory++;
    	this.currentUrl = this.urlHistoryArr[this.currentHistory];

    	// remove step from history when latest
    	if (this.currentHistory == this.urlHistoryArr.length - 1) {
    		this.currentHistory = false;
    		this.urlHistoryArr.length--;
    	}

    	// load history url
    	return this.refresh();
    },
    //--------------------------------------------------------------------------------
    request: function(url, options) {
    	// intercept get data from inputs
    	if (options != undefined && options.get != undefined) {
    		url = url + '&' + core.form.serialize_input(options.get);
    		options.get = false;
    	}

    	// add panel id to url
    	url += '&p=' + this.id;

    	// init options
    	options = $.extend({
    		update: '#panel_' + this.id,
    		_panel: this.id,
			csrf: this.csrf,
			hidden_update: this.hidden_update
    	}, (options == undefined ? {} : options));

    	// run request
    	return core.ajax.request(url, options);
    },
    //--------------------------------------------------------------------------------
    requestUpdate: function(url, options) {
    	// init options
    	options = $.extend({
    		get: false,
    		overwrite: false
    	}, (options == undefined ? {} : options));

    	// intercept get data from inputs
    	if (options.get) {
    		url = url + '&' + core.form.serialize_input(options.get);
    		options.get = false;
    	}

    	// run request
		var urlIndex = this.addUrl(url, options.overwrite);
		return this.refresh(urlIndex, options);
    },
    //--------------------------------------------------------------------------------
    requestRefresh: function(url, options) {
    	// init options
    	options = $.extend({
    		success: function(responseText, requestOptions) { eval(requestOptions._panel + '.refresh(null, {no_overlay:options.no_overlay, autoscroll:options.autoscroll});'); }
    	}, (options == undefined ? {} : options));

    	// run request
    	return this.request(url, options);
    },
    //--------------------------------------------------------------------------------
    requestRefreshNr: function(url, nr, options) {
    	// init options
    	options = $.extend({
    		_nr: nr,
    		success: function(responseText, requestOptions) { eval(requestOptions._panel + '.refresh(' + requestOptions._nr + ');'); }
    	}, (options == undefined ? {} : options));

    	// run request
    	return this.request(url, options);
    },
    //--------------------------------------------------------------------------------
    requestBack: function(url, options) {
    	// init options
    	options = $.extend({
    		steps: 1,
    		success: function(responseText, requestOptions) { eval(requestOptions._panel + '.back(' + requestOptions.steps + ');'); }
    	}, (options == undefined ? {} : options));

    	// run request
    	return this.request(url, options);
    },
    //--------------------------------------------------------------------------------
	popup: function(url, options) {
		// add panel id to url and a popup flag
    	url += '&pop=1&p=' + this.id;

    	options = $.extend({
    		no_overlay: true,
    	}, (options == undefined ? {} : options));

		// create popup
		return core.browser.popup(url, options);
	},
	//--------------------------------------------------------------------------------
	modal_switch: function(url, options) {
		// add panel id to url and a popup flag
    	url += '&pop=1&p=' + this.id;

    	options = $.extend({
    		no_overlay: true,
    	}, (options == undefined ? {} : options));

		// create popup
		return core.browser.modal_switch(url, options);
	},
	//--------------------------------------------------------------------------------
	toggle_popup: function(url, options) {
		// add panel id to url and a popup flag
    	url += '&pop=1&p=' + this.id;

    	options = $.extend({
    		no_overlay: true,
    	}, (options == undefined ? {} : options));

		// create popup
		return core.browser.toggle_popup(url, options);
	},
	//--------------------------------------------------------------------------------
	close: function() {
		// close popup
		core.browser.close_popup();
	},
    //--------------------------------------------------------------------------------
    setParent: function(parentName) {
    	this.parent = eval(parentName);
    },
    //--------------------------------------------------------------------------------

    // protected methods

    //--------------------------------------------------------------------------------
    addHistory: function(urlIndex) {
    	// add url number to history if not the same as current url
    	if (urlIndex != this.currentUrl) this.urlHistoryArr.push(this.currentUrl);
    }
    //--------------------------------------------------------------------------------
}