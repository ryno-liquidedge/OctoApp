<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class input_by_type extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Input by type";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// options
		$options = array_merge([
			"id" => false,
			"label" => false,
			"value" => false,
			"value_arr" => [],
			"type" => false,
		], $options);

		$field = $options["id"];
		$value = $options["value"];
		$label = $options["label"];
		$value_arr = $options["value_arr"];

		switch ($options["type"]) {
			// checkbox
  			case \com\data::TYPE_SET        : return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icheckbox($field, $value_arr, $value, $label, array_merge(["bitwise" => true], $options));

			// select
  			case \com\data::TYPE_BOOL 		: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iselect($field, "bool", $value, $label, $options);
            case "select":
            case \com\data::TYPE_REFERENCE 	: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iselect($field, $value_arr, $value, $label, $options);

            // date
  			case \com\data::TYPE_DATE		: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->idate($field, $value, $label, $options);
			case \com\data::TYPE_YEARMONTH	: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->iyearmonth($field, $value, $label, $options);
			case \com\data::TYPE_DATETIME	: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->idatetime($field, $value, $label, $options);

			// text
  			case \com\data::TYPE_SECONDS 	:
  			case \com\data::TYPE_MINUTES 	:
  			case \com\data::TYPE_INT 		:
				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["limit" => "numeric", "width" => "small"], $options));

  			case \com\data::TYPE_FLOAT 		:
  			    if($value == 0) $value = false;
				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["limit" => "fraction", "width" => "medium"], $options));

  			case \com\data::TYPE_STRING     : return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, $options);
			case \com\data::TYPE_EMAIL		: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["limit" => "email"], $options));
  			case \com\data::TYPE_TELNR 		: return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, ($value == "  " ? false : $value), $label, array_merge([".ui-itel" => true], $options));

  			case \com\data::TYPE_JSON 		:
  			case \com\data::TYPE_XML 		:
  			case \com\data::TYPE_HTML 		:
  			case \com\data::TYPE_TEXT 		:
  				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->itext($field, $value, $label, array_merge(["rows" => 5], $options));

  			case \com\data::TYPE_MILISECONDS	:
  			case \com\data::TYPE_DURATION	:
  			case \com\data::TYPE_PERCENTAGE :
  			case \com\data::TYPE_CURRENCY :
				if ($value != "0.0") {
					if (preg_match("/^\\./i", $value)) $value = "0{$value}";
					if (preg_match("/\\.[0-9]*0$/i", $value)) $value = rtrim($value, "0");
					if (preg_match("/\\.$/i", $value)) $value = rtrim($value, ".");
				}
				else $value = "0";

				return \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->icurrency($field, $value, $label, $options);

		}
	}
	//--------------------------------------------------------------------------------
}