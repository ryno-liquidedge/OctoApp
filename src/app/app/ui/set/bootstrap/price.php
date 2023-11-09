<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class price extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Price";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"amount" => false,
			".fw-bold" => true,

			"sup" => false,
			"/sup" => [".fw-bold ms-1" => true,],

			"amount_old" => false,
			"/amount_old" => [".text-decoration-line-through ms-1" => true,],

			"include_symbol" => true,
			"decimals" => 2,
			"has_decimals" => true,
			"trim" => 0,

			"append" => false,
  		], $options);

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->div_([".price-wrapper" => true]);

			$options["*"] = \com\num::currency($options["amount"], $options);
			$buffer->span($options);

			if($options["sup"]){
				$buffer->sup_([".fw-bold ms-1" => true, ]);
					$buffer->add($options["sup"]);
				$buffer->_sup();
			}

			if($options["amount_old"]){
				$options["/amount_old"]["*"] = \LiquidedgeApp\Octoapp\app\app\ui\num::currency($options["amount_old"], $options);
				$buffer->span($options["/amount_old"]);
			}

			if($options["append"]){
				$buffer->add(is_callable($options["append"]) ? $options["append"]() : $options["append"]);
			}

		$buffer->_div();

		// done
		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}