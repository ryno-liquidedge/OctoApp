<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class form_value2 extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Vertical form value";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public function value_vertical($label, $value, $type = false, $options = [])

		// options
		$options = array_merge([
			"label" => false,
			"value" => false,
			"type" => false,

			"@onclick" => false,
			"valuehtml" => false,
			"trid" => false,
			".float-right" => false,
		], $options);

		// init
		$label = $options["label"];
		$value = $options["value"];
		$type = $options["type"];

		// init
		$form_group_options = [];

		// value
		if ($type !== false) {
			$value = \LiquidedgeApp\Octoapp\app\app\data\data::format_html($value, $type);
			$options["valuehtml"] = true;
		}

		// value type
		$content_type = ($options["valuehtml"] ? "*" : "^");

		// onclick
		if ($options["@onclick"]) {
			$form_group_options["@onclick"] = $options["@onclick"];
		}

		// pull right
		if ($options[".float-right"]) {
			$form_group_options[".float-right"] = true;
			$form_group_options[".text-right"] = true;
		}

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$html->div_(".form-group", $form_group_options);
		{
			$html->label(".mb-0 ^{$label}");
			$html->div(".form-control-plaintext .pt-0 {$content_type}{$value}");
		}
		$html->_div();

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}