<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class form_value4 extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Plaintext form value";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"value" => false,
		], $options);

		// html
		$html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$html->div(".form-control-plaintext ^{$options["value"]}", $options);

		// done
		return $html->get_clean();
	}
	//--------------------------------------------------------------------------------
}