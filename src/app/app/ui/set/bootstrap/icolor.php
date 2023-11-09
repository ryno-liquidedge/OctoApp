<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class icolor extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;

	protected $options = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Color Input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

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
			".ui-color" => true,
			".form-control" => true,

			// form-input
			"required" => false,
			"required_title" => "Required",
			"/required" => [],
			"required_message" => false,
			"required_icon" => false,
			"help" => false,
			"help_popover" => false,

			"wrapper_id" => false,
			"/wrapper" => [],
			"input_append_id" => false,

			"label_width" => false,
			"label_middle" => true,
			"label_col" => false,
			"label_html" => false,
			"label_click" => false,
			"label_hidden" => false,
			"floating_label" => false,
			"horizontal" => true,

			"/form_input" => [],
			"/input_group" => [],
  		], $options);


  		$form_input_options = array_merge([
			"required" => $options["required"],
			"required_title" => $options["required_title"],
			"/required" => $options["/required"],
			"required_message" => $options["required_message"],
			"required_icon" => $options["required_icon"],
			"help" => $options["help"],
			"help_popover" => $options["help_popover"],

			"wrapper_id" => $options["wrapper_id"],
			"/wrapper" => $options["/wrapper"],
			"input_append_id" => $options["input_append_id"],

			"label" => $options["label"],
			"label_width" => $options["label_width"],
			"label_col" => $options["label_col"],
			"label_html" => $options["label_html"],
			"label_click" => $options["label_click"],
			"floating_label" => $options["floating_label"],
			"horizontal" => $options["horizontal"],
			"/input_group" => $options["/input_group"],
		], $options["/form_input"]);


		// html
		$js_id = $options["id"];
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$buffer->xform_input(function(&$html) use (&$options){
			$html->xinput("color", $options["id"], $options["value"], $options);
		}, $form_input_options);


		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}