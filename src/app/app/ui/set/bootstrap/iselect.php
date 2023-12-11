<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iselect extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Select input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
	/**
	 * Returns the HTML for a select input.
	 *
	 * @param string $id <p>The id of the select element used when submitting the form.</p>
	 * @param string[] $value_option_arr <p>An indexed list of available options for the select - ['index' => 'value']. The selected option's index will be used as the value when the form is submitted. You can specify option groups by adding another array level with the index as the group name - ['group' => ['index' => 'value']]. When bool or boolnull is given, the options will be Yes/No(/Not Selected). When a database table name is provided, a list of values from that table will be generated.</p>
	 * @param string|string[] $value <p>The starting option's index or array of indexes when using a multi-select.</p>
	 * @param string $label <p>The name of the element that will be displayed as a label. Providing "" will not show a label, but will still render the underlying structure. Providing false will show no label and will not generate the underlying structure.</p>
	 *
	 * @param int $options[size] <p>Add option to make the select a multi-select with this many options visible.</p>
	 * @param string $options[tooltip] <p>Text to display as tooltip when hovering over the element.</p>
	 * @param string $options[filter] <p>Used in conjunction with providing a database table on the $value_option_arr parameter. Will then use this value as a where clause on the get list call.</p>
	 * @param boolean $options[hidden] <p>Creates a hidden input element with the id and value as specified and displays the value as static text.</p>
	 * @param int|string $options[width] <p></p>
	 * @param string[] $options[exclude] <p>Specify an array of indexes from $value_option_arr to remove from the resulting options. Functions as a blacklist.</p>
	 * @param string[] $options[include] <p>Specify an array of indexes from $value_option_arr to include in the resulting options. Functions as a whitelist.</p>
	 * @param boolean $options[savedefault] <p>This option populates the defaultvalue attribute, which can be used to reset the element value.</p>
	 * @param boolean $options[addnew] <p></p>
	 * @param boolean $options[focus] <p>Focusses the cursor on this element when the page has finished loaded. Only the last element with focus set will be executed.</p>
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
	 * @return string <p>HTML for the select tag.</p>
	 */
	//public static function iselect($id, $value_option_arr, $value = false, $label = false, $options = []) {

		// options
		$signature_options = \LiquidedgeApp\Octoapp\app\app\arr\arr::extract_signature_items(".", $options);
		$options = array_merge([
			"id" => false,
			"value" => false,
			"label" => false,
			"value_option_arr" => false,
			"/options" => [],

			// basic
			"@disabled" => false,
			"@nosubmit" => false,
			".float-left" => false,
			".float-right" => false,
			"!change" => false,

			// advanced
			"size" => false,
			"tooltip" => false,
			"filter" => false,
			"hidden" => false,
			"width" => false,
			"exclude" => false,
			"include" => false,
			"savedefault" => false,
			"addnew" => false,
			"/addnew" => [],
			"focus" => false,
			"disabled_options" => false,
			"options_options" => false,
			"hide_when_no_options" => false,
			"autocomplete" => false,
			"autocomplete_id" => false,
			"autocomplete_value" => false,

			// select2
			"checkboxes" => false,
			"placeholder" => false,
			"!select" => false,
			"!unselect" => false,
			"!close" => false,

			// form-input
			"help" => false,
			"required" => false,
			"required_skipval" => false,
			"required_title" => "Required",
			"/required" => [],
			"required_icon" => true,
			"label_width" => false,
			"label_middle" => false,
			"label_col" => false,
			"label_html" => false,
			"label_click" => false,
			"label_hidden" => false,
			"/label" => [],
			"prepend" => false,
			"append" => false,
			"append_type" => false,
			"/wrapper" => [],
			"wrapper_id" => false,
			"input_append_id" => false,
			".form-control" => true,
		], $options);

		// init
		$id = $options["id"];
		$value = $options["value"];
		$label = $options["label"];
		$label_validation = $label;
		if($options["label_hidden"]) $label = false;
		if($options["label_middle"]) {
		    $options["/wrapper"][".align-items-center"] = true;
        };

		$options["@defaultvalue"] = $value;

		// values
		$value_option_arr = $options["value_option_arr"];

		// empty values if explicitly false or using an autocompleter
		if ($value_option_arr === false || $options["autocomplete"]) {
			$value_option_arr = [];
		}

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// form input options
		$form_input_options = [
			"required" => $options["required"],
			"help" => $options["help"],
			"prepend" => $options["prepend"],

			"label" => $label,
			"label_width" => $options["label_width"],
			"label_col" => $options["label_col"],
			"label_html" => $options["label_html"],
			"label_click" => $options["label_click"],
            "/label" => $options["/label"],

			"input_id" => $id,
            "/wrapper" => $options["/wrapper"],
		];

		// id and name
		$options["@id"] = $id;
		$options["@name"] = $id;

		// class
		//$options[".form-control"] = true;
		$options[".form-select"] = true;

		// values
		if (!is_array($value_option_arr)) {
			switch ($value_option_arr) {
				case "bool" : $value_option_arr = [0 => "No", "1" => "Yes"]; break;
				case "boolnull" : $value_option_arr = ["null" => "-- Not Selected --", 0 => "No", "1" => "Yes"]; break;
				default :
					$value_option_arr = \core::dbt($value_option_arr)->get_list($options["filter"], ["option_none" => true]);
					if ($options["hide_when_no_options"]) {
						if (count($value_option_arr) == 1) return false;
					}
					break;
			}
		}

		// hidden
		if ($options["hidden"]) {
			// function: $FN_inject
			$FN_inject = function($html) use ($id, $value) {
				$html->xihidden($id, $value);
			};

			$text = ($value_option_arr[$value] == "-- Not Selected --" ? "None" : $value_option_arr[$value]);
			$html->xform_value($label, $text, DB_STRING, [
				"label_click" => $options["label_click"],
				"label_width" => $options["label_width"],
				"label_col" => $options["label_col"],
				"inject" => $FN_inject,
			]);

			// done
			return $html->get_clean();
		}

		// grouped options
		if (!is_array(\LiquidedgeApp\Octoapp\app\app\arr\arr::get_first_value($value_option_arr))) {
			$value_option_arr = ["!" => $value_option_arr];
		}

		// exclude
  		if (false !== $options["exclude"]) {
			$options["exclude"] = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["exclude"], ["delimiter" => ","]);
		}

		// include
  		if (false !== $options["include"]) {
			$options["include"] = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["include"], ["delimiter" => ","]);
		}

		// save default
		if ($options["savedefault"]) {
			$options["@defaultvalue"] = htmlentities($value);
		}

		// size
		if ($options["size"]) {
			$options["@size"] = $options["size"];
			$options["@multiple"] = true;
		}

		// show checkboxes on iselect with tagged selection
		if ($options["checkboxes"]) {
			// events
			$selector = "\$select2_{$id}";
			$js_on_select = ($options["!close"] ? "{$selector}.on('select2:close', function (e) { {$options["!close"]} });" : false);
			$js_on_unselect = ($options["!unselect"] ? "{$selector}.on('select2:unselect', function (e) { {$options["!unselect"]} });" : false);
			$js_on_close = ($options["!select"] ? "{$selector}.on('select2:select', function (e) { {$options["!select"]} });" : false);

			// value
			$js_value = false;
			$width = ($options["#width"] ?? "element"); // #width will receive actual width style, resolve will 'resolve' 'vanilla' width option (i.e. no #)
			/*if ($value) {
				$select2_value = json_encode(\LiquidedgeApp\Octoapp\app\app\arr\arr::splat($value));
				$js_value = "{$selector}.val({$select2_value}).trigger('change');";
			}*/

			// init select2
			\LiquidedgeApp\Octoapp\app\app\js\js::add_domready_script("
				// destroy existing if exists
				if ($('#{$id}').data('select2')) {
					// destroy
					$('#{$id}').select2('destroy');
					
					// unbind events
					$('#{$id}').off('select2:closing');
					$('#{$id}').off('select2:selecting');
					$('#{$id}').off('select2:unselecting');
				}
			
				// init
				{$selector} = $('#{$id}').select2({
					closeOnSelect: false,
					placeholder: '{$options["placeholder"]}',
					width: '{$width}'
				});
				
				// value
				{$js_value}
				
				// events
				{$js_on_select}
				{$js_on_unselect}
				{$js_on_close}
			");
		}

		// autocomplete
		if ($options["autocomplete"]) {
			// attributes
			$options["@multiple"] = true;

			// init
			$select_all_id = "_select_all";

			// autocomplete options
			$autocomplete_options = [
				"placeholder" => "Start typing for options ...",
				"allow_clear" => true,
				"min_input" => 3,
				"max_selection" => 1,
				"close_on_select" => true,
				"select_all" => false,
				"url" => $options["autocomplete"],
			];

			// close on select only on 1 item
			if ($autocomplete_options["max_selection"] == 1) {
				$autocomplete_options["close_on_select"] = false;
			}

			// config override
			if (is_array($options["autocomplete"])) {
				$autocomplete_options = array_merge($autocomplete_options, $options["autocomplete"]);
			}

			// value
			$js_set_value = false;
			if ($options["autocomplete_value"]) {
				$js_set_value = "
					var option = new Option('{$options["autocomplete_value"]}', {$options["autocomplete_id"]}, true, true);
					$('#{$id}').append(option).trigger('change');
				";
			}

			// script
			\LiquidedgeApp\Octoapp\app\app\js\js::add_domready_script("
				// destroy existing if exists
				if ($('#{$id}').data('select2')) {
					// destroy
					$('#{$id}').select2('destroy');
					
					// unbind events
					$('#{$id}').off('select2:closing');
					$('#{$id}').off('select2:selecting');
					$('#{$id}').off('select2:unselecting');
				}
			
				// init
				$('#{$id}').select2({
					placeholder: '{$autocomplete_options["placeholder"]}',
					allowClear: '{$autocomplete_options["allow_clear"]}',
					minimumInputLength : '{$autocomplete_options["min_input"]}',
					maximumSelectionLength : '{$autocomplete_options["max_selection"]}',
					closeOnSelect : '{$autocomplete_options["close_on_select"]}',
					containerCss: {
						'border-radius' : '0px',
					},
					dropdownCss: {
						'border-radius' : '0px',
					},
					ajax: {
						url: '{$autocomplete_options["url"]},',
						dataType: 'json',
						processResults: function (data) {
							// convert nova's id/value pair to select2's id/text pair
							var data_mapped = $.map(data, function(obj) {
								return { id: obj.id, text: obj.value, id_actual: obj.id };
							});
							
							// store results
							$('#{$id}').data('results', data_mapped);
							
							// prepend 'select all' option if enabled
							if ('{$autocomplete_options["select_all"]}' == 1) {
								data_mapped.unshift({id:'{$select_all_id}', text:'Select all'});
							}
							
							// done
							return {
								results: data_mapped 
							};
						},
					},
					escapeMarkup: function(markup) {
						// escape html generated from templateResult
						return markup;
					},
					templateResult: function (data, container) {
						// customize return elements
						return '<span class=\"select2-results__option-actual\" data-id-parent=\"{$id}\" data-id-actual=\"'+data.id+'\" aria-selected=\"false\">'+data.text+'</span>';
					},
				});
				
				// events for 'select all'
				if ('{$autocomplete_options["select_all"]}' == 1) {
					// event: closing
					$('#{$id}').on('select2:closing', function (e) {
						// only proceed if there are actually any options avaailable
						var available_options = $('.select2-results__options li:not(.select2-results__option.select2-results__message):has(span[data-id-parent={$id}])');
						//var available_options = $('.select2-results__option-actual[data-id-parent={$id}]');
						if (available_options.length == 0) {
							return;
						}
					
						// get selected/deselected options
						var selected_arr = $('.select2-results__option[aria-selected=true] span[data-id-parent={$id}]:not([data-id-actual=_select_all])');
						var deselected_arr = $('.select2-results__option[aria-selected=false] span[data-id-parent={$id}]:not([data-id-actual=_select_all])');
						//var selected_arr = $('.select2-results__option-actual[aria-selected=true][data-id-parent={$id}]:not([data-id-actual=_select_all])');
						//var deselected_arr = $('.select2-results__option-actual[aria-selected=false][data-id-parent={$id}]:not([data-id-actual=_select_all])');
						
						// set data as current value (to allow appending)
						var data = $('#{$id}').val();
						
						// remove deselected options
						$.each(deselected_arr, function(index, value){
							el = $(value);
							id = el.attr('data-id-actual');
							
							// remove option from control
							$('#{$id}').find('option[value='+ id +']').remove();
						});
						
						// add new options to control to allow for setting value
						$.each(selected_arr, function(index, value){
							el = $(value);
							id = el.attr('data-id-actual');
							text = el.text();
							
							data.unshift(id);
							
							// create new option if it's not already there
							if ($('#{$id}').find(\"option[value='\" + id + \"']\").length == 0) {
								var newOption = new Option(text, id, false, false);
								$('#{$id}').append(newOption);
							}
						});
						
						// set value
						$('#{$id}').val(data).trigger('change');
					});
					
					// event: selecting
					$('#{$id}').on('select2:selecting', function (e) {
						var selected = e.params.args.data.id;
						var target = e.params.args.originalEvent.target;
						
						if (selected == '{$select_all_id}') {
							$('.select2-results__option').attr({'aria-selected':true, 'selected':true});
							//$('.select2-results__option-actual').attr({'aria-selected':true, 'selected':true});
						}
						else {
							$(target).attr({'aria-selected':true, 'selected':true});
						}
						
						// stop select event
						e.preventDefault();
					});
					
					// event: unselecting
					$('#{$id}').on('select2:unselecting', function (e) {
						// cater for no specific element trigger (e.g. clear all) - just proceed as usual
						if (typeof e.params.args.originalEvent == 'undefined') {
							return;
						}
						
						// init
						var selected = (typeof e.params.args.data != 'undefined' ? e.params.args.data.id : false);
						var target = e.params.args.originalEvent.target;
						
						if (selected == '{$select_all_id}') {
							$('.select2-results__option').attr({'aria-selected':false, 'selected':false});
							//$('.select2-results__option-actual').attr({'aria-selected':false, 'selected':false});
						}
						else {
							// set attributes 
							$(target).attr({'aria-selected':false, 'selected':false});
						}
						
						// stop unselect event if there are options - unselect is called on deselect in results AND close icon on tags
						var available_options = $('.select2-results__options li:not(.select2-results__option.select2-results__message):has(span[data-id-parent={$id}])');
						//var available_options = $('.select2-results__option-actual[data-id-parent={$id}]');
						if (available_options.length != 0) {
							e.preventDefault();
						}
					});
				}
				
				// intial value
				{$js_set_value}
			");
		}

		// width
		$this->apply_options($options);
		$form_input_options["width"] = $options["width"];

		// tooltip
 		if ($options["tooltip"]) {
 			if (is_array($options["tooltip"])) $options["tooltip"] = implode("<br />", $options["tooltip"]);
			$options["@title"] = $options["tooltip"];
 			\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tooltip();
 		}

		// required
		if (!$options["required_skipval"]) {
			if ($options["required"] && \com\ui\helper::$current_form) {
				if ($options["addnew"]) \com\ui\helper::$current_form->add_validation_emptylistnew($id, $label_validation);
				else \com\ui\helper::$current_form->add_validation_emptylist($id, $label_validation);
			}
		}

		// focus
		if ($options["focus"]) {
			\LiquidedgeApp\Octoapp\app\app\js\js::add_script("$('#{$id}').focus();");
		}

		// add new
		if ($options["addnew"]) {
			// setup add new
			$value_option_arr["!"] = [-2 => '-- Add New --'] + $value_option_arr["!"];
			$options["!change"] = "if ($('#$id').val() == -2) { $('#__{$id}select').hide(); $('#__{$id}addnew').show(); $('#__{$id}add').val('').focus(); }";
			$options["wrapper_id"] = "__{$id}select";

			$options["/addnew"]["required_skipval"] = $options["addnew"];
			$options["/addnew"]["required"] = $options["required"];
			$options["/addnew"]["help"] = $options["help"];
			$options["/addnew"]["wrapper_id"] = "__{$id}addnew";
			$options["/addnew"]["append_type"] = "btn";
			$options["/addnew"]["label_col"] = $options["label_col"];
			$options["/addnew"]["/wrapper"] = $options["/wrapper"];

			$options["remove_signature"] = false;
			foreach ($signature_options AS $key => $value){
				$options["/addnew"][$key] = $value;
			}

			$options["/addnew"]["append"] = function($html) use ($id) {
				$html->xbutton(false, "$('#__{$id}addnew').hide(); $('#__{$id}select').show(); $('#__{$id}add').val(''); $('#{$id}').val($('#{$id}').attr('defaultvalue'));", ["icon" => "ban-circle"]);
			};

			$html->add(\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext("__{$id}add", false, $label, $options["/addnew"]));
			\LiquidedgeApp\Octoapp\app\app\js\js::add_script("$('#__{$id}addnew').hide();");
		}

		//------------------------------
		// function: $FN_input
		$FN_input = function(&$html) use ($value, &$value_option_arr, &$options) {
			// input
			$html->select_(false, $options);

			// values
			foreach ($value_option_arr as $value_option_index => $value_option_item) {
				// option group
				if ($value_option_index != "!") $html->optgroup_(false, ["@label" => $value_option_index]);

				// options
				foreach ($value_option_item as $value_option_item_index => $value_option_item_item) {
					// exclude
					if ($options["exclude"] && in_array($value_option_item_index, $options["exclude"])) continue;

					// include
					if ($options["include"] && !in_array($value_option_item_index, $options["include"])) continue;

					// value
					$option_options = [];
					$option_options["@value"] = $value_option_item_index;

					// disabled
					if ($options["disabled_options"]) {
						if (in_array($value_option_item_index, $options["disabled_options"], true)) $option_options["@disabled"] = true;
					}

					// selected
					if (is_array($value)) {
						if (in_array($value_option_item_index, $value)) $option_options["@selected"] = true;
					}
					elseif ("$value" == "$value_option_item_index") $option_options["@selected"] = true;

					// styles
					if ($options["options_options"] && in_array($value_option_item_index, array_keys($options["options_options"]))) {
						$option_options = array_merge($options["options_options"][$value_option_item_index], $option_options);
					}

					// option specific options
					if (is_array($options["/options"]) && !empty($options["/options"][$value_option_item_index])) {
						$option_options = array_merge($options["/options"][$value_option_item_index], $option_options);
					}

					// option
					$html->option_(false, $option_options);
					$html->content($value_option_item_item);
					$html->_option();
				}

				// option group close
				if ($value_option_index != "!") $html->_optgroup();
			}

			// end input
			$html->_select();
		};
		//------------------------------

		// input
		$form_input_options["append"] = $options["append"];
		$form_input_options["append_type"] = $options["append_type"];
		$form_input_options["wrapper_id"] = $options["wrapper_id"];
		$form_input_options["required_icon"] = $options["required_icon"];
		$form_input_options["required_title"] =  $options["required_title"];
		$form_input_options["/required"] =  $options["/required"];
		$html->xform_input($FN_input, $form_input_options);

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}