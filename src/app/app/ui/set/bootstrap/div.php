<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class div extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {

		// init
        $this->name = "div";
		$this->html = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		// init
        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
        $buffer->div($options);

		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}