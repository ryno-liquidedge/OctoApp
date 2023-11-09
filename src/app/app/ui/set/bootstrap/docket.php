<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class alert
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class docket extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Docket";
	}
	//--------------------------------------------------------------------------------
    public function build($options = []) {
        $options = array_merge([
        	"text" => false,
        	"icon" => false,
        	"color" => false,
        	"/icon" => [".mr-2" => true],
		], $options);

        $color = $options["color"];

        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

        $options[".text-{$color}"] = (bool) $color;
        $options[".docket"] = true;

        $buffer->span_($options);
			if($options["icon"]) $buffer->xicon($options["icon"], $options["/icon"]);
			$buffer->add($options["text"]);
		$buffer->_span();

        return $buffer->get_clean();
    }
	//--------------------------------------------------------------------------------
}