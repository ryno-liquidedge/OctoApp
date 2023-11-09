<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class icheckbox extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Checkbox input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
	/*
	 * Returns the HTML for a checkbox input.
	 *
	 * @param string $id <p>The id of the select element used when submitting the form. The id will be used as is when a single checkbox is displayed or the bitwise option is used. When there is multiple checkboxes, the index of the value will be added to the is as such - id[index].</p>
	 * @param string|string[] $value_option_arr <p>An indexed list of available options for the checkboxes - ['index' => 'label'].</p>
	 * @param boolean|boolean[] $value <p>The starting checkbox status when using single checkbox or an array of indexes that needs to be checked when using multi-checkboxes.</p>
	 * @param string $label <p>The name of the element that will be displayed as a label. Providing "" will not show a label, but will still render the underlying structure. Providing false will show no label and will not generate the underlying structure.</p>
	 *
	 * @param string $options[tooltip] <p>Text to display as tooltip when hovering over the element.</p>
	 * @param string[] $options[exclude] <p>Specify an array of indexes from $value_option_arr to remove from the resulting options. Functions as a blacklist.</p>
	 * @param string[] $options[include] <p>Specify an array of indexes from $value_option_arr to include in the resulting options. Functions as a whitelist.</p>
	 * @param int $options[column_count] <p>Divide the checkboxes into this amount of columns before starting a new line. This number must be divisable into 12.</p>
	 * @param boolean $options[inline] <p>This option will display the checkboxes next to each other instead of the default stacked.</p>
	 * @param boolean $options[bitwise] <p>This option will use the indexes of the provided $value_option_arr as a bitmask and will calculate the numeric value thereof to submit with the form. The individual checkboxes will no longer be submitted.</p>
	 *
	 * @param string $options[help] <p>Text to display that is meant to give hints on how to use the element.</p>
	 * @param boolean $options[required] <p>Make the field required. It will be checked for a value when the form is submitted.</p>
	 * @param string $options[wrapper_id] <p>The id to use on the wrapper form control. Used to hide/show label and input group.</p>
	 * @param int|string $options[label_width] <p></p>
	 * @param boolean $options[label_html] <p></p>
	 *
	 * @param string $options[#] <p>If the option index starts with '#', the rest of the index will be used as an inline style name and the option value as the value. (style="[option-index]:[option-value];")</p>
	 * @param boolean $options[.] <p>If the option index starts with '.' and the option value is 'true', the rest of the index will be added as an inline class. (class="[option-index]")</p>
	 * @param string|boolean $options[@] <p>If the option index starts with '@', the rest of the index will be used as a tag attribute name and the option value as the value. ([option-index]="[option-value]")</p>
	 * @param string $options[!] <p>If the option index starts with '!', the rest of the index will be used as an inline event and the option value as the javascript to execute. (on[option-index]="[option-value]")</p>
	 *
	 * @return string <p>HTML for the checkbox input tag.</p>
	 */
	//public static function icheckbox($id, $value_option_arr, $value = false, $label = false, $options = []) {

		// options
		$options = array_merge([
			// basic
			"@disabled" => false,
			"@nosubmit" => false,
			".float-left" => false,
			".float-right" => false,
			"!click" => false,

			// advanced
			".btn-check" => false,
			"/btn-check" => [".btn-sm btn-outline-secondary" => true],
			"tooltip" => false,
			"exclude" => false,
			"disabled_arr" => [],
			"include" => false,
			"column_count" => false,
	      	"inline" => false,
	      	"bitwise" => false,
			"switch" => false,

			// form-input
			"help" => false,
			"required" => false,
			"required_message" => false,
			"required_icon" => true,
			"label_width" => false,
			"label_col" => false,
			"label_html" => false,
			"label_click" => false,
			"label_multi_click" => false,
			"prepend" => false,
			"append" => false,
			"append_type" => false,
			"wrapper_id" => false,
			"/wrapper" => [],
			"/form-check" => [".my-1" => true],
			"/check-label" => [],
		], $options);

		// init
		$id = $options["id"];
		$value_option_arr = $options["value_option_arr"];
		$value = $options["value"];
		$label = $options["label"];

		if($options[".btn-check"]){
			if(!$options["/check-label"]){
				$options["/btn-check"][".btn"] = true;
				$options["/check-label"] = $options["/btn-check"];
			}

			if($options["inline"]){
				$options["/form-check"][".d-inline p-0 me-2"] = true;
				$options["inline"] = false;
			}
		}else{
			$options["/check-label"][".ms-2"] = true;
		}

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// single checkbox value
		if ($value_option_arr === false) {
			$value_option_arr = 1;
		}

		// exclude and include
  		if (false !== $options["exclude"]) $options["exclude"] = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["exclude"], ["delimiter" => ","]);
  		if (false !== $options["include"]) $options["include"] = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["include"], ["delimiter" => ","]);

		// bitwise
		if ($options["bitwise"]) {
			$options["@nosubmit"] = true;
		}

		// tooltip
 		if ($options["tooltip"]) {
 			if (is_array($options["tooltip"])) $options["tooltip"] = implode("<br />", $options["tooltip"]);
			$options["@title"] = $options["tooltip"];
 			\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tooltip();
 		}

		// required
		if ($options["required"] && is_array($value_option_arr)) {
			// required not supported on more than one checkbox
			$options["required"] = false;
		}
		if ($options["required"] && \com\ui\helper::$current_form) {
			\com\ui\helper::$current_form->add_validation_unchecked($id, $label);
		}

		// function: $FN_input
		$FN_input = function(&$html) use ($id, $value, &$value_option_arr, &$options, $label) {
			// bitwise: hidden field for calculation and submit
			if ($options["bitwise"]) {
				$html->xihidden($id, $value);
			}

			// single checkbox
			if (!is_array($value_option_arr)) {
				// checked
				$checkbox_options = [
					//".form-check-input" => true,
					//".position-static" => true,
					".form-check-input" => true,
				];
				if ($value_option_arr == $value) $checkbox_options["@checked"] = true;

				// input
				$options["/form-check"][".form-check"] = true;
				if($options["switch"]) $options["/form-check"][".form-switch"] = true;

				$html->div_($options["/form-check"]);
				{
					$html->xinput("checkbox", $id, $value_option_arr, array_merge($options, $checkbox_options));


					$options["/check-label"]["@for"] = $id;
					$options["/check-label"]["@data-target-id"] = $id;
					$options["/check-label"][".form-check-label"] = true;
					$options["/check-label"][".cursor-pointer"] = true;

					if(isset($options["*"]))$html->label($options["/check-label"]);
				}
				$html->_div();

				// done
				return;
			}

			// multiple checkboxes
			$count = 0;
			$value_arr = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($value);
			foreach ($value_option_arr as $value_option_index => $value_option_item) {
				// exclude and include
				if ($options["exclude"] && in_array($value_option_index, $options["exclude"])) continue;
				if ($options["include"] && !in_array($value_option_index, $options["include"])) continue;

				// columns: start row wrapper if needed and start col for content
				if ($options["column_count"]) {
					if ($count % $options["column_count"] == 0) {
						if ($count != 0) $html->_div();
						$html->div_(".row");
					}
					$col_count = (12 % $options["column_count"] == 0 ? ceil(12 / $options["column_count"]) : 12);
					$html->div_(".col-sm-{$col_count}");
				}

				// id
				$checkbox_options = [
					//".form-check-input" => true,
					".form-check-input" => true,
					"@data-id" => $id,
				];
				$checkbox_id = "{$id}[{$value_option_index}]";
				$identifier = "{$id}_{$value_option_index}";

				if ($options["disabled_arr"]) $checkbox_options["@disabled"] = in_array($value_option_index, $options["disabled_arr"]);

				// bitwise and non-bitwise settings
				if ($options["bitwise"]) {
					// id
					$checkbox_id = "__{$checkbox_id}";

					// checked
					$checkbox_options["@checked"] = in($value_option_index, $value);

					// click for calculation
					$checkbox_options["!click"] = "$('#{$id}').val(core.bit.set($(this).val(), $('#{$id}').val(), $(this).prop('checked')));";
				}
				else {
					// checked
					$checkbox_options["@checked"] = in_array($value_option_index, $value_arr);
				}

				//--------------------
				// group
				$opt_form_check = $options["/form-check"];
				$opt_form_check[".form-check"] = true;
				$opt_form_check[".form-check-inline"] = $options["inline"];
				$opt_form_check[".$identifier"] = true;
				$opt_form_check[".custom-switch"] = $options["switch"];
				$opt_form_check[".custom-checkbox"] = !$options["switch"];

				$html->div_($options["/form-check"]);
				{
					//--------------------
					// input checkbox
					$html->xinput("checkbox", $checkbox_id, $value_option_index, array_merge($options, $checkbox_options));

					//--------------------
					// label
					//$html->label_(".form-check-label", ["@for" => $checkbox_id]);

					$opt_check_label = $options["/check-label"];
					$opt_check_label["@for"] = $checkbox_id;
					$opt_check_label["@data-target-id"] = $checkbox_id;
					$opt_check_label[".form-check-label"] = true;
					$opt_check_label[".cursor-pointer"] = true;

					$html->label_($opt_check_label);
					if (isset($options["label_multi_click"][$value_option_index])) {
						// dropdown
						if ($options["label_multi_click"][$value_option_index] instanceof \com\ui\intf\dropdown) {
							$click_label = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->link($options["label_multi_click"][$value_option_index], $value_option_item);
						}
						else {
							$click_label = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->link(false, $value_option_item, ["!click" => $options["label_multi_click"][$value_option_index] . "  return false;"]);
						}
						$html->content(" {$click_label}", ["html" => true]);
					}
					else {
						$html->content(" {$value_option_item}", ["html" => true]);
					}
					$html->_label();

				}
				$html->_div();

				// columns: end content col and add to counter
				if ($options["column_count"]) {
					$html->_div();
				}
				$count++;
			}

			// columns: end row wrapper
			if ($options["column_count"] && $value_option_arr) {
				$html->_div();
			}
		};

		// input
		$html->xform_input($FN_input, [
			"required" => $options["required"],
			"required_message" => $options["required_message"],
			"required_icon" => $options["required_icon"],
			"help" => $options["help"],
			"prepend" => $options["prepend"],
			"append" => $options["append"],
			"append_type" => $options["append_type"],

			"wrapper_id" => $options["wrapper_id"],
			"/wrapper" => $options["/wrapper"],

			"label" => $label,
			"label_width" => $options["label_width"],
			"label_col" => $options["label_col"],
			"label_html" => $options["label_html"],
			"label_click" => $options["label_click"],

			"input_id" => $id,
		]);

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}