<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * Class.
 *
 * @author Liquid Edge Solutions
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iyearmonth extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Year and month input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		// public static function idatetime($id, $value = false, $label = false, $options = []) {

		// shared
		// options
		$options = array_merge([
			"id" => false,
			"value" => false,
			"label" => false,

			// basic
			"!change" => false,
			"@disabled" => false,
			"@placeholder" => "yyyy-mm",

			"value_format" => "Y-m",
			"@data-js-format" => "YYYY-MM",
			"min_view" => "years",

			// advanced
			"width" => false,
			"hidden" => false,
			"start_date" => false,

			// form-input
			"help" => false,
			"required" => false,
			"prepend" => false,
			"append" => false,
			"wrapper_id" => false,
			"label_width" => false,
			"label_col" => false,
			"label_html" => false,

			".ui-iyearmonth" => true,
		],$options);

		$datetimepicker = \LiquidedgeApp\Octoapp\app\app\inc\datetimepicker\datetimepicker::make();
		return $datetimepicker->build($options);
	}
	//--------------------------------------------------------------------------------
}