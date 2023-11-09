<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\custom;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class copy_text extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// methods
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
			"label" => false,
			"text" => false,
			"@title" => "Click to copy text",
			".copy-text" => true,
			"/wrapper" => [".d-inline" => true],
		], $options);

		if(!$options["text"]) return "";

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$options["/wrapper"][".copy-text-wrapper"] = true;

		$buffer->div_($options["/wrapper"]);
			if($options["label"])$buffer->strong(["*" => $options["label"]]);
			$buffer->xlink("javascript:core.util.copy_text('{$options["text"]}')", $options["text"], $options);
		$buffer->_div();

		return $buffer->build();

	}
  	//--------------------------------------------------------------------------------
}