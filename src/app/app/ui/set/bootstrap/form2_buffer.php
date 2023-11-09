<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class form2_buffer extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
  	// properties
  	//--------------------------------------------------------------------------------
	public $cid; // unique component id
    public $action = false; // html form 'action' attribute
    public $validate_url = false;
    public $submit_js = false;
    public $submit_button_id = false;
	public $id_form = false;
	public $id_block = false;
	public $horizontal = true;
	public $enable_error_popup = true;

    public $js_validation_emptylist = "(this.e_form[\\'%field_id%\\'].options[this.e_form[\\'%field_id%\\'].selectedIndex].value == 0 || this.e_form[\\'%field_id%\\'].options[this.e_form[\\'%field_id%\\'].selectedIndex].value == \\'null\\' ? true : false)";
    public $js_validation_empty = "(this.e_form[\\'%field_id%\\'].value == \\'\\' ? true : false)";
    public $js_validation_emptyvalue = "(this.e_form[\\'%field_id%\\'].value == \\'%arg1%\\' ? true : false)";
    public $js_validation_unchecked = "(!this.e_form[\\'%field_id%\\'].checked)";

    protected $validations = []; // data validations
    //--------------------------------------------------------------------------------
	// magic
    //--------------------------------------------------------------------------------
    public function __construct($options = []) {
		// options
		$options = array_merge([
			"id" => false,
		], $options);

		$this->cid = ($options["id"] ? $options["id"] : \com\session::$current->session_uid);
		$this->id_form = "{$this->cid}_form";
		$this->id_block = "{$this->cid}_block";
    }
    //--------------------------------------------------------------------------------
	// functions
    //--------------------------------------------------------------------------------
	public function build($options = []) {
	}
	//--------------------------------------------------------------------------------
    public function add_validation($element, $type, $display, $enabled = true) { // adds a form validation test and message
	  	$this->validations[] = [
	  		"element" => $element, // strtr($element, ["[" => "\\\\[", "]" => "\\\\]"])
	  		"type" => $type,
	  		"display" => $display,
	  		"enabled" => $enabled,
	  	];
	}
    //--------------------------------------------------------------------------------
	public function add_validation_empty($element, $display) { $this->add_validation($element, "empty", $display); }
    //--------------------------------------------------------------------------------
    public function add_validation_zero($element, $display) { $this->add_validation($element, "zero", $display); }
    //--------------------------------------------------------------------------------
    public function add_validation_date($element, $display) { $this->add_validation($element, "date", $display); }
    //--------------------------------------------------------------------------------
    public function add_validation_length($element, $display, $length) { $this->add_validation($element, "length", [$display, $length]); }
    //--------------------------------------------------------------------------------
    public function add_validation_maxlength($element, $display, $length) { $this->add_validation($element, "maxlength", [$display, $length]); }
    //--------------------------------------------------------------------------------
    public function add_validation_minlength($element, $display, $length) { $this->add_validation($element, "minlength", [$display, $length]); }
    //--------------------------------------------------------------------------------
    public function add_validation_notequal($element1, $display1, $element2, $display2) { $this->add_validation([$element1, $element2], "notequal", [$display1, $display2]); }
    //--------------------------------------------------------------------------------
    public function add_validation_emptylist($element, $display) { $this->add_validation($element, "emptylist", $display); }
    //--------------------------------------------------------------------------------
    public function add_validation_emptylistnew($element, $display) { $this->add_validation($element, "emptylistnew", $display); }
    //--------------------------------------------------------------------------------
    public function add_validation_email($element, $display) { $this->add_validation($element, "email", $display); }
    //--------------------------------------------------------------------------------
    public function add_validation_unchecked($element, $display) { $this->add_validation($element, "unchecked", $display); }
    //--------------------------------------------------------------------------------
    public function add_validation_maxnumber($element, $display, $max) { $this->add_validation($element, "maxnumber", [$display, $max]); }
    //--------------------------------------------------------------------------------
    public function add_validation_minnumber($element, $display, $min) { $this->add_validation($element, "minnumber", [$display, $min]); }
    //--------------------------------------------------------------------------------
    public function add_validation_complexpassword($element, $display, $username_field = "") { $this->add_validation($element, "complexpw", [$display, $username_field]); }
    //--------------------------------------------------------------------------------
    public function add_validation_address($element, $display) { $this->add_validation($element, "address", $display); }
    //--------------------------------------------------------------------------------
    public function add_validation_custom($validation_code, $message) { $this->add_validation($validation_code, "custom", $message); }
    //--------------------------------------------------------------------------------
    public function end(&$buffer) {

		// init
      	$validations = [];
      	foreach ($this->validations as $validation) {
      	  	$element = (is_array($validation["element"]) ? "new Array(".implode(",", \LiquidedgeApp\Octoapp\app\app\arr\arr::quote($validation["element"])).")" : "'$validation[element]'");
      	  	$display = (is_array($validation["display"]) ? "new Array(".implode(",", \LiquidedgeApp\Octoapp\app\app\arr\arr::quote($validation["display"])).")" : "'$validation[display]'");
			$element = strtr($element, ["[" => "\\\\[", "]" => "\\\\]"]); // cater for ids passed as arrays
		    $validations[] = "new com_form_validation($element, '$validation[type]', $display, {$validation["enabled"]})";
		}
      	$validations = "new Array(".implode(",", $validations).")";
    	$js_object = "$this->cid = new com_form('$this->cid'".($validations ? ", $validations" : "").");";

		$this->form_close($buffer);
		$this->wrapper_close($buffer);

		$buffer->script(["*" => "$(function(){ $js_object })"]);

    	/// display

    	\com\ui\helper::$current_form = false;

    }
    //--------------------------------------------------------------------------------
    public function get_validation($type, $field_id, $arg1 = false) {
    	$type_name = "js_validation_$type";
    	return strtr($this->{$type_name}, ["%field_id%" => $field_id, '%arg1%' => $arg1]);
    }
    //--------------------------------------------------------------------------------
	public function start(&$buffer) {
		\com\ui\helper::$current_form = &$this;

		$this->wrapper_open($buffer, $this->id_block);
		$this->form_open($buffer, $this->id_form, $this->action);
	}
    //--------------------------------------------------------------------------------
    public function get_submit_js($url = false, $function = 'requestRefresh', $new_url = false, $confirm_text = false, $overwrite = false, $inter_js = false) {
        // params
        if (!$url) $url = $this->action;

		// confirm
		$JS_reset = "\$button.button('reset');";
		$JS_clicked = "\$button.button('loading');";
		$JS_confirm_text = ($confirm_text ? ", confirm: '{$confirm_text}'" : false);
		$JS_cancel_confirm = ($confirm_text ? ", cancel_confirm: function() { {$JS_reset} }" : false);
		$JS_ok_confirm = ($confirm_text ? ", ok_confirm: function() { {$JS_reset} }" : false);

		// overwrite
		$JS_overwrite = ($overwrite ? ", overwrite: true" : false);

		// new url responseText
		if ($new_url == "responseText") $new_url = "' + responseText + '";

		// new url is javascript
		if ($new_url) {
			if (substr($new_url, 0, 3) == "js:") $new_url = substr($new_url, 3);
			else $new_url = \core::$panel.".requestUpdate('{$new_url}', { form: '#{$this->id_form}'{$JS_overwrite} });";
		}

		// submit
		$JS_submit = false;
		if ($new_url) {
			$JS_submit = "core.ajax.request('{$url}', { form: '#$this->id_form'{$JS_confirm_text}{$JS_cancel_confirm}, success: function(responseText) { {$new_url} } }); {$inter_js}";
		}
		else {
			$JS_submit = \core::$panel.".{$function}('{$url}', { form: '#{$this->id_form}'{$JS_confirm_text}{$JS_overwrite}{$JS_cancel_confirm}{$JS_ok_confirm} }); {$inter_js}";
		}

		// validation
		if ($this->validate_url) $JS_submit = "core.ajax.request_function('$this->validate_url', function(responseText) { if (responseText.code == '0') { $JS_submit } else if (responseText.code == '-1') { core.browser.confirm(responseText.message, function() { $JS_submit }, function() { {$JS_reset} }); } else { {$JS_reset} core.browser.alert(responseText.message); } }, { form: '#$this->id_form', no_overlay: true });";

		// input validation
		$form_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options([
			"*enable_error_popup" => $this->enable_error_popup
		]);
		$JS_submit = "var \$button = $(this); {$JS_clicked} if ($this->cid.validate($form_options)) { {$JS_submit} } else { {$JS_reset} }";

        // save submit javascript
        if (!$this->submit_js) $this->submit_js = $JS_submit;

        // return js string
		return $JS_submit;
    }
    //--------------------------------------------------------------------------------
	protected function wrapper_open(&$buffer, $id_block) {
        $buffer->div_("#{$id_block} .form-wrapper");
	}
	//--------------------------------------------------------------------------------
	protected function wrapper_close(&$buffer) {
        $buffer->_div();
	}
	//--------------------------------------------------------------------------------
	protected function form_open(&$buffer, $id_form, $action) {
        $buffer->xform($id_form, $action, [
			"@novalidate" => true,
			"@autocomplete" => "off",
			"@data-validate" => $this->validate_url,
			"!submit" => "return false;",
			"@role" => "form",
			"@data-cid" => $this->cid,
		]);
		$buffer->xbutton("submit", "", ["#display" => "none"]);
		$buffer->fieldset_();
		$buffer->xhoneypot("empty_check");
	}
	//--------------------------------------------------------------------------------
	protected function form_close(&$buffer) {
        $buffer->_fieldset();
        $buffer->_form();
	}
    //--------------------------------------------------------------------------------
}