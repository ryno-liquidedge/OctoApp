<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class pagination
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 *
 * https://botmonster.com/jquery-bootpag
 *
 */
class pagination extends \LiquidedgeApp\Octoapp\app\app\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Pagination";
		$this->id = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("pagination");
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		// options
		$options = array_merge([
		    "total" => 200,
		    "page" => 1,
		    "maxVisible" => 5,
		    "firstLastUse" => true,

		    "next" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-angle-right"),
		    "prev" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-angle-left"),

		    "first" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-angle-double-left"),
		    "last" => \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icon("fa-angle-double-right"),

		    "!click" => "function(page){}",

		], $options);

		$pagination = \LiquidedgeApp\Octoapp\app\app\ui\inc\pagination\pagination::make();
		return $pagination->build($options);
	}
	//--------------------------------------------------------------------------------
}