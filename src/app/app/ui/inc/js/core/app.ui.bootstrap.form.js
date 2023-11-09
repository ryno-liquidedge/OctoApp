///-------------------------------------------------------------------------
//  @project    core
//  @version    1.05
//  @author     Niel Astle
//  @company    Liquid Edge Solutions
//	@copyright  Copyright Liquid Edge Solutions. All rights reserved.
//  @desc       Client side user interface for 'com_form' component
///-------------------------------------------------------------------------
// classes
///-------------------------------------------------------------------------
var com_form_validation = function(the_cid, the_type, the_display, enabled) { // com_form_validation constructor
	/// init properties
	this.cid = the_cid;
	this.type = the_type;
	this.display = the_display;
	this.enabled = (enabled == undefined ? true : enabled);

	if (com_form.messages[this.type] == null) {
	  	this.display = new Array(this.type);
	  	this.type = 'unknown';
	}
}

///-------------------------------------------------------------------------
var com_form = function(the_cid, the_validations) { // com_form constructor
	// init properties
  	this.cid = the_cid; // unique component id
  	this.e_form = document.forms[the_cid + '_form']; // form element
  	this.validations = (the_validations == null ? new Array() : the_validations); // form validation elements

  	/// debug
  	//if (this.e_form == null) alert('DEV: The form \'' + the_cid + '\' does not exist');
}



///-------------------------------------------------------------------------
// static
///-------------------------------------------------------------------------
com_form.messages = {
  	empty 			: 'The {0} field was empty.',
	zero 			: 'The {0} field was empty.',
	address 		: 'The {0} field needs at least a street name as well as suburb, city, code and province.',
	date 			: 'The {0} field was empty.',
	notequal 		: 'The {0}- and {1} fields must have the same value.',
	length 			: 'The {0} field must be {1} characters long.',
	maxlength 		: 'The {0} field length must be less than or equal to {1} characters.',
	minlength 		: 'The {0} field length must be more than or equal to {1} characters.',
	emptylist 		: 'The {0} field was empty.',
	emptylistnew	: 'The {0} field was empty.',
	email 			: 'The {0} field must contain a valid email address.',
	unchecked 		: 'The {0} field must be checked.',
	maxnumber		: 'The {0} field must be less than {1}.',
	minnumber		: 'The {0} field must be more than {1}.',
	complexpw		: '{0}',
	custom			: '{0}',
	/// debug
	unknown			: 'DEV: There is no validation for type: {0}.'
}


