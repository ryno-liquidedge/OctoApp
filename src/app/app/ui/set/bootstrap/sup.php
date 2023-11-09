<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class alert
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */

class sup extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {

	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Sup";
	}
	//--------------------------------------------------------------------------------
    public function build($options = []) {

        $options = array_merge([
        	"pre_text" => false,
        	"/pre_text" => [],

        	"text" => false,

        	"post_text" => false,
        	"/tepost_textxt" => [],
		], $options);


        $buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		if($options["pre_text"]){
			$options["/pre_text"]["*"] = $options["pre_text"];
			$buffer->span($options["/pre_text"]);
		}

		if($options["text"]){
			$options["*"] = $options["text"];
			$buffer->sup($options);
		}

		if($options["post_text"]){
			$options["/post_text"]["*"] = $options["post_text"];
			$buffer->span($options["/post_text"]);
		}

        return $buffer->build();
    }
	//--------------------------------------------------------------------------------
}