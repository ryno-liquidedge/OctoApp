/**
 * jQuery plugin.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
(function($) {
	//--------------------------------------------------------------------------------
	var setup = {
		name: 'ui_ping',
		functions: {
			init: function(options) {
				// options
				var options = $.extend({
				}, options);

				// run
				return this.each(function() {
					// init
					var $this = $(this);
					var $data = $.extend({
						init: false,
					}, $this.data(setup.name));

					// run once
					if ($data.init)	return this;
					$data.init = true;
					$this.data(setup.name, $data);

					// start as good
					$this.ui_ping('set_status', 'good');

					// setup recurring server check and change styles based on response
					window.setInterval(function () {
						var start_time = new Date().getTime();
						$.ajax({
							type: 'GET',
							url: "/files/img/ping.png?nocache=" + start_time,
							success: function (response) {
								var request_time = new Date().getTime() - start_time;
								if (request_time < 500) $this.ui_ping('set_status', 'good');
								else $this.ui_ping('set_status', 'slow');
							},
							error: function (jqXHR, textStatus) {
								$this.ui_ping('set_status', 'error');
							}
						});
					}, 10000);
				});
			},
			//--------------------------------------------------------------------------------
			set_status: function(status) {
				return this.each(function() {
					// init
					var $this = $(this);

					// check current status
					if ($this.hasClass('ui-ping-status-' + status)) return;

					// remove current status
					$this.removeClass(function(index, item) {
						return (item.match(/(^|\s)ui-ping-status-[a-z]+/ig) || []).join(' ');;
					});

					// set new status
					$this.addClass('ui-ping-status-' + status);
					switch (status) {
						case 'good':
							$this.attr('data-original-title', 'Good connection');
							break;

						case 'slow':
							$this.attr('data-original-title', 'Slow connection');
							break;

						case 'error':
							$this.attr('data-original-title', 'No connection');
							break;
					}
				});
			}
			//--------------------------------------------------------------------------------
		}
	};
	//--------------------------------------------------------------------------------
	core.jquery.register_plugin(setup);
	//--------------------------------------------------------------------------------
})(jQuery);