<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class idate extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct() {
		$this->name = "Date input";
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

			// basic
			"!change" => false,
			"@disabled" => false,
			"@placeholder" => "yyyy-mm-dd",

			"value_format" => "Y-m-d",
			"@data-js-format" => "YYYY-MM-DD",

			// advanced
			"width" => false,
			"hidden" => false,
			"start_date" => false,
			"end_date" => false,
			"min_view" => "days", // lowest view to go down to
			"start_view" => false, // which view to start at
			"view_select" => false, // which view to trigger selection at

			// form-input
			"help" => false,
			"required" => false,
			"prepend" => false,
			"append" => false,
			"wrapper_id" => false,
			"label_width" => false,
			"label_col" => false,
			"label_html" => false,

			".ui-idate" => true,

		],$options);

		$datetimepicker = \LiquidedgeApp\Octoapp\app\app\inc\datetimepicker\datetimepicker::make();
		return $datetimepicker->build($options);

	}
	//--------------------------------------------------------------------------------
}