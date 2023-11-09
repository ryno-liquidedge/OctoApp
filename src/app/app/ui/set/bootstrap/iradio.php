<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iradio extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Radio input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
	/*
	 * Returns the HTML for a radio button.
	 *
	 * @param string $id <p>The id of the select element used when submitting the form.</p>
	 * @param string|string[] $value_option_arr <p>An indexed list of available options for the radio buttons - ['index' => 'label'].</p>
	 * @param boolean|boolean[] $value <p>The index of the radio button that will be checked.</p>
	 * @param string $label <p>The name of the element that will be displayed as a label. Providing "" will not show a label, but will still render the underlying structure. Providing false will show no label and will not generate the underlying structure.</p>
	 *
	 * @param string $options[tooltip] <p>Text to display as tooltip when hovering over the element.</p>
	 * @param string[] $options[exclude] <p>Specify an array of indexes from $value_option_arr to remove from the resulting options. Functions as a blacklist.</p>
	 * @param string[] $options[include] <p>Specify an array of indexes from $value_option_arr to include in the resulting options. Functions as a whitelist.</p>
	 * @param int $options[column_count] <p>Divide the radio buttons into this amount of columns before starting a new line. This number must be divisable into 12.</p>
	 * @param boolean $options[inline] <p>This option will display the radio buttons next to each other instead of the default stacked.</p>
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
	 * @return string <p>HTML for the radio button tag.</p>
	 */
	//public static function iradio($id, $value_option_arr, $value = false, $label = false, $options = []) {

		// options
		$options = array_merge([
			"id" => false,
			"value" => false,
			"label" => false,
			"value_option_arr" => false,

			// basic
			"@disabled" => false,
			"@nosubmit" => false,
			"!click" => false,

			// advanced
			".btn-check" => false,
			"/btn-check" => [".btn-sm btn-outline-primary" => true],
			"tooltip" => false,
			"exclude" => false,
			"include" => false,
			"column_count" => false,
	      	"inline" => false,

			// form-input
			"help" => false,
			"required" => false,
			"label_width" => false,
			"label_col" => false,
			"label_html" => false,
			"prepend" => false,
			"append" => false,
			"wrapper_id" => false,
			"/form-check" => [],
			"/check-label" => [],
		], $options);

		// init
		$id = $options["id"];
		$value = $options["value"];
		$label = $options["label"];
		$value_option_arr = $options["value_option_arr"];

		if($options[".btn-check"]){
			if(!$options["/check-label"])
				$options["/btn-check"][".btn"] = true;
				$options["/check-label"] = $options["/btn-check"];

			$options["!click"] .= "
				$('label[data-target-id={$id}]').removeClass('active');
				$('#{$id}:checked').closest('label').addClass('active');
			";

			if($options["inline"]){
				$options["/form-check"][".d-inline p-0 me-2"] = true;
				$options["inline"] = false;
			}
		}

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		// exclude and include
  		if (false !== $options["exclude"]) $options["exclude"] = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["exclude"], ["delimiter" => ","]);
  		if (false !== $options["include"]) $options["include"] = \LiquidedgeApp\Octoapp\app\app\arr\arr::splat($options["include"], ["delimiter" => ","]);

		// tooltip
 		if ($options["tooltip"]) {
 			if (is_array($options["tooltip"])) $options["tooltip"] = implode("<br />", $options["tooltip"]);
			$options["@title"] = $options["tooltip"];
 			\LiquidedgeApp\Octoapp\app\app\ui\ui::make()->tooltip();
 		}

		// required
		if ($options["required"] && \com\ui\helper::$current_form) {
			\com\ui\helper::$current_form->add_validation_zero($id, $label);
		}

		// function: $FN_input
		$FN_input = function(&$html) use ($id, $value, &$value_option_arr, &$options) {
			$count = 0;
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

				// checked
				$radio_options = [
					".form-check-input" => true,
				];
				if (false !== $value && "{$value_option_index}" == "{$value}") {
					\com\js::add_script("$('#{$id}[value=\"{$value}\"]').attr('checked', true);");
					if($options[".btn-check"]){
						\com\js::add_script("$('#{$id}[value=\"{$value}\"]').closest('label').addClass('active');");
					}
				}

				//--------------------
				// group
				$options["/form-check"][".form-check"] = true;
				$options["/form-check"][".form-check-inline"] = $options["inline"];
				$html->div_($options["/form-check"]);
				{
					//--------------------
					// label

					$options["/check-label"]["@data-target-id"] = $id;
					$options["/check-label"][".form-check-label"] = true;
					$options["/check-label"][".cursor-pointer"] = true;
					$options["/check-label"][".my-1"] = true;

					$html->label_($options["/check-label"]);

						//--------------------
						// input radio
						$html->xinput("radio", $id, $value_option_index, array_merge($options, $radio_options));

						$html->span(["*" => $value_option_item]);
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
			if ($options["column_count"]) {
				$html->_div();
			}
		};

		// input
		$html->xform_input($FN_input, [
			"required" => $options["required"],
			"help" => $options["help"],
			"prepend" => $options["prepend"],
			"append" => $options["append"],

			"wrapper_id" => $options["wrapper_id"],

			"label" => $label,
			"label_width" => $options["label_width"],
			"label_col" => $options["label_col"],
			"label_html" => $options["label_html"],

			"input_id" => $id,
		]);

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}