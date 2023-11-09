<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class honeypot extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// properties
	//--------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	public function __construct($options = []) {

		// init
        $this->name = "honeypot";

	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

	    $options = array_merge([
	        "id" => false,
	    ], $options);

		// init
        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
        $buffer->div_(["#display" => "none"]);
            $buffer->xitext($options["id"], false, false);
            $buffer->input([
                "@id" => \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("empty_check"),
                "@name" => "empty_check",
                "@type" => "text",
                "@value" => "",
            ]);
        $buffer->_div();

		return $buffer->get_clean();
	}
	//--------------------------------------------------------------------------------
}