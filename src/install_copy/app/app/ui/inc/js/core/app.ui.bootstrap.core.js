/**
 * Javascript nova core
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
var core = {
	//--------------------------------------------------------------------------------
	ajax: {
		//--------------------------------------------------------------------------------
		request: function(url, options) {
			// options
			var options = $.extend({
				update: false,
				data: false,
				get: false,
				post: false,
				form: false,
				confirm: false,
				method: 'POST',
				success: false,
				complete: false,
				beforeSend: false,
				done: false,
				no_overlay: false,
				confirm_cancel: false,
				ok_confirm: false,
				screen_overlay: true,
				csrf: false,
				hidden_update: false,
				async: true,
				autoscroll: false,
				autoscroll_offset: -150,
				loader_html: false,
			}, (options == undefined ? {} : options));

			// confirm
			if (options.confirm === true) {
				options.confirm = 'Are you sure you want to continue?';
			}

			// function
			var $ajax = {
				done: function() { /* support for done method return */ }
			};
			var do_ajax_request = function() {
				// overlay
				if (!options.no_overlay && options.update) {
					core.overlay.show(options.update);
				}

				// post data
				var data_arr = [];
				if (options.form) data_arr.push(core.form.serialize(options.form));
				if (options.post) {
					if (!options.data) options.data = '';
					options.data += core.form.serialize_input(options.post);
				}
				if (typeof options.data === 'object') {
					for (var prop in options.data) {
						data_arr.push(encodeURIComponent(prop) + '=' + encodeURIComponent(options.data[prop]));
					}
					options.data = false;
				}
				if (options.data) data_arr.push(options.data);
				if (options.csrf && options.method != 'GET' && !options.form) data_arr.push('_csrf=' + encodeURIComponent(options.csrf));
				if (data_arr.length > 0) options.data = data_arr.join('&');

				// get data
				if (options.get) url = url + '&' + core.form.serialize_input(options.get);

				// request
				$ajax = $.ajax(url, {
					async: options.async,
					type: options.method,
					data: options.data,
					beforeSend:function(){

						if (options.beforeSend) options.beforeSend.apply(this, [options]);

						// overlay
                        if(options.loader_html){
                            $(options.update).html(options.loader_html);
                        }else if (!options.no_overlay && options.update) {
							if(options.screen_overlay){
								if(!options.no_overlay) core.overlay.show(options.update);
							}else{
								$(options.update).html('<div class="text-center panel-loader"><div class="min-vh-15"></div><div class="spinner-grow text-muted"></div><div class="spinner-grow text-primary"></div><div class="spinner-grow text-success"></div><div class="spinner-grow text-info"></div><div class="spinner-grow text-warning"></div><div class="spinner-grow text-danger"></div><div class="spinner-grow text-secondary"></div><div class="spinner-grow text-muted"></div><div class="min-vh-15"></div></div>');
							}
						}
					},
					success: function(data) {
						// check for structured response

						if (typeof data == 'string' && (/^##JSON##/).test(data)) {
							// extract structure
							data = $.parseJSON(data.replace(/^##JSON##/, ''));

							// execute action
							switch (data.type) {
								case 'alert' :
									core.browser.alert(data.message, data.title);
									$('button').button('reset');
									break;
							}
							return;
						}

						// check response for message signature
						if (typeof data == 'string' && (/##MESSAGE##/).test(data)) {
							// split message string and data
							var message_index = data.search(/##MESSAGE##/);
							var message = data.substr(message_index);
							data = data.substr(0, message_index);

							// show message
							var message_arr = eval(message.replace('##MESSAGE##', ''));
							core.message.show_notice('', message_arr);
						}

						if (options.success) {
							switch (typeof options.success) {
								case 'string' : eval(options.success + '(data, options);'); break;
								case 'function' : options.success.apply(this, [data, options]); break;
							}
						}
						else {
							if (options.update && options.update !== "false")	{

							    if(core.workspace.is_system) options.autoscroll = false;
							    if($(options.update).closest('.modal').length) options.autoscroll = false;

								$(options.update).find('object.swfupload').each(function() {
									swfobject.removeSWF($(this)[0].id);
								});

								$('body .tooltip').remove();

								if (options.hidden_update) {
									$(options.update).parent().css({ 'height': ($(options.update).parent().outerHeight())});
									$(options.update)
										.hide()
										.html(data)
										.show();
									$(options.update).parent().css({ 'height': ''});
								} else {

								    if(options.loader_html){
								        setTimeout(function () {
                                            $(options.update).html(data);
                                            if(options.autoscroll) core.browser.scrollTo(options.update, {offset: options.autoscroll_offset});

                                        }, 500)
                                    }else{
								        $(options.update).html(data);
								        if(options.autoscroll) core.browser.scrollTo(options.update, {offset: options.autoscroll_offset});
                                    }
								}
							}
						}

						if (options.done) {
							switch (typeof options.done) {
								case 'string' : eval(options.done + '(data, options);'); break;
								case 'function' : options.done.apply(this, [data, options]); break;
							}
						}

					},
					complete: function(d) {

						let data = d.responseJSON;

						if (!options.no_overlay && options.update) core.overlay.hide();

						if (options.complete) {
                            switch (typeof options.complete) {
                            case 'string':
                                eval(options.complete + '(data, options);');
                                break;
                            case 'function':
                                options.complete.apply(this, [data, options]);
                                break;
                            }
                        }
					}
				});
			}

			// confirm dialog
			if (options.confirm) {
				core.browser.confirm(options.confirm, function() {
					if (options.ok_confirm) options.ok_confirm();
					do_ajax_request();
				}, options.confirm_cancel);
			}
			else do_ajax_request();

			// return;
			return $ajax;
		},
		//--------------------------------------------------------------------------------
		request_function: function(url, func, options) {
	    	// options
	    	var options = $.extend({
				success: func
	    	}, (options == undefined ? {} : options));

	    	// ajax
    		return core.ajax.request(url, options)
		},
		//--------------------------------------------------------------------------------
		request_update: function(url, el, options) {
	    	// options
	    	var options = $.extend({
		   		update: el
			}, (options == undefined ? {} : options));

	    	// ajax
    		return core.ajax.request(url, options)
		},
		//-------------------------------------------------------------------------
        process_response: function (response, oncomplete) {

            core.util.trigger_tooltip();

            if (response.js) eval(response.js);
            if (response.alert) core.browser.alert(response.alert, (response.title ? response.title : "Alert"), {ok_callback: new Function(response.ok_callback)});
            if (response.message) core.browser.alert(response.message, (response.title ? response.title : "Alert"), {
                ok_callback: new Function(response.ok_callback),
                class: 'custom'
            });
            if (response.notice) {
                if(!response.title) response.title = "Notice";
                if(!response.notice_color) response.notice_color = "white";
                if(!response.notice_background) response.notice_background = "info";
                core.message.show_notice(response.title, response.notice, 2000, {background: response.notice_background, color:response.notice_color});
            }
            if (response.redirect) {
                core.overlay.show();
                document.location = response.redirect;
            }
            if (response.refresh) document.location.reload();
            if (response.popup) core.browser.popup(response.popup);

            oncomplete = (oncomplete === undefined ? function () {
            } : oncomplete);

            setTimeout(function () {
                oncomplete();
            }, 100)

        }
	},
	//--------------------------------------------------------------------------------
	overlay: {
		//--------------------------------------------------------------------------------
		show: function(id, overlay_id) {
			let el = $(".page-loader-overlay");
			if(el.length) el.show();
		},
		//--------------------------------------------------------------------------------
		hide: function(id) {
			let el = $(".page-loader-overlay");
			if(el.length) el.fadeOut();
		}
	},
	//--------------------------------------------------------------------------------
	form: {
		//--------------------------------------------------------------------------------
        /**
         *
         * @param dateString
         * @returns {Date}
         */
        php_date_to_js_date_obj: function (dateString) {

            var dateParts = dateString.split("-");

            // month is 0-based, that's why we need dataParts[1] - 1
            var dateObject = new Date(+dateParts[0], dateParts[1], +dateParts[2]);

            return dateObject;
        },
        //--------------------------------------------------------------------------------
        /**
         *
         * @param date
         * @returns {string}
         */
        parse_js_date: function (date) {
            var month = '' + (date.getMonth() + 1),
                day = '' + date.getDate(),
                year = date.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        },
        //--------------------------------------------------------------------------------
        /**
         * @param dateCheck
         * @param dateFrom
         * @param dateTo
         * @returns {boolean}
         */
        date_is_between: function (dateCheck, dateFrom, dateTo) {

            let from = this.php_date_to_js_date_obj(dateFrom);  // -1 because months are from 0 to 11
            let to = this.php_date_to_js_date_obj(dateTo);
            let check = this.php_date_to_js_date_obj(dateCheck);

            return check >= from && check <= to;

        },
		//-------------------------------------------------------------------------
        test_password_strength: function (password_str) {
            var strength = 0;

            strength += /[A-Z]+/.test(password_str) ? 1 : 0;
            strength += /[a-z]+/.test(password_str) ? 1 : 0;
            strength += /[0-9]+/.test(password_str) ? 1 : 0;
            strength += /[\W]+/.test(password_str) ? 1 : 0;

            switch (strength) {
                case 3:
                    return 2;
                    break;
                case 4:
                    return 3;
                    break;
                default:
                    return 1;
                    break;
            }
        },
        //--------------------------------------------------------------------------------
        append_hidden_element: function (form, id, value) {
            var i = document.createElement('input'); //input element, hidden
            i.setAttribute('id', id);
            i.setAttribute('name', id);
            i.setAttribute('value', value);
            i.setAttribute('type', 'hidden');
            form.append(i);
        },
        //--------------------------------------------------------------------------------
        clear_fields: function (options) {

        	// options
	    	options = $.extend({
		   		form_target: ''
			}, (options === undefined ? {} : options));

	    	let form_prefix = options.form_target !== '' ? options.form_target+' ' : '';

            $(form_prefix+"input[type=password], "+form_prefix+"input[type=text], "+form_prefix+"input[type=email], textarea").each(function () {
                let element = $(this);
                let val = "";
                if (element.attr('defaultvalue') != undefined) val = element.attr('defaultvalue');
                element.val(val);
            })
            $(form_prefix+"select").each(function () {
                let element = $(this);
                let val = element.find('option:first').val();
                if (element.attr('defaultvalue') != undefined) val = element.attr('defaultvalue');
                element.val(val);
                element.change();
            })

            $(form_prefix+".input-group.icon-right i.fa").removeClass("fa-check bg-green-lighter color-success fa-times bg-red-lighter color-danger");

            core.form.reset_validate_fields();
            $('label.error').remove();
            $(form_prefix+'input').removeClass("error valid is-invalid");
            $(form_prefix+'select').removeClass("error valid is-invalid");

            $(form_prefix+'input[type=checkbox]').prop('checked', false);

            if ($('.selectpicker').length) {
                $('.selectpicker').selectpicker('deselectAll');
            }
        },
        //--------------------------------------------------------------------------------
        reset_validate_fields: function () {
            $('.is-invalid').removeClass('is-invalid');
        },
		//--------------------------------------------------------------------------------
        clear_form_fields: function ($id) {
        	if(!$id) $id = "body";

            $($id+" .is-invalid").removeClass('is-invalid');

			$($id+" input, "+$id+" textarea").each(function(){
                let element = $(this);
                let val = "";
                if(element.attr('defaultvalue') !== undefined) val = element.attr('defaultvalue');
                element.val(val);
            });

            $($id+" select").each(function(){
                let element = $(this);
                let val = element.find('option:first').val();
                if(element.attr('defaultvalue') !== undefined) val = element.attr('defaultvalue');
                element.val(val);
                element.change();
            });
        },
		//--------------------------------------------------------------------------------
		set_button_loading:function(target, options){

			// options
	    	options = $.extend({
		   		html: 'Loading... <i class=\'fas fa-spinner fa-spin\'></i>'
			}, (options == undefined ? {} : options));

			if(!core.workspace.loading_button){
				core.workspace.loading_button = [];
			}

			$(function(){
				$(target).each(function(){
					let el = $(this);

					if(el.hasClass('loading')) {
						return;
					}

					let accessor = core.string.get_random_id({prepend:"accessor"});
					core.workspace.loading_button.push({
						accessor : accessor,
						html : el.html(),
					});
					el.html(options.html);
					el.prop('disabled', true);
					el.attr('data-accessor', accessor);
					el.addClass("loading");
				});
			});
		},
		//--------------------------------------------------------------------------------
		unset_button_loading:function(target){

			if(!core.workspace.loading_button){
				core.workspace.loading_button = [];
			}

			$(target).each(function(){
				let el = $(this);
				let accessor = el.data('accessor');

				let result = core.workspace.loading_button.filter(x => x.accessor === accessor);

				$.each( result, function( key, value ) {
					el.html(value.html);
					el.prop('disabled', false);
					el.removeAttr('data-accessor');
					el.removeClass("loading");
				});
			});
		},
		//--------------------------------------------------------------------------------
		serialize_input: function(selector) {
			// build data from all forms
			var data_arr = [];
			$(selector).each(function(input_index, input_item) {
				// prams
				var $input_item = $(input_item);

				// do not serialze element with nosubmit attribute
				if ($input_item.attr('nosubmit')) return true;

				let id = $input_item.attr('name');
				if(!id) id = $input_item.attr('id');

				// make sure we have an id
				if (!id) return true;


				// serialized based on type
				switch ($input_item.prop('type')) {
					case 'hidden' :
					case 'text'	:
					case 'color'	:
					case 'number'	:
					case 'password' :
					case 'select-one' :
						data_arr.push(id + '=' + encodeURIComponent($input_item.val()));
						break;

					case 'textarea' :
						var value = $input_item.val();

						// ie9 textarea html fix
						//if (document.addEventListener && !window.requestAnimationFrame) value = $input_item.html();

						data_arr.push(id + '=' + encodeURIComponent(value));
						break;

					case 'checkbox'	:
					case 'radio' :
						let name = $input_item.attr('name');
						if(!name) name = $input_item.attr('id');
						if ($input_item.prop('checked')) data_arr.push(name + '=' + encodeURIComponent($input_item.val()));
						break;

					case 'select-multiple' 	:
						$input_item.find('option').each(function(option_index, option_item) {
							var $option_item = $(option_item);
							if ($option_item.prop('selected')) data_arr.push($input_item.attr('id') + '[]=' + encodeURIComponent($option_item.val()));
						});
						break;
				}
			});

			return data_arr.join('&');
		},
		//--------------------------------------------------------------------------------
		serialize: function(form_arr) {
			// params
			var form_arr = ($.isArray(form_arr) ? form_arr : [form_arr]);

			// build data from all forms
			var data_arr = [];
			$.each(form_arr, function(form_index, form_item) {
				// build data from all input elements in form
				var $form_item = $(form_item);
				var input_arr = $form_item.find('input,select,textarea');
				$.each(input_arr, function(input_index, input_item) {
					var $input_item = $(input_item);
					data_arr.push(core.form.serialize_input($input_item));
				});
			});

			return data_arr.join('&');
		},
		//--------------------------------------------------------------------------------
		is_complex_password: function(text, min_length, require_number, require_letter, require_uppercase_letter, require_special, not_contain) {
			// params
			var min_length = (min_length == undefined ? 8 : min_length);

			// init
			var message_arr = [];

			// check if the text is at least the minimum length
			if (text.length < min_length) message_arr.push('Must be at least ' + min_length + ' characters');

			// check if the text contains a number
			if (require_number && !(/[0-9]/i).test(text)) message_arr.push('Must contain at least one number');

			// check if the text contains a character
			if (!require_uppercase_letter && require_letter && !(/[a-z]/i).test(text)) message_arr.push('Must contain at least one letter');

			// check if the text contains lowercase and uppercase letters
			if (require_uppercase_letter && require_letter && !(/[a-z]/).test(text)) message_arr.push('Must contain at least one lowercase letter');
			if (require_uppercase_letter && require_letter && !(/[A-Z]/).test(text)) message_arr.push('Must contain at least one uppercase letter');

			// check if the text contains a special character
			if (require_special && !(/[!@#\$%\^&\*\-_\+=\.\?\<\>]/i).test(text)) message_arr.push('Must contain at least one of the following: !@#$%^&*-_+=.?<>');

			// check if the text does not contain certain phrases
			if (not_contain) {
				var regex = new RegExp(not_contain, 'ig');
				if ((regex).test(text)) message_arr.push('Must not contain the phrase \'' + not_contain + '\'');
			}

			// return messages if the text is not a complex password, otherwise bool true
			return (message_arr.length ? message_arr : true);
		},
		//--------------------------------------------------------------------------------
		count_checked: function(id_prefix, checked) {
			// params
			var checked = (checked == undefined ? true : checked);

			// init
			var count = 0;

			// count checkboxes
			var checkbox_arr = $('input[id^=' + id_prefix + '\\[]');
			$.each(checkbox_arr, function(checkbox_index, checkbox_item) {
				var $checkbox_item = $(checkbox_item);
				if ($checkbox_item.prop('checked') == checked) count++;
			});

			// end
			return count;
		},
		//--------------------------------------------------------------------------------
        max_checked: function(group, max) {
            // tests for maximum number checked checkboxes in specific range, returns true if maximum have been reached
            var count = 0;

			$('input[group=' + group + ']:checked').each(function(index, item) {
				count++;
			});

			return((count > max) ? true : false);
        },
		//--------------------------------------------------------------------------------
		select_all: function(id) {
			var $id = $(id);
			$id.find('option:not(:selected)').each(function(index, item) {
				var $item = $(item);
				$item.prop('selected', true);
			});
			$id.focus();
		},
		//--------------------------------------------------------------------------------
		select_none: function(id) {
			var $id = $(id);
			$id.find('option:selected').each(function(index, item) {
				var $item = $(item);
				$item.removeAttr('selected');
			});
			$id.focus();
		},
		//--------------------------------------------------------------------------------
		populate_select: function(url, id, options) {
			// options
			var options = $.extend({
				// ajax options
				no_overlay: true,
				enable_reset_html:false,
				autoscroll:false,
				form: false,

				// custom options
				selected_index: 0
			}, (options == undefined ? {} : options));

			// method
			if (!options.method) {
				options.method = (options.form ? 'POST' : 'GET');
			}

			// show busy message in select
			var $id = $(id);
			$id.find('option')
				.remove()
				.end()
				.append('<option value="0">Please wait, fetching data...</option>');

			// option: sucess
			options.success = function(data) {
				// remove message
				$id.find('option').remove();

				// add options
				$.each(data, function(data_index, data_item) {
					$('<option value="' + data_index + '">' + data_item + '</option>').appendTo($id);
				});

				// sort options
				$id.find('option').sort(function(option1, option2) {
					return (option1.innerHTML > option2.innerHTML ? 1 : -1);
				}).appendTo($id);

				// select default option
				if (options.selected_index) $id.val(options.selected_index);
				else $id.val($(id+" option:first").val());
			}

			// fetch and populate new values
			return core.ajax.request(url, options);
		},
	 	//--------------------------------------------------------------------------------
		populate_input: function(url, options) {
			// options
			var options = $.extend({
				no_overlay: true
			}, (options == undefined ? {} : options));

			return core.ajax.request(url, {
				method: 'GET',
				no_overlay: options.no_overlay,
				success: function(data) {
					$.each(data, function(data_index, data_item) {
						data_index = data_index.replace('[', '\\[');
						data_index = data_index.replace(']', '\\]');
						$('#' + data_index).val(data_item);
					});
				}
			});
		},
		//--------------------------------------------------------------------------------
	 	limit_numeric: function(id) {
			// limit for [0-9-]
			var $id = $(id);
			$id.val($id.val().replace(/[^0-9\-]/ig, ''));
		},
		//--------------------------------------------------------------------------------
		limit_alpha_numeric: function(id) {
			// limit for [a-zA-Z0-9-]
			var $id = $(id);
			$id.val($id.val().replace(/[^a-zA-Z0-9\-]/ig, ''));
		},
		//--------------------------------------------------------------------------------
	 	limit_numeric_positive: function(id) {
			// limit for [0-9]
			var $id = $(id);
			$id.val($id.val().replace(/[^0-9]/ig, ''));
		},
		//--------------------------------------------------------------------------------
		limit_fraction: function(id) {
			// limit for [0-9.-]
			var $id = $(id);
			$id.val($id.val().replace(/[^0-9\.\-]/ig, ''));
		},
		//--------------------------------------------------------------------------------
		limit_alphanumeric: function(id) {
			// limit for [a-zA-Z0-9-]
			var $id = $(id);
			$id.val($id.val().replace(/[^a-zA-Z0-9\-]/ig, ''));
		},
		//--------------------------------------------------------------------------------
		limit_alpha: function(id) {
			// limit for [a-zA-Z]
			var $id = $(id);
			$id.val($id.val().replace(/[^a-zA-Z]/ig, ''));
		},
		//--------------------------------------------------------------------------------
		limit_year: function(id) {
			// limit for [0-9]
			var $id = $(id);
			$id.val($id.val().replace(/[^0-9]/ig, ''));
		},
		//--------------------------------------------------------------------------------
		limit_email: function(id) {
			// limit for [[a-zA-Z0-9@.-_]
			var $id = $(id);
			$id.val($id.val().replace(/[^a-zA-Z0-9@\.\-_]/ig, ''));
		},
		//--------------------------------------------------------------------------------
		change_selected_text: function(id, text, prefix, suffix) {
			// params
			var $id = $(id);
			var text = (text == undefined ? false : text);
			var prefix = (prefix == undefined ? '' : prefix);
			var suffix = (suffix == undefined ? '' : suffix);

			// focus element
			$id.focus();

			// ie
			if (document.selection) {
				var text_selection = document.selection.createRange();
				text_selection.text = prefix + (text == false ? text_selection.text : text) + suffix;
			}
			// mozilla
			else {
				var text_selection_start = $id.prop('selectionStart');
				if (text_selection_start || text_selection_start == '0') {
					var text_selection_end = $id.prop('selectionEnd');
					var text_selection = $id.val().substring(text_selection_start, text_selection_end);

					$id.val($id.val().substring(0, text_selection_start) + prefix + (text == false ? text_selection : text) + suffix + $id.val().substring(text_selection_end, $id.val().length));
				}
				else $id.val($id.val() + prefix + (text == false ? '' : text) + suffix);
			}
		},
		//--------------------------------------------------------------------------------
		submit: function(form, options) {
			// options
			var options = $.extend({
				url: false,
				cid: false,
			}, (options == undefined ? {} : options));

			// validate
			if (options.cid) {
				if (!options.cid.validate()) return false;
            }

			// url
			if (options.url) {
                form.action = options.url;
            }

			// submit
			form.submit();
		}
		//--------------------------------------------------------------------------------
	},
	//--------------------------------------------------------------------------------
	bit: {
		//--------------------------------------------------------------------------------
		set: function(bit, bit_value, state) {
			state = (state == undefined ? true : state);
			bit = parseInt(bit);
			bit_value = parseInt(bit_value);

			if (state) return (bit_value | bit);
			else return (bit_value & ~bit);
		}
	},
	//--------------------------------------------------------------------------------
	message: {
		//--------------------------------------------------------------------------------
		is_notice_created: false,
		is_static_created: false,
		//--------------------------------------------------------------------------------
		show_notice: function(title, message_arr, delay, options) {
			// options
			var options = $.extend({
				background: "info",
				color: "white",
				tiny: true,
			}, (options == undefined ? {} : options));

			// params
			var message_arr = ($.isArray(message_arr) ? message_arr : [message_arr]);
			if(!message_arr && title) message_arr = title;

			// toast wrapper
			var dom_toast = $('#ui_toast');
			if (!dom_toast.length) {
				$('<div>', {
					'id': 'ui_toast',
					'class': 'ui-toast',
					'aria-live' : 'polite',
					'aria-atomic' : 'true'
				}).prependTo('body');
			}

			// build message
			var message = '';
			$.each(message_arr, function(index, item) {
				message += item + '<br />';
			});

			// close button
			var dom_close_button = $('<button>', {
				'type': 'button',
				'class' : 'btn-close',
				'data-bs-dismiss' : 'toast',
				'aria-label' : 'Close',
				'html' : '<span aria-hidden="true">&times;</span>',
			})[0];

			// show message
			$('<div>', {
				'class': 'toast align-items-center bg-'+options.background+ ' text-'+options.color,
				'role': 'alert',
				'aria-live': 'assertive',
				'aria-atomic': 'true',
				'data-bs-delay': delay ? delay : 2000
			})
			.html($(
				'<div class="d-flex">'+
					'<div class="toast-body">'+
						message +
					'</div>'+
					'<button type="button" class="btn-close btn-close-'+options.color+' me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'+
				'</div>'
			))
			.appendTo('#ui_toast')

			.on('hidden.bs.toast', function () {
				$(this).remove();
			})
			.toast('show');
		}
	},
	//--------------------------------------------------------------------------------
	browser: {
		window_count: 0,
		popup_count: 0,
		popup_id_arr: [],
		//--------------------------------------------------------------------------------
		load_script: function(url, options) {

			var imported = document.createElement('script');
			imported.src = url;
			document.head.appendChild(imported);

		},
		//--------------------------------------------------------------------------------
		redirect: function(url, options) {

			core.overlay.show();

			document.location = url;

		},
		//--------------------------------------------------------------------------------
		scroll_bottom: function() {
			$('html, body').animate({ scrollTop: $(document).height() }, 1000);
		},
		//--------------------------------------------------------------------------------
		popup: function(url, options) {
			// options
			var options = $.extend({
				width: 'modal-lg',
				height: 'auto',
				backdrop: true, // true | false | 'static'
				closable: true,
				id: core.data.generate_unique_id('popup'),
				dirty_check: false,
				hide_header: !core.workspace.is_system,
				hide_footer: false,
				enable_loading_content: true,
				loading_content: false,
				title: '',
				class_modal_content: '',
				height_class: 'min-h-40vh',
				fade_class: 'fade-in',
				dirty_check_msg: 'Are you sure you want to continue? Changes made have not been saved and will be lost, do you want to proceed?'
			}, options);

			if(options.height_class){
				options.class_modal_content += " "+options.height_class
			}

			if(options.enable_loading_content && !options.loading_content){
				options.loading_content = '' +
					'<div class="text-center panel-loader '+options.height_class+' d-flex align-items-center justify-content-center">' +
						'<div class="spinner-grow mx-1 spinner-grow-sm text-muted"></div>' +
						'<div class="spinner-grow mx-1 spinner-grow-sm text-primary"></div>' +
						'<div class="spinner-grow mx-1 spinner-grow-sm text-success"></div>' +
						'<div class="spinner-grow mx-1 spinner-grow-sm text-info"></div>' +
						'<div class="spinner-grow mx-1 spinner-grow-sm text-warning"></div>' +
						'<div class="spinner-grow mx-1 spinner-grow-sm text-danger"></div>' +
						'<div class="spinner-grow mx-1 spinner-grow-sm text-secondary"></div>' +
						'<div class="spinner-grow mx-1 spinner-grow-sm text-muted"></div>' +
					'</div>';
			}

			let p = new popup(options);
			if(options.enable_loading_content){
				p.set_body_content(options.loading_content);
			}
			p.on_show(function(e){
				setTimeout(function(){
					core.ajax.request(url, {
						data:{ mid: options.id },
						no_overlay:true,
						autoscroll:false,
						method: 'GET',
						beforeSend: function(response){
							// $('#' + options.id + ' .modal-content').addClass(options.height_class);
						},
						success: function(response){
							$('#' + options.id + ' .modal-body').addClass(options.fade_class).html(response);
						},
					});
				}, 500);
			});

			return p.build({
				backdrop:options.backdrop,
			});

		},
		//--------------------------------------------------------------------------------
		close_all_modals: function(options){

			options = $.extend({
				delay: 200,
	    	}, (options === undefined ? {} : options));

			let time = 0;

			$($('.modal').get().reverse()).each(function() {
				let el = $(this);
				setTimeout( function(){
					el.modal('hide');
				}, time)
				time += options.delay;
			});

			core.browser.popup_id_arr = [];


		},
		//--------------------------------------------------------------------------------
		toggle_popup: function(url, options){

			core.overlay.show();

			core.browser.close_popup();

			setTimeout(function () {
				core.browser.popup(url, options);
			}, 300);
		},
		//--------------------------------------------------------------------------------
		close_modal: function(mid){
			$(mid).modal('hide');
		},
		//--------------------------------------------------------------------------------
		modal: function(url, options){

			var options = $.extend({
				id: core.data.generate_unique_id('modal'),
				no_overlay: false,
				class: false,
				beforeSend: function(){},
				complete: function(){},
	    	}, (options == undefined ? {} : options));

			//append to url
			url += "&mid="+options.id

			//show overlay
			if(!options.no_overlay)
				core.overlay.show();

			let existing_backdrop = $('.modal-backdrop');
			let existing_modal = $('.modal');
			let total_modals = $('.modal').length + 1;
			let gap_top = total_modals * 1.75;

			core.browser.load_content(url, function(){
				
				$('#'+options.id)
				.on('show.bs.modal', function (event) {
					let $this = $(this);
					$this.find(".modal-dialog").css("margin", gap_top+"rem auto");

					if(existing_backdrop.length) existing_backdrop.css('z-index', '1029');
					if(existing_modal.length) existing_modal.css('z-index', '1039');
					if(options.class) $this.addClass(options.class);

					if(options.beforeSend)
						options.beforeSend.apply(this, [$this, options]);
				})
				.on('shown.bs.modal', function (event) {
					let $this = $(this);
					core.browser.popup_id_arr.push(options.id);

					if(options.complete)
						options.complete.apply(this, [$this, options]);
				})
				.on('hide.bs.modal', function(e) {
					if(existing_backdrop.length) existing_backdrop.css('z-index', '1039');
					if(existing_modal.length) existing_modal.css('z-index', '1049');
				})
				.on('hidden.bs.modal', function (event) {
					core.browser.popup_id_arr.pop();
					$(this).remove();
				}).modal('show');

				core.overlay.hide();
			}, options);
		},
		//--------------------------------------------------------------------------------
		modal_switch: function(url, options){

			options = $.extend({
				delay:200
	    	}, (options === undefined ? {} : options));

			core.overlay.show();

			core.browser.close_popup();

			setTimeout(function () {
				core.browser.popup(url, options);
			}, options.delay);

		},
		//--------------------------------------------------------------------------------
		load_content: function(url, success, options){

			var options = $.extend({
				method:'GET',
	    	}, (options == undefined ? {} : options));


			core.ajax.request_function(url, function(response){
				$('body').append(response);

				if (success){
					switch (typeof success) {
						case 'string' : eval(success + '(data, options);'); break;
						case 'function' : success.apply(this, [response, options]); break;
					}
				}

			}, options)
		},
		//--------------------------------------------------------------------------------
		close_popup: function() {
			var last_popup_id = core.browser.popup_id_arr[core.browser.popup_id_arr.length - 1];
			try {
				$('#' + last_popup_id).modal('hide');
				$('*[title]').tooltip('hide');
			}catch (e) {}
		},
		//--------------------------------------------------------------------------------
		new_window: function(url, width, height, position, scrollbars) {
		  	// params
		  	var width = (width == undefined ? 'max' : width);
		  	var height = (height == undefined ? 'max' : height);
		  	var position = (position == undefined ? 'none' : position);
		  	var scrollbars = (scrollbars == undefined ? 'yes' : scrollbars);

		    // keep track of how many popups where opened
		    core.browser.window_count++;

			// convert % to value
			if (width.match(/%/i)) width = window.screen.width * parseInt(width.replace(/%/i, '')) / 100;
			if (height.match(/%/i)) height = window.screen.height * parseInt(height.replace(/%/i, '')) / 100;

		    // convert 'max' meta to maximum value
		    if (width == 'max') width = window.screen.width;
		    if (height == 'max') height = window.screen.height - 120;

			// move window to specified position
			var top = 0;
			var left = 0;
		    if (position != 'none') {
				var top_offset = (window.screenTop != undefined ? window.screenTop : window.screen.top);
				var left_offset = (window.screenLeft != undefined ? window.screenLeft : window.screen.left);

				var width_max = window.screen.width;
				var height_max = (window.screen.height - 120);

				switch (position) {
			      	case 'right'  :
						left = Math.floor(width_max - width);
						break;

			      	case 'center' :
			      		top = Math.floor((height_max / 2) - (height / 2)) + top_offset;
			      		left = Math.floor((width_max / 2) - (width / 2)) + left_offset;
			    		break;
			    }
		    }

		    // create window
		    return window.open(url, 'new_window' + core.browser.window_count, 'toolbar=no,status=yes,resizable=yes,directories=no,location=no,menubar=no,scrollbars=' + scrollbars + ',width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
		},
		//--------------------------------------------------------------------------------
		alert: function(message, title, options) {

			options = $.extend({
				id: core.string.get_random_id({prepend: 'modal',}),
				width: 'modal-md',
				closable: false,
				backdrop: 'static',
				class: 'ui-alert',
				ok_callback: function(){},
			}, (options === undefined ? {} : options));

			if(!title) title = "Alert";

			let $popup = new popup(options);
			$popup.set_title(title);
			$popup.set_body_content(message);
			$popup.set_footer_content('<button type="button" class="btn btn-primary" commodal-btn="ok" data-bs-dismiss="modal">Ok</button>');
			$popup.on_shown(function(){
				let $this = $(this);
				$this.find('button[commodal-btn=ok]')
					.click(function() {
						if (options.ok_callback !== undefined) options.ok_callback.apply(this, []);
					}).focus();
				core.overlay.hide();
			});
			$popup.build();
			return $popup;

		},
		//--------------------------------------------------------------------------------
		confirm: function(message, ok_callback, cancel_callback, options) {

			var options = $.extend({
				width: 'modal-md',
				title: 'Confirm',
				class: 'ui-confirm',
			}, (options == undefined ? {} : options));

			var title = options.title;
			ok_callback = (ok_callback === undefined ? function() {} : ok_callback);
			cancel_callback = (cancel_callback === undefined ? function() {} : cancel_callback);

			let alert = new popup(options);
			alert.set_title(title);
			alert.set_body_content(message);
			alert.set_footer_content('<button class="btn btn-primary" commodal-btn="ok" data-bs-dismiss="modal">Ok</button><button class="btn btn-secondary" commodal-btn="cancel" data-bs-dismiss="modal">Cancel</button>');
			alert.on_show(function(){
				var $this = $(this);
				$this.find('button[commodal-btn=ok]')
					.click(function() {
						if (ok_callback) ok_callback.apply(this, []);
					}).focus();

				$this.find('button[commodal-btn=cancel]').click(function() {
					if (cancel_callback) cancel_callback.apply(this, []);
				});
			});
			return alert.build();
		},
		//--------------------------------------------------------------------------------
		move_popup_body_header_to_title: function() {
			if (!core.browser.popup_id_arr) return;

			var last_popup_id = core.browser.popup_id_arr[core.browser.popup_id_arr.length - 1];
			var $modal_title = $('#' + last_popup_id + ' .modal-title');
			var $popup_header = $('#' + last_popup_id + ' .modal-body h2').first();

			if (!$modal_title.html() && $popup_header.html()) {
				$modal_title.html($popup_header.html());
			}

			if ($modal_title.html() == $popup_header.html()) {
				$popup_header.remove();
			}
		},
		//--------------------------------------------------------------------------------
        setUrl: function (url, title) {

            if (!title) title = document.title;

            const nextState = {additionalInformation: 'Updated the URL with JS'};
            window.history.replaceState(nextState, title, url);
        },

        //-------------------------------------------------------------------------
        scrollToTop: function (options) {
            core.browser.scrollTo('html', {offset: -150,});
        },
        //-------------------------------------------------------------------------
        scrollToBottom: function (target, options) {

            var options = $.extend({
                offset: 0,
            }, (options == undefined ? {} : options));

            if(!target) target = "html";

            let el = target;
            if (!target instanceof jQuery)
                el = $(target);

           target.animate({ scrollTop: target.prop("scrollHeight") + options.offset}, 1000);
        },
        //-------------------------------------------------------------------------
        scrollTo: function (scrollto_element, options) {

            var options = $.extend({
                scrollable_element: 'html, body',
                offset: 0,
            }, (options == undefined ? {} : options));

            if (!scrollto_element) scrollto_element = "body";

            let el = $(options.scrollable_element);
            if (options.scrollable_element instanceof jQuery)
                el = options.scrollable_element;

            el.animate({
                scrollTop: $(scrollto_element).offset().top + options.offset
            }, 200);
        },
		//--------------------------------------------------------------------------------
	},
	//--------------------------------------------------------------------------------
	event: {
		//--------------------------------------------------------------------------------
		is_control: function(event) {
			var code = event.keyCode || event.which;
			return ($.inArray(code, [8,9,13]) != -1)
		},
		//--------------------------------------------------------------------------------
		is_numeric: function(event) {
			// test for [0-9-]
			var code = event.keyCode || event.which;
			if (core.event.is_control(event)) return true;
			return ((code >= 48 && code <= 57) || code == 45);
		},
		//--------------------------------------------------------------------------------
		is_year: function(event) {
			// test for [0-9]
			var code = event.keyCode || event.which;
			if (core.event.is_control(event)) return true;
			return (code >= 48 && code <= 57);
		},
		//--------------------------------------------------------------------------------
		is_numeric_positive: function(event) {
			// test for [0-9]
			var code = event.keyCode || event.which;
			if (core.event.is_control(event)) return true;
			return (code >= 48 && code <= 57);
		},
		//--------------------------------------------------------------------------------
		is_fraction: function(event) {
			// test for [0-9.]
			var code = event.keyCode || event.which;
			if (core.event.is_control(event)) return true;
			return ((code >= 48 && code <= 57) || code == 46 || code == 45);
		},
		//--------------------------------------------------------------------------------
		is_alpha_numeric: function(event) {
			// test for [a-zA-Z0-9]
			var code = event.keyCode || event.which;
			if (core.event.is_control(event)) return true;
			return ((code >= 48 && code <= 57) || (code >= 65 && code <= 90) || (code >= 97 && code <= 122) || code == 45);
		},
		//--------------------------------------------------------------------------------
		is_alpha: function(event) {
			// test for [a-zA-Z]
			var code = event.keyCode || event.which;
			if (core.event.is_control(event)) return true;
			return ((code >= 65 && code <= 90) || (code >= 97 && code <= 122));
		},
		//--------------------------------------------------------------------------------
		is_email: function(event) {
			// test for [a-zA-Z0-9@.-_]
			var code = event.keyCode || event.which;
			if (core.event.is_control(event)) return true;
			return ((code >= 48 && code <= 57) || (code >= 64 && code <= 90) || (code >= 97 && code <= 122) || code == 95 || code == 45 || code == 46);
		}
		//--------------------------------------------------------------------------------
	},
	//--------------------------------------------------------------------------------
	number: {
		//--------------------------------------------------------------------------------
		format_kbmb: function(bytes) {
			if (bytes < 1024) return bytes + ' bytes';
			if (bytes < 1048576) return Math.round(bytes / 1024) + 'kb';
			return Math.round(bytes / 1048576) + 'mb';
		}
	},
	//--------------------------------------------------------------------------------
	data:{
		generate_unique_id:function(prefix){
			return core.string.get_random_id({prepend:prefix})
		}
	},
	//--------------------------------------------------------------------------------
	string: {
		//-------------------------------------------------------------------------
		formatCurrency: function(number, options){
			return core.util.formatCurrency(number, options);
		},
		//-------------------------------------------------------------------------
        get_random_id: function (options) {
        	options = $.extend({
				prepend: '',
				length: 5,
				lowercase: true,
				glue: "_",
			}, (options === undefined ? {} : options));

			let result = '';
			let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			let charactersLength = characters.length;

			for (let i = 0; i < options.length; i++) {
				result += characters.charAt(Math.floor(Math.random() * charactersLength));
			}

			if(options.lowercase) result = result.toLowerCase();
			if(options.prepend !== '') result = options.prepend + options.glue + result;

			return result;
        },
		//--------------------------------------------------------------------------------
		uc_first: function(text) {
			return text.charAt(0).toUpperCase() + text.slice(1);
		},
		//--------------------------------------------------------------------------------
		extension: function(filename) {
			return filename.substr((filename.lastIndexOf('.') + 1));
		},
		//--------------------------------------------------------------------------------
		clean_msword_input: function(input) {
			  // 1. remove line breaks / Mso classes
			  var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
			  var output = input.replace(stringStripper, ' ');
			  // 2. strip Word generated HTML comments
			  var commentSripper = new RegExp('<!--(.*?)-->','g');
			  var output = output.replace(commentSripper, '');
			  var tagStripper = new RegExp('<(/)*(img|meta|link|span|\\?xml:|st1:|o:|font)(.*?)>','gi');
			  // 3. remove tags leave content if any
			  output = output.replace(tagStripper, '');
			  // 4. Remove everything in between and including tags '<style(.)style(.)>'
			  var badTags = ['style', 'script','applet','embed','noframes','noscript'];

			  for (var i=0; i< badTags.length; i++) {
			    tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
			    output = output.replace(tagStripper, '');
			  }
			  // 5. remove attributes ' style="..."'
			  var badAttributes = ['style', 'start'];
			  for (var i=0; i< badAttributes.length; i++) {
			    var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
			    output = output.replace(attributeStripper, '');
			  }
			  return output;
		},
		//--------------------------------------------------------------------------------
		clean_wysiwyg_paste: function(input) {
			  // 1. remove line breaks / Mso classes
			  var stringStripper = /(\n|\r| class=(")?Mso[a-zA-Z]+(")?)/g;
			  var output = input.replace(stringStripper, ' ');
			  // 2. strip Word generated HTML comments
			  var commentSripper = new RegExp('<!--(.*?)-->','g');
			  var output = output.replace(commentSripper, '');
			  var tagStripper = new RegExp('<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>','gi');
			  // 3. remove tags leave content if any
			  output = output.replace(tagStripper, '');
			  // 4. Remove everything in between and including tags '<style(.)style(.)>'
			  var badTags = ['script','applet','embed','noframes','noscript'];

			  for (var i=0; i< badTags.length; i++) {
			    tagStripper = new RegExp('<'+badTags[i]+'.*?'+badTags[i]+'(.*?)>', 'gi');
			    output = output.replace(tagStripper, '');
			  }
			  // 5. remove attributes ' style="..."'
			  var badAttributes = ['start'];
			  for (var i=0; i< badAttributes.length; i++) {
			    var attributeStripper = new RegExp(' ' + badAttributes[i] + '="(.*?)"','gi');
			    output = output.replace(attributeStripper, '');
			  }
			  return output;
		},
		//--------------------------------------------------------------------------------
	},
	//--------------------------------------------------------------------------------
	jquery: {
		register_plugin: function(setup) {
			(function($) {
				$.fn[setup.name] = function(fn) {
					// call the function if it is available within the plugin
					if (setup.functions[fn]) {
						return setup.functions[fn].apply(this, Array.prototype.slice.call(arguments, 1));
					}

					// construct the plugin if we received options or no function
					if (typeof fn === 'object' || !fn) {
						return setup.functions.init.apply(this, arguments);
					}

					// the function was not found within the plugin
					$.error('Function [' + setup.name + '.' + fn + ']" does not exist.');
				};
			})(jQuery);
		}
	},
	//--------------------------------------------------------------------------------
	storage: {
		//--------------------------------------------------------------------------------
		build_id: function(component, id) {
			// required
			if (!component && !id) {
				return false;
			}

			// done
			return component+'-'+id;
		},
		//--------------------------------------------------------------------------------
		get_storage_object: function(storage_type) {
			// storage
			switch (storage_type) {
				case 'local' : return localStorage; break;
				case 'session' : return sessionStorage; break;
				default : return; break;
			}
		},
		//--------------------------------------------------------------------------------
		set: function(storage_type, component, id, property, value) {
			// params
			var key = this.build_id(component, id);
			if (!key) {
				throw 'Key required to set local storage';
			}

			// storage
			var storage = this.get_storage_object(storage_type);
			if (!storage) {
				throw 'Storage object not found';
			}

			// required
			if (!property || property === undefined) {
				throw 'No property received';
			}

			// get item
			var item = this.get(storage_type, component, id);

			// create if it doesn't yet exist
			if (item == undefined || item == null) {
				item = {};
			}

			// set value
			item[property] = value;

			// stringify
			item = JSON.stringify(item);

			// set
			storage.setItem(key, item);
		},
		//--------------------------------------------------------------------------------
		get: function(storage_type, component, id, property) {
			// params
			var key = this.build_id(component, id);
			if (!key) {
				throw 'Key required to get local storage';
			}

			// storage
			var storage = this.get_storage_object(storage_type);
			if (!storage) {
				throw 'Storage object not found';
			}

			// get
			try {
				var item = storage.getItem(key);
			}
			catch(error){
				// not found
				return undefined;
			}

			// not found
			if (!item || item == null) {
				return item;
			}

			// parse item
			item = JSON.parse(item);

			// return entire storage item
			if (!property) {
				return item;
			}

			// property
			return item[property];
		},
		//--------------------------------------------------------------------------------
		remove: function(storage_type, component, id, property) {
			// params
			var key = this.build_id(component, id);
			if (!key) {
				throw 'Key required to set local storage';
			}

			// storage
			var storage = this.get_storage_object(storage_type);
			if (!storage) {
				throw 'Storage object not found';
			}

			// required
			if (!property || property === undefined) {
				throw 'No property received';
			}

			// get item
			var item = this.get(storage_type, component, id);

			// return if it doesn't yet exist
			if (!item || item == undefined || item == null) {
				return;
			}

			// remove
			delete item[property];

			// stringify
			item = JSON.stringify(item);

			// set
			storage.setItem(key, item);
		},
		//--------------------------------------------------------------------------------
		local: {
			//--------------------------------------------------------------------------------
			set: function(component, id, property, value) {
				core.storage.set('local', component, id, property, value);
			},
			//--------------------------------------------------------------------------------
			get: function(component, id, property) {
				return core.storage.get('local', component, id, property);
			},
			//--------------------------------------------------------------------------------
			remove: function(component, id, property) {
				core.storage.remove('local', component, id, property);
			},
			//--------------------------------------------------------------------------------
		},
		//--------------------------------------------------------------------------------
		session: {
			//--------------------------------------------------------------------------------
			set: function(component, id, property, value) {
				core.storage.set('session', component, id, property, value);
			},
			//--------------------------------------------------------------------------------
			get: function(component, id, property) {
				return core.storage.get('session', component, id, property);
			},
			//--------------------------------------------------------------------------------
			remove: function(component, id, property) {
				core.storage.remove('session', component, id, property);
			},
			//--------------------------------------------------------------------------------
		},
		//--------------------------------------------------------------------------------
	},
	//--------------------------------------------------------------------------------
	workspace: {
	},
	//--------------------------------------------------------------------------------
	session: {
	},
	//--------------------------------------------------------------------------------
	util: {
        //--------------------------------------------------------------------------------
        htmlentities: function(str) {
            return $("<textarea/>")
            .text(str)
            .html();
        },
       //--------------------------------------------------------------------------------
        html_entity_decode: function(str) {
            return $("<textarea/>")
            .html(str)
            .text();
        },
        //--------------------------------------------------------------------------------
        nl2br: function(str, is_xhtml) {
            if (typeof str === 'undefined' || str === null) {
                return '';
            }
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        },
        //--------------------------------------------------------------------------------
        mark_text: function(str, $target){

        	if(!$target) $target = 'body';

        	$target = $($target);

        	let src_str = $target.html();
			let term = str;
			term = term.replace(/(\s+)/,"(<[^>]+>)*$1(<[^>]+>)*");
			let pattern = new RegExp("("+term+")", "gi");

			src_str = src_str.replace(pattern, "<mark>$1</mark>");
			src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/,"$1</mark>$2<mark>$4");

			$target.html(src_str);
        },
		//--------------------------------------------------------------------------------
        highlight_text: function(str, $target){

            if(!$target) $target = 'body';

            try{
                $($target).highlight(str);
            }catch (e) {}
        },
        //--------------------------------------------------------------------------------
        remove_highlight_text: function($target){
            if(!$target) $target = 'body';
            $($target).removeHighlight();
        },
        //--------------------------------------------------------------------------------
        extension: function (filename) {
            return filename.substr((filename.lastIndexOf('.') + 1));
        },
        //-------------------------------------------------------------------------
        clean_filename: function (filename) {
            var extension = util.extension(filename);
            var s = filename.substr(0, filename.lastIndexOf('.')) || filename;
            return s.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.' + extension;
        },
        //-------------------------------------------------------------------------
        is_mobile: function () {
            if (/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) return true;
            return false;
        },
        //-------------------------------------------------------------------------
        serialize_object: function (params) {
            return jQuery.param(params);
        },
        //-------------------------------------------------------------------------
        id_generator: function () {
            return core.string.get_random_id();
        },
        //-------------------------------------------------------------------------
        trigger_tooltip: function () {
            setTimeout(function () {

                let tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipList.map(function (el) {
                  return bootstrap.Tooltip.getOrCreateInstance(el);
                });

            }, 100);
        },
        //-------------------------------------------------------------------------
        formatCurrency: function (number, options) {

            var options = $.extend({
                symbol: "R",
                precision: 2,
            }, (options == undefined ? {} : options));

            var neg = false;
            if (number < 0) {
                neg = true;
                number = Math.abs(number);
            }

            if(options.symbol)
                options.symbol = options.symbol + " ";

            return (neg ? "-" + options.symbol : options.symbol) + parseFloat(number, 10).toFixed(options.precision).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
        },
        //-------------------------------------------------------------------------
        str_to_num: function (number) {
            return parseFloat(number.match(/\d+/)[0]);
        },
        //-------------------------------------------------------------------------
        round: function round(value, precision) {
            if (precision == undefined) precision = 2;
            var aPrecision = Math.pow(10, precision);
            return Math.round(value * aPrecision) / aPrecision;
        },
        //-------------------------------------------------------------------------
        copy_text_to_clipboard: function (text, options) {
            core.util.copy_text(text, options);
        },
        //-------------------------------------------------------------------------
        copy_to_clipboard: function (element) {
            core.util.copy_text(element.text());
        },
        //-------------------------------------------------------------------------
        copy_text: function (text, options) {

            var options = $.extend({
                br2nl: false,
                nl2br: false,
            }, (options == undefined ? {} : options));

            if (options.br2nl) {
                text = text.replace(/<br\s*[\/]?>/gi, "\n");
            }
            if (options.nl2br) {
                text = text.replace(/(?:\r\n|\r|\n)/g, '<br>');
            }

            let body = $('body');
            let id = core.util.id_generator();

            let $temp = $("<textarea>");
            $temp.attr("id", id);
            $temp.css({position: 'absolute', left: '-5000px'});
            $temp.val(text);

            if (body.hasClass('modal-open')) {
                $('.modal.show').append($temp);
                $temp.select();
            } else {
                $("body").append($temp);
                $temp.select();
            }

            setTimeout(function () {
                document.execCommand("copy");
                core.message.show_notice('Copy', 'Text copied to clipboard');
                $temp.remove();
            }, 100);

        },
        //-------------------------------------------------------------------------
        get_type: function (object) {
            var stringConstructor = "test".constructor;
            var arrayConstructor = [].constructor;
            var objectConstructor = {}.constructor;

            if (object === null) {
                return "null";
            } else if (object === undefined) {
                return "undefined";
            } else if (object.constructor === stringConstructor) {
                return "String";
            } else if (object.constructor === arrayConstructor) {
                return "Array";
            } else if (object.constructor === objectConstructor) {
                return "Object";
            } else {
                return null;
            }
        },
    },
	//--------------------------------------------------------------------------------
};
