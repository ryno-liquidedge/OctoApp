<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class text extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "text";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"text" => false,

			"sup" => false,
			"/sup" => [".ms-1" => true,],
  		], $options);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_([".text-wrapper d-inline" => true]);

			$options["*"] = $options["text"];
			$buffer->span($options);

			if($options["sup"]){
				$buffer->sup_([".ms-1" => true, ]);
					$buffer->add($options["sup"]);
				$buffer->_sup();
			}
		$buffer->_div();

		// done
		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}