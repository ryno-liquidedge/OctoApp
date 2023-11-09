<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class itext extends \com\ui\set\bootstrap\itext {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Text input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
	/**
	 * Returns the HTML for a text input.
	 *
	 * @param string $id <p>The id of the select element used when submitting the form.</p>
	 * @param string $value <p>The starting value of the input.</p>
	 * @param string $label <p>The name of the element that will be displayed as a label. Providing "" will not show a label, but will still render the underlying structure. Providing false will show no label and will not generate the underlying structure.</p>
	 *
	 * @param int $options[rows] <p>Providing a row value will display a textarea element with this number of rows instead of an input element.</p>
	 * @param string $options[limit] <p>Filters the characters that can be used based on a named set. Can be numeric, numeric_positive, fraction, alphanumeric, alpha, email</p>
	 * @param int|string $options[width] <p></p>
	 * @param boolean $options[hidden] <p>Creates a hidden input element with the id and value as specified and displays the value as static text.</p>
	 * @param boolean $options[mask] <p>Sets the input type to password, allowing the text to be masked.</p>
	 * @param boolean $options[focus] <p>Focusses the cursor on this element when the page has finished loaded. Only the last element with focus set will be executed.</p>
	 *
	 * @param string $options[autocomplete] <p>Specify an URL to use for fetching a list of available values. The query string is sent as 'term'. The return must be a JSON array with each element having an id and a value.</p>
	 * @param string $options[autocomplete_addvar] <p>A CSS selector with form elements that will be parsed and sent with the autocomplete URL.</p>
	 * @param string $options[autocomplete_value] <p>Specify the initial value that should be in the autocomplete text input.</p>
	 * @param string $options[autocomplete_select] <p>Javascript to run on selection.</p>
	 *
	 * @param string $options[help] <p>Text to display that is meant to give hints on how to use the element.</p>
	 * @param boolean $options[required] <p>Make the field required. It will be checked for a value when the form is submitted.</p>
	 * @param boolean $options[required_skipval] <p>Only display the required indicator. Skip the form validation. This must be used in conjunction with the required option.</p>
	 * @param string|function $options[prepend] <p>Text to add in a special block attached to the front of the element. A callback function may be used to add other controls and advanced text.</p>
	 * @param string|function $options[append] <p>Text to add in a special block attached to the end of the element. A callback function may be used to add other controls and advanced text.</p>
	 * @param string $options[wrapper_id] <p>The id to use on the wrapper form control. Used to hide/show label and input group.</p>
	 * @param string $options[input_append_id] <p></p>
	 * @param int|string $options[label_width] <p></p>
	 * @param boolean $options[label_html] <p></p>
	 *
	 * @param string $options[#] <p>If the option index starts with '#', the rest of the index will be used as an inline style name and the option value as the value. (style="[option-index]:[option-value];")</p>
	 * @param boolean $options[.] <p>If the option index starts with '.' and the option value is 'true', the rest of the index will be added as an inline class. (class="[option-index]")</p>
	 * @param string|boolean $options[@] <p>If the option index starts with '@', the rest of the index will be used as a tag attribute name and the option value as the value. ([option-index]="[option-value]")</p>
	 * @param string $options[!] <p>If the option index starts with '!', the rest of the index will be used as an inline event and the option value as the javascript to execute. (on[option-index]="[option-value]")</p>
	 *
	 * @return string <p>HTML for the text input tag.</p>
	 */
	//public static function itext($id, $value = false, $label = false, $options = []) {

		// options
  		$options = array_merge([
  			"id" => false,
			"value" => false,
			"label" => false,

			// basic
			"@disabled" => false,
			"@nosubmit" => false,
			"@maxlength" => false,
			"@tabindex" => false,
			"@placeholder" => false,
			"@com-form-validate" => false,
			".float-left" => false,
			".float-right" => false,
			"!enter" => false,
			"!keypress" => false,
			"!keyup" => false,
			"!change" => false,
			".ui-itext" => true,
			".no-auto-fill" => false,

			// advanced
			"rows" => false,
			"limit" => false,
			"width" => false,
			"hidden" => false,
			"mask" => false,
			"focus" => false,
			"focus_delay" => 10,
			"autocomplete" => false,
			"autocomplete_nocache" => false,
			"autocomplete_loader" => true,
			"/autocomplete_loader" => [],
	      	"autocomplete_addvar" => false,
	      	"autocomplete_value" => false,
	      	"autocomplete_select" => false,
	      	"autocomplete_select_complete" => false,
	      	"autocomplete_closed" => false,
	      	"autocomplete_limit" => 10,
	      	"value_is_html" => false,
	      	"wysiwyg" => false,

			// form-input
			"help" => false,
			"help_popover" => false,
			"required" => false,
			"required_icon" => true,
			"required_skipval" => false,
			"required_message" => true,
			"required_title" => "Required",
			"/required" => [],
			"prepend" => false,
			"append" => false,
			"append_type" => false,
			"wrapper_id" => false,
			"/wrapper" => [],
			"/input_group" => [],
			"/form_input" => [],
			"/form_label" => [],
			"input_append_id" => false,
			"label_width" => false,
			"label_middle" => true,
			"label_col" => false,
			"label_html" => false,
			"label_click" => false,
			"label_hidden" => false,
			"floating_label" => false,
			"horizontal" => true,
  		], $options);


  		// init
		$id = $options["id"];
		$value = $options["value"];
		$label = $options["label"];
		$label_validation = $label;
		if($options["label_hidden"]) $label = false;
		if($options["floating_label"]) $options["@placeholder"] = "";

		if($options["label_middle"] && !$options["help"]) {
		    $options["/wrapper"][".align-items-center"] = true;
        };

		// value
		$value = strtr(isnull($value) ? "" : $value, ["&lt;" => "<"]);

		// hidden
		if ($options["hidden"]) {
			// function: $FN_inject
			$FN_inject = function($html) use ($id, $value) {
				$html->xihidden($id, $value);
			};

			// html
			$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
			$html->xform_value($label, $value, false, [
				"/form_label" => $options["/form_label"],
				"label_width" => $options["label_width"],
				"label_col" => $options["label_col"],
				"label_click" => $options["label_click"],
				"wrapper_id" => $options["wrapper_id"],
				"append" => $options["append"],
				"append_type" => $options["append_type"],
				"inject" => $FN_inject,
			]);

			// done
			return $html->get_clean();
		}

		// autocomplete: https://github.com/twitter/typeahead.js
		// backup-autocomplete: https://github.com/bassjobsen/Bootstrap-3-Typeahead

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		//-----------------------------
		// setup

		// form input options
		$form_input_options = array_merge([
			"required" => $options["required"],
			"required_title" => $options["required_title"],
			"/required" => $options["/required"],
			"required_message" => $options["required_message"],
			"required_icon" => $options["required_icon"],
			"help" => $options["help"],
			"help_popover" => $options["help_popover"],
			"prepend" => $options["prepend"],
			"append" => $options["append"],
			"append_type" => $options["append_type"],

			"wrapper_id" => $options["wrapper_id"],
			"/wrapper" => $options["/wrapper"],
			"input_append_id" => $options["input_append_id"],

			"label" => $label,
			"label_width" => $options["label_width"],
			"label_col" => $options["label_col"],
			"label_html" => $options["label_html"],
			"label_click" => $options["label_click"],
			"floating_label" => $options["floating_label"],
			"horizontal" => $options["horizontal"],
			"/input_group" => $options["/input_group"],
			"/form_label" => $options["/form_label"],
		], $options["/form_input"]);

		// autocomplete
		$autocomplete_id = \LiquidedgeApp\Octoapp\app\app\js\js::parse_id($id);
		if ($options["autocomplete"]) {
			if (isnull($value)) $value = false;
			$html->xihidden($id, $value);
			$id = "__{$id}";
			$options["@nosubmit"] = true;
			$options["@autocomplete"] = "chrome-off";
			if (!$options["@placeholder"]) $options["@placeholder"] = "Start typing for options ...";
			if ($options["autocomplete_value"]) $value = $options["autocomplete_value"];
			$options["@nova-selected-item"] = $value;

			if($options["autocomplete_loader"]){

				$options["/autocomplete_loader"] = array_merge([
					"#position" => "absolute",
					"#right" => "5px",
					"#z-index" => "1",
					"#bottom" => "5px",
				], $options["/autocomplete_loader"]);
				$options["/autocomplete_loader"][".autocomplete-loading"] = true;
				$options["/autocomplete_loader"][".$id-loading"] = true;
				$options["/autocomplete_loader"]["#display"] = "none";

				//autocomplete wrapper open
				$html->style(["*" => ".ui-menu.ui-widget.ui-widget-content.ui-autocomplete{ z-index: 1055; }"]);
				$html->div_([".w-100 autocomplete-wrapper position-relative" => true]);
				$html->div_($options["/autocomplete_loader"]);
					$html->xicon("fa-spinner", [".fa-spin" => true]);
				$html->_div();
			}
		}
		// class
		$options[".form-control"] = true;

		// id
		$options["@id"] = $id;
		if(!isset($options["@name"]))$options["@name"] = $id;

		// width
		if ($options["prepend"] || $options["append"]) {
			$form_input_options["width"] = $options["width"];
		}
		else {
			$this->apply_options($options);
		}

		// rows
		if ($options["rows"]) {
			$options["@rows"] = $options["rows"];
		}

		// type
		$type = "text";
		if ($options["mask"]) $type = "password";

		// disabled
		if ($options["@disabled"]) {
			$options["@readonly"] = true;
		}

		// limit
		if (in_array($options["limit"], ["year"])) {
			$options["@com-form-validate"] = $options["limit"];
		}

		if($options["wysiwyg"]){
		    $options[".input-wysiwyg"] = true;
        }

		//------------------------------
		// function: $FN_input
			$FN_input = function(&$html) use ($type, $id, $value, &$options) {
			if ($options["rows"]) {
				$value_type = ($options["value_is_html"] ? "*" : "^");
				$html->textarea("{$value_type}{$value}", $options);
			}
			else $html->xinput($type, $id, $value, $options);
		};
		//------------------------------

		// form item
		$form_input_options["input_id"] = $id;
		$html->xform_input($FN_input, $form_input_options);

		//-----------------------------
		// script
		// required
		if (!$options["required_skipval"]) {
			if ($options["required"] && \com\ui\helper::$current_form) {
				\com\ui\helper::$current_form->add_validation_empty($id, $label_validation);
			}
		}

		// limit
		if ($options["limit"]) {
			$JS_id = strtr($id, ["[" => "\\\\[", "]" => "\\\\]", "." => "\\\\."]);
			\com\js::add_event("#{$JS_id}", 'keypress', "return core.event.is_{$options["limit"]}(event);");
			\com\js::add_event("#{$JS_id}", 'change', "core.form.limit_{$options["limit"]}(this);");

			// limit: email
			if ($options["limit"] == "email" && \com\ui\helper::$current_form) {
				\com\ui\helper::$current_form->add_validation_email($id, $label_validation);
			}
		}

		// autocomplete
		if ($options["autocomplete"]) {
			$id = \LiquidedgeApp\Octoapp\app\app\js\js::parse_id($id);

			$url = $options["autocomplete"];
			if($options["autocomplete_nocache"]) $url .= "&nocache=".time();
			$json_options = \LiquidedgeApp\Octoapp\app\app\js\js::create_options([
				"*source" => "!function( request, response ) {
					core.ajax.request_function('$url', function(data){
						response( data );
					}, {
						no_overlay: true,
						method: 'GET',
						data: {
							term: request.term
						}
					});
				}",
				"*create" => "!function( event, ui ) {
				}",
				"*search" => "!function( event, ui ) {
					$('.$id-loading').show().removeClass('loaded');
				}",
				"*response" => "!function( event, ui ) {
					$('.$id-loading').hide().addClass('loaded');
				}",
				"*close" => "!function( event, ui ) {
					let id = ui.item ? ui.item.value : '';
					let val = ui.item ? core.util.html_entity_decode(ui.item.label) : '';
					
					if(ui.item){
						$('#{$id}').val(val);
						$('#{$autocomplete_id}').val(id);
						$('#{$id}').attr('nova-selected-item', id);
					}
					
				}",
				"*change" => "!function( event, ui ) {
					if(!ui.item){
						let el = $('#{$id}');
						el.val(el.attr('defaultvalue'));
						
						let el_key = $('#{$autocomplete_id}');
						el_key.val(el_key.attr('defaultvalue'));
					}else{
						let id = ui.item ? ui.item.value : '';
						let val = ui.item ? core.util.html_entity_decode(ui.item.label) : '';
					
						$('#{$id}').val(val);
						$('#{$autocomplete_id}').val(id);
						$('#{$id}').attr('nova-selected-item', id);
					}
				}",
				"*select" => "!function( event, ui ) {
					
					let id = ui.item ? ui.item.value : '';
					let val = ui.item ? core.util.html_entity_decode(ui.item.label) : '';
					
					setTimeout(function(){
						{$options["autocomplete_select"]}
						if(ui.item){
							$('#{$id}').val(val);
							$('#{$autocomplete_id}').val(id);
							$('#{$id}').attr('nova-selected-item', id);
						}
						{$options["autocomplete_select_complete"]}
					}, 10);
					
				}",
			]);

			\com\js::add_script("
				$(function(){
					$( '#$id' ).autocomplete($json_options);
				})
		  ");
		}

		// wysiwyg
		if ($options["wysiwyg"]) {
			$wysiwyg = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->wysiwyg($id, is_array($options["wysiwyg"]) ? $options["wysiwyg"] : []);
			$wysiwyg->get();
		}

		// focus
		if ($options["focus"]) {
			\com\js::add_script("
				setTimeout(function(){
					$('#{$id}').focus();
				}, {$options["focus_delay"]});
			");
		}

		if ($options["autocomplete"]) {
			//autocomplete wrapper open
			$html->_div();
		}

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}