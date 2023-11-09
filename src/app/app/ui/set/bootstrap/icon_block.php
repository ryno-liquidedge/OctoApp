<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class icon_block extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;

	protected $options = null;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Icon Block";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"icon" => false,
			"color" => "green",
			"background" => "white",
			".icon-block" => true,
		], $options);

		$options[".bg-{$options["background"]}"] = true;
		$options[".text-{$options["color"]}"] = true;

		// html
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_($options);
            $buffer->xicon($options["icon"], [".me-2" => false]);
        $buffer->_div();
		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}