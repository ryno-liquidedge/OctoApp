<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class icurrency extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Currency input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
  		$options = array_merge([
  			"id" => false,
  			"value" => false,
  			"label" => false,
  			"group_digits" => false,
  			"@placeholder" => "0.00",

			"prepend" => "R",
			"limit" => "fraction",
			".ui-icurrency" => true,

		    //formating options
		    "include_symbol" => false,
			"decimals" => 2,
			"has_decimals" => !\core::$app->get_instance()->get_option("app.currency.remove.decimals"),
			"trim" => 0,
  		], $options);

  		// currency mask js
		if ($options["group_digits"]) {
			\com\js::add_script("
				$('#{$options["id"]}').inputmask({
				  'alias': 'currency',
				  'groupSeparator': ' ',
				  'autoGroup': true,
				  'digits': 2,
				  'digitsOptional': true,
				  'rightAlign': false,
				  'prefix': '',
				  'showMaskOnHover': false,
				  'greedy': false,
				});
			");
		}

		if ($options["value"]) {
			$options["value"] = \LiquidedgeApp\Octoapp\app\app\ui\num::currency($options["value"], $options);
		}
		// done
		return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($options["id"], $options["value"], $options["label"], $options);
	}
	//--------------------------------------------------------------------------------
}