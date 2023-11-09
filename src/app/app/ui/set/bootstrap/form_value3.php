<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class form_value3 extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Horizontal form value";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public function value($label, $value, $type = false, $options = [])

		// options
		$options = array_merge([
			"label" => false,
			"value" => false,
			"type" => false,

			"trid" => false,
			"vertical" => false,
		], $options);

		// init
		$label = $options["label"];
		$value = $options["value"];
		$type = $options["type"];

		// vertical
		if ($options["vertical"]) {
			return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->form_value2($label, $value, $type, $options);
		}

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$html->xform_value($label, $value, $type, $options);

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}