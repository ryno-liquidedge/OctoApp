<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class iselect_multi extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Multi Select input";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {

		$options = array_merge([
		    "id" => false,
		    "value" => false,
		    "label" => false,
		    "value_option_arr" => [],
		    ".multiselect-ui" => true,
		    "@multiple" => true,

		    "/js" => [],
		], $options);

		$target = \LiquidedgeApp\Octoapp\app\app\str\str::get_random_id("multi_select");
		$options["#display"] = "none";
		$options[".{$target}"] = true;

		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();
		$buffer->xiselect($options["id"], $options["value_option_arr"], $options["value"], $options["label"], $options);

		$js_options = array_merge([

			"*maxHeight" => 300,
			"*enableFiltering" => true,
			"*includeSelectAllOption" => true,
			"*includeResetOption" => true,
			"*nonSelectedText" => "-- None Selected --",

			"*widthSynchronizationMode" => 'ifPopupIsSmaller',

			"*buttonClass" => 'btn btn-primary d-flex align-items-center align-items-center text-start',
			"*buttonWidth" => '100%',

			"*onInitialized" => "!function(select, container){}",
		], $options["/js"]);

		$buffer->script(["*" => "
			$(function(){
				$('.{$target}').multiselect(". \LiquidedgeApp\Octoapp\app\app\js\js::create_options($js_options).");
			});
		"]);

		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}