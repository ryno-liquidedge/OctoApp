/**
 * Component for handling and rendering list data
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
(function($) {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	var properties = {
		name: 'comlist'
	}
	//--------------------------------------------------------------------------------
	// methods
	//--------------------------------------------------------------------------------
	var methods = {
		//--------------------------------------------------------------------------------
		init: function(options) {
			// options
			var options = $.extend({
				url: false,
				panel: false,
				navigation : 'page',
				csrf: false,
				form: false,
				params: {}
			}, options);

			// work
			return this.each(function() {
				var $this = $(this);
				var data = $this.data(properties.name);

				// first time init
				if (!data) {
					$this.data(properties.name, {
						id: $this.attr('id'),
						url: options.url,
						panel: options.panel,
						is_on_modal: ($(options.panel).parent(".modal .modal-body").length > 0),
						navigation: options.navigation,
						params: options.params,
						csrf: options.csrf,
						form: options.form
					});
				}

				core.browser.move_popup_body_header_to_title();
			});
		},
		//--------------------------------------------------------------------------------
		destroy: function() {
			return this.each(function() {
				var $this = $(this);
				$this.remove();
			});
		},
		//--------------------------------------------------------------------------------
		update: function() {
			return this.each(function() {
				var $this = $(this);
				var data = $this.data(properties.name);

				if (data.url.search(/&comlist=/ig) != -1) data.url = data.url.replace(/&comlist=.*?(&|$)/ig, '$1');
				var request_options = {
					csrf: data.csrf,
					form: data.form,
					data: 'comlist=' + encodeURIComponent(JSON.stringify(data.params))
				};

				if (data.is_on_modal) {
					request_options.complete = function() {
						core.browser.move_popup_body_header_to_title();
					};
				}

				request_options.autoscroll = false;

				core.ajax.request_update(data.url, data.panel, request_options);
			});
		},
		//--------------------------------------------------------------------------------
		reset: function() {
			return this.each(function() {
				var $this = $(this);
				var data = $this.data(properties.name);

				data.params.r = 1;
				$this.data(properties.name, data);

				$this[properties.name]('update');
			});
		},
		//--------------------------------------------------------------------------------
		quickfind: function() {
			return this.each(function() {
				var $this = $(this);
				var data = $this.data(properties.name);

				if (data.navigation == 'page') data.params.o = 0;
				data.params.q = $('#' + data.id + '_quickfind').val();
				$this.data(properties.name, data);

				$this[properties.name]('update');
			});
		},
		//--------------------------------------------------------------------------------
		datefind: function() {
			return this.each(function() {
				var $this = $(this);
				var data = $this.data(properties.name);

				if (data.navigation == 'page') data.params.o = 0;
				data.params.df = $('#' + data.id + '_datefrom').val();
				data.params.dt = $('#' + data.id + '_dateto').val();
				$this.data(properties.name, data);

				$this[properties.name]('update');
			});
		},
		//--------------------------------------------------------------------------------
		page: function(options) {
			// options
			var options = $.extend({
				offset: 0
			}, options);

			// work
			return this.each(function() {
				var $this = $(this);
				var data = $this.data(properties.name);

				data.params.o = options.offset;
				$this.data(properties.name, data);

				$this[properties.name]('update');
			});
		},
		//--------------------------------------------------------------------------------
		sort: function(options) {
			// options
			var options = $.extend({
				field: 0,
				order: 0
			}, options);

			// work
			return this.each(function() {
				var $this = $(this);
				var data = $this.data(properties.name);

				data.params.sf = options.field;
				data.params.so = options.order;
				$this.data(properties.name, data);

				$this[properties.name]('update');
			});
		},
		//--------------------------------------------------------------------------------
		togglecheck: function() {
			return this.each(function() {
				var $this = $(this);
				var data = $this.data(properties.name);

				var checked = $('#' + data.id + '_chkall:checked').val();
				checked = (checked ? 'checked' : false);
				$('#' + data.id + ' input[id*="' + data.id + '_chk\\["]').each(function() {
					$(this).prop('checked', checked);
				});
			});
		}
		//--------------------------------------------------------------------------------
	};
	//--------------------------------------------------------------------------------
	// register plugin
	//--------------------------------------------------------------------------------
	$.fn[properties.name] = function(method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		}
		else {
			$.error('Method ' + method + ' does not exist on jQuery.' + properties.name);
		}
	};
	//--------------------------------------------------------------------------------
})(jQuery);
//--------------------------------------------------------------------------------