<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class form_value extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Form value";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
	/**
	 * Returns HTML for static text with label.
	 *
	 * @param string $label <p>The name of the element that will be displayed as a label. Providing "" will not show a label, but will still render the underlying structure. Providing false will show no label and will not generate the underlying structure.</p>
	 * @param string $value <p>The text value to display.</p>
	 * @param int $type <p>The formatting to apply to the value before displaying it. Uses DB_ constants.</p>
	 *
	 * @param string $options[wrapper_id] <p>The id to use on the wrapper form control. Used to hide/show label and input group.</p>
	 * @param string $options[input_append_id] <p></p>
	 * @param int|string $options[label_width] <p></p>
	 * @param boolean $options[label_html] <p></p>
	 * @param string|function $options[append] <p>Text to add in a special block attached to the end of the element. A callback function may be used to add other controls and advanced text.</p>
	 *
	 * @param string $options[#] <p>If the option index starts with '#', the rest of the index will be used as an inline style name and the option value as the value. (style="[option-index]:[option-value];")</p>
	 * @param boolean $options[.] <p>If the option index starts with '.' and the option value is 'true', the rest of the index will be added as an inline class. (class="[option-index]")</p>
	 * @param string|boolean $options[@] <p>If the option index starts with '@', the rest of the index will be used as a tag attribute name and the option value as the value. ([option-index]="[option-value]")</p>
	 * @param string $options[!] <p>If the option index starts with '!', the rest of the index will be used as an inline event and the option value as the javascript to execute. (on[option-index]="[option-value]")</p>
	 *
	 * @return string <p>The HTML for the static text.</p>
	 */
	//public static function value($label, $value, $type = DB_STRING, $options = []) {

		// options
		$options = array_merge([
			"type" => false,
			"value" => false,
			"label" => false,

			// advanced
			"novalue" => "None",
			"inject" => false,

			"help" => false,
			"label_width" => false,
			"label_col" => false,
			"label_html" => true,
			"label_click" => false,
			"label_tooltip" => false,
			"/label" => [],
			"wrapper_id" => false,
			"append" => false,
			"append_type" => false,
			"/wrapper" => [],
		], $options);

		// init
		$type = $options["type"];
		$value = $options["value"];
		$label = $options["label"];

		// value
		if (false !== $type) {
			$value = \com\data::format_html($value, $type, ["novalue" => $options["novalue"]]);
		}

		// function: FN_value
		$FN_value = function($html) use ($value, &$options) {
			$html->div(array_merge([
				".form-control-plaintext" => true,
				"*" => $value,
				".pb-0" => (bool)$options["help"],
			], $options));

			if ($options["inject"]) {
				if (is_string($options["inject"])) $html->content($options["inject"], ["html" => true]);
				elseif (is_callable($options["inject"])) $options["inject"]($html);
			}
		};

		// done
		return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->form_input($FN_value, [
			"append" => $options["append"],
			"append_type" => $options["append_type"],

			"label" => $label,
			"label_width" => $options["label_width"],
			"label_col" => $options["label_col"],
			"label_html" => $options["label_html"],
			"label_click" => $options["label_click"],
			"label_tooltip" => $options["label_tooltip"],
			"/label" => $options["/label"],

			"help" => $options["help"],
			"wrapper_id" => $options["wrapper_id"],
			"/wrapper" => $options["/wrapper"],
		]);
	}
	//--------------------------------------------------------------------------------
}