///-------------------------------------------------------------------------
// prototypes
///-------------------------------------------------------------------------
com_form.prototype = { // com_form prototype
	///-------------------------------------------------------------------------
	validate: function(options) { // perform input validation and submit form

		// options
		var options = $.extend({
			enable_error_popup: true,
			auto_focus_on_error: false,
			enable_error_feedback: true,
		}, (options == undefined ? {} : options));

	  	var error = '';
	  	var error_alert = '';
	  	for (var i in this.validations) {
	  	  	var new_error = false;

	  	  	// enabled
	  	  	if (!this.validations[i].enabled) continue;
	  	  	if(!$('#'+this.validations[i].cid).is(':visible') && !$('#'+this.validations[i].cid).hasClass('input-wysiwyg')) continue;

	  	  	// custom validation
	  	  	if (this.validations[i].type == 'custom') {
	  	  		eval(this.validations[i].cid);
	  	 	}
	  		else {
		  	  	// predefined validation
		  	  	switch (typeof this.validations[i].cid) {
		  	  	  	case 'string' :
		  	  	  		if (this.validations[i].type == 'address') {
							var address_id = '\\[' + this.validations[i].cid + '\\]';

							var type_value = $('#add_type' + address_id).val();

							var suburb_value = $.trim($('#add__suburb' + address_id).val());
							var town_value = $.trim($('#add__town' + address_id).val());
							var code_value = $.trim($('#add_code' + address_id).val());

							var development_value = $.trim($('#add_development' + address_id).val());
							var building_value = $.trim($('#add_building' + address_id).val());
							var farm_value = $.trim($('#add_farm' + address_id).val());
							var street_value = $.trim($('#add_street' + address_id).val());

		  	  	  			var pobox_value = $.trim($('#add_pobox' + address_id).val());
		  	  	  			var privatebag_value = $.trim($('#add_privatebag' + address_id).val());
		  	  	  			var postnet_value = $.trim($('#add_postnet' + address_id).val());

		  	  	  			var line1_value = $.trim($('#add_line1' + address_id).val());
		  	  	  			var country_value = $.trim($('#add__country' + address_id).val());

							switch (type_value) {
								case '1' :
									new_error = (((street_value == '' && farm_value == '' && development_value == '' && building_value == '') || suburb_value == '' || town_value == '' || code_value == '') ? true : false);
									com_form.messages['address'] = 'The {0} field needs at least a farm, street, development or building name as well as suburb, city and code.';
									break;

								case '2' :
		  	  	  					new_error = ((pobox_value == '' || suburb_value == '' || town_value == '' || code_value == '') ? true : false);
		  	  	  					com_form.messages['address'] = 'The {0} field needs at least a PO box as well as suburb, city and code.';
									break;

								case '3' :
		  	  	  					new_error = (((privatebag_value == '' && postnet_value == '') || suburb_value == '' || town_value == '' || code_value == '') ? true : false);
		  	  	  					com_form.messages['address'] = 'The {0} field needs at least a private bag or postnet number as well as suburb, city and code.';
									break;

								case '5' :
									new_error = ((line1_value == '' || country_value == '' || code_value == '') ? true : false);
		  	  	  					com_form.messages['address'] = 'The {0} field needs at least one line, a country and code.';
									break;
							}
		  	  	  		}
		  	  	  		else {
					  	  	var the_value = $('#' + this.validations[i].cid).val();
					  	  	switch (this.validations[i].type) {
					  	  	  	case 'empty' 		: new_error = ($.trim(the_value) == '' || the_value == 'null'? true : false); break;
					  	  	  	case 'zero' 		: new_error = (the_value == 0 ? true : false); break;
					  	  	  	case 'date' 		: new_error = (the_value == '' ? true : false); break;
					  	  	  	case 'length' 		: new_error = (the_value.length != this.validations[i].display[1] ? true : false); break;
					  	  	  	case 'maxlength' 	: new_error = (the_value.length > this.validations[i].display[1] ? true : false); break;
					  	  	  	case 'minlength'	: new_error = (the_value.length < this.validations[i].display[1] ? true : false); break;
					  	  	  	case 'emptylist'	:
					  	  	  		var value = $('#' + this.validations[i].cid).val();
					  	  	  		new_error = (value == 0 || value == 'null' || value == null ? true : false);
					  	  	  		break;

					  	  	  	case 'emptylistnew'	:
					  	  	  		var value = $('#' + this.validations[i].cid).val();
					  	  	  		if (value == -2) new_error = ($('#__' + this.validations[i].cid + 'add').val() == '' ? true : false);
					  	  	  		else new_error = (value == 0 || value == 'null' || value == null ? true : false);
					  	  	  		break;

					  	  	  	case 'email' 		:
									var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,})+$/;
									new_error = (the_value ? !filter.test(the_value) : false);
					  	  	  		break;

					  	  	  	case 'unchecked'	: new_error = (!this.e_form[this.validations[i].cid].checked); break;
					  	  	  	case 'maxnumber'	: new_error = (parseInt(the_value) > this.validations[i].display[1] ? true : false); break;
					  	  	  	case 'minnumber'	: new_error = (parseInt(the_value) < this.validations[i].display[1] ? true : false); break;

					  	  	  	case 'complexpw'	:
									if (the_value) {
										var message_arr = core.form.is_complex_password(the_value, 8, true, true, true, false, ($('#' + this.validations[i].display[1]) ? $('#' + this.validations[i].display[1]).val() : false));
										if (message_arr === true) new_error = false;
										else {
											new_error = true;
											com_form.messages['complexpw'] = 'The {0} field requires the following:<ul>';
											$.each(message_arr, function(message_index, message_item) {
												com_form.messages['complexpw'] += '<li>' + message_item + '</li>';
											});
											com_form.messages['complexpw'] += '</ul>';
										}
									}
									break;
					  	  	}
		  	  	  		}
					  	break;
				  	case 'object' :
				  	  	switch (this.validations[i].type) {
				  	  	  	case 'notequal' 	: new_error = (this.e_form[this.validations[i].cid[0]].value != this.e_form[this.validations[i].cid[1]].value ? true : false); break;
				  	  	}
				  	  	break;
		  	  	}
		  	}
			if (new_error) {
				var error_feedback = $('#' + this.validations[i].cid + ' + div.invalid-feedback');
				if (!error_feedback.length) {
					error_feedback = $('div.invalid-feedback[data-target='+this.validations[i].cid+']');
				}
				if (error_feedback.length && options.enable_error_feedback) {
					error_feedback.html(this.get_message(this.validations[i]));
				}

				$('#' + this.validations[i].cid).addClass("is-invalid");
				error_alert += '<li>' + this.get_message(this.validations[i]) + '</li>';
				error = true;
			}
		}

		// check for attribute validations
		$('#' + this.cid + '_form [com-form-validate]').each(function(index, item) {
			var $item = $(item);
			var value = $item.val();
			var validate_type = $item.attr('com-form-validate');

			var new_error = false;
			switch (validate_type) {
				case 'year' :
					var filter  = /^[1-9]{1}[0-9]{3}$/;
					new_error = (value && value != '0' ? !filter.test(value) : false);
					break;

				case 'regex' :
					try {
						new RegExp(value);
					}
					catch(e) {
						new_error = true;
					}
					break;
			}

			if (new_error) {
				var label = $('label[for=' + $item.attr('id') + ']').text();
				error = true;
				error_alert += '<li>The ' + label + ' field must contain a valid ' + validate_type + '</li>';
			}
		});

		// show alert if we have errors
		if (error_alert && options.enable_error_popup) {
			core.browser.alert('<ul>' + error_alert + '</ul>', 'The below issues were encountered when trying to complete your action:');
		}

		if(error && options.auto_focus_on_error){
			$('.is-invalid:first').focus();
		}

		// done
	  	return !error;
	},

	///-------------------------------------------------------------------------
	get_message: function(the_com_form_validation) { // returns the formatted message for the message type
	  	var display = (typeof the_com_form_validation.display == 'string' ? new Array(the_com_form_validation.display) : the_com_form_validation.display);
	  	var message = com_form.messages[the_com_form_validation.type];
	  	for (var i in display) {
	  	  	var regex = new RegExp('\\{' + i + '\\}', 'ig');
	  	  	message = message.replace(regex, display[i]);
	  	}
	  	return message;
	},

	///-------------------------------------------------------------------------
	enable_validation: function(id) {
		$.each(this.validations, function(index, item) {
			if (item.cid == id) item.enabled = true;
		});
	},
	///-------------------------------------------------------------------------
	disable_validation: function(id) {
		$.each(this.validations, function(index, item) {
			if (item.cid == id) item.enabled = false;
		});
	}
	///-------------------------------------------------------------------------
};

