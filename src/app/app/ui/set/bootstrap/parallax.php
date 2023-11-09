<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class parallax extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Parallax";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
		    "src" => false,
		    "html" => false,

		    "@data-bss-parallax-bg" => "true",
			"#height" => "100vh",
			"#background-position" => "center center",
			"#background-size" => "cover",
			"#position" => "relative",

			".d-flex justify-content-center align-items-center" => true,

		], $options);

		$options["#background-image"] = "url({$options["src"]})";
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$buffer->div_($options);
			if($options["html"]){
				$buffer->div_([".d-flex w-100 justify-content-center" => true]);
					$buffer->add(is_callable($options["html"]) ? $options["html"]() : $options["html"]);
				$buffer->_div();
			}
		$buffer->_div();


		return $buffer->build();

	}
	//--------------------------------------------------------------------------------
}