class formSubmitHelper {
    //--------------------------------------------------------------------------------
    /**
     * constructor
     * @param options
     */
    constructor(options) {
        // options
        this.options = $.extend({
            action:false,
            validate_url:false,
            no_overlay:false,
            form_el:false,
            cid:false,
            data:{},
            no_popup:false,
            confirm:false,
            recaptcha:false,
        }, (options === undefined ? {} : options));

        if(this.options.form_el) this.setFormElement(this.options.form_el);

    }
    //--------------------------------------------------------------------------------
    set(key, value){
        this.options[key] = value;
    }
    //--------------------------------------------------------------------------------
    setAction(action){
        this.options.action = action;
    }
    //--------------------------------------------------------------------------------
    setNoOverlay(bool){
        this.options.no_overlay = bool;
    }
    //--------------------------------------------------------------------------------
    setFormElement(form_el){
        this.options.form_el = $(form_el);
        this.options.action = this.options.form_el.attr('action');
        this.options.target = '#'+this.options.form_el.attr('id');
        this.options.cid = this.options.form_el.data('cid');
        this.options.validate_url = this.options.form_el.data('validate');
    }
    //--------------------------------------------------------------------------------
    ajax(){
        let instance = this;
        core.ajax.request(instance.options.action, {
            enable_reset_html:false,
            form:instance.options.target,
            data:instance.options.data,
            beforeSend: function(){
                if(!instance.options.no_overlay) core.overlay.show();
            },
            success: function(response){
                if(!instance.options.no_overlay) core.overlay.hide();
                core.ajax.process_response(response);
            },
        });
    }
    //--------------------------------------------------------------------------------
    execute_ajax(token){

        let instance = this;

        app.form.reset_validate_fields();

        if(token !== undefined) instance.options.data['g-recaptcha-response'] = token;

        if(instance.options.validate_url){
            core.ajax.request_function(instance.options.validate_url, function(response){
                if(response.code === 1) return core.browser.alert(response.message);
                else instance.ajax();
            }, {
                form:instance.options.target,
                data:instance.options.data,
                beforeSend: function(){
                    if(!instance.options.no_overlay) core.overlay.show();
                }
            });
        }else instance.ajax();
    }
    //--------------------------------------------------------------------------------
    do_ajax(){

        let instance = this;

        if (instance.options.recaptcha && typeof grecaptcha != "undefined") {
            e.preventDefault();
            grecaptcha.ready(function () {
                grecaptcha.execute(recaptcha, {action: 'submit'}).then(function (token) {
                    instance.execute_ajax(token);
                });
            });
        } else {
            instance.execute_ajax();
        }
    }
    //--------------------------------------------------------------------------------
    call(){

        let instance = this;

        if(instance.options.cid){
            let cid = eval(instance.options.cid);
            let validated = cid.validate({enable_error_popup: !instance.options.no_popup});
            if(!validated) return;
        }
        if(instance.options.confirm) {
            core.browser.confirm(instance.options.confirm, function(){
                instance.do_ajax();
            });
        } else instance.do_ajax();
    }
    //--------------------------------------------------------------------------------
}

///------------------------------------------------------------------